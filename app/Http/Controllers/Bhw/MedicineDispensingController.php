<?php

namespace App\Http\Controllers\Bhw;

use App\Http\Controllers\Controller;
use App\Models\ClinicRecord;
use App\Models\InventoryLog;
use App\Models\Medicine;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MedicineDispensingController extends Controller
{
    /** Route name prefix: `bhw` or `admin` (same screens, different URLs). */
    private function routePrefix(): string
    {
        $name = request()->route()?->getName() ?? '';

        return str_starts_with((string) $name, 'admin.') ? 'admin' : 'bhw';
    }

    private function dispensingIndexRoute(): string
    {
        return $this->routePrefix() . '.dispensing.index';
    }

    /** Same grouping key as DoctorClinicRecordController when reserving lots. */
    private function normalizeMedicineName(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name));

        return mb_strtolower($name);
    }

    /** Where to land after medicines are released so the visit shows on Clinic Records. */
    private function patientRecordsIndexRoute(): string
    {
        return $this->routePrefix() === 'admin' ? 'record.index' : 'bhw.record.index';
    }

    public function index()
    {
        $records = ClinicRecord::query()
            ->awaitingMedicineDispensing()
            ->withCount([
                'medicines as pending_medicine_lines' => function ($q) {
                    $q->whereNull('clinic_record_medicine.dispensed_at');
                },
            ])
            ->orderByDesc('consultation_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('bhw.dispensing.index', [
            'records' => $records,
            'dispensingRoutePrefix' => $this->routePrefix(),
        ]);
    }

    public function show(ClinicRecord $record)
    {
        if (!$this->recordHasPendingDispensing($record)) {
            return redirect()
                ->route($this->dispensingIndexRoute())
                ->with('info', 'This visit is no longer in the medicine queue.');
        }

        $record->load([
            'medicines' => function ($q) {
                $q->orderBy('clinic_record_medicine.id');
            },
        ]);

        $pending = $record->medicines->filter(fn ($m) => $m->pivot->dispensed_at === null);
        $completed = $record->medicines->filter(fn ($m) => $m->pivot->dispensed_at !== null);

        return view('bhw.dispensing.show', [
            'record' => $record,
            'pendingMedicines' => $pending,
            'completedMedicines' => $completed,
            'dispensingRoutePrefix' => $this->routePrefix(),
        ]);
    }

    public function dispense(Request $request, ClinicRecord $record): RedirectResponse
    {
        if (!$this->recordHasPendingDispensing($record)) {
            return redirect()
                ->route($this->dispensingIndexRoute())
                ->with('info', 'This visit is no longer in the medicine queue.');
        }

        $validated = $request->validate([
            'dispense_quantities' => ['nullable', 'array'],
            'dispense_quantities.*' => ['nullable', 'integer', 'min:0'],
            'release_note' => ['nullable', 'string', 'max:1000'],
        ]);
        $requestedQuantities = collect($validated['dispense_quantities'] ?? [])
            ->mapWithKeys(fn ($qty, $rowId) => [(int) $rowId => (int) $qty]);
        $manualReleaseNote = trim((string) ($validated['release_note'] ?? ''));

        $summary = [];
        $partialSummary = [];
        $hasAnyReleased = false;

        DB::transaction(function () use ($record, $requestedQuantities, $manualReleaseNote, &$summary, &$partialSummary, &$hasAnyReleased) {
            $rows = DB::table('clinic_record_medicine')
                ->where('clinic_record_id', $record->id)
                ->whereNull('dispensed_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($rows->isEmpty()) {
                $record->refresh();
                if ($record->published_to_registry_at === null) {
                    $record->update([
                        'published_to_registry_at' => now(),
                        'medicines_given' => trim((string) $record->medicines_given) !== ''
                            ? $record->medicines_given
                            : ('Released to patient records by BHW: ' . $this->bhwDisplayName() . ' on ' . now()->format('M d, Y g:i A')),
                    ]);
                }

                return;
            }

            $today = Carbon::today();

            foreach ($rows as $row) {
                $plannedLot = Medicine::query()->whereKey($row->medicine_id)->lockForUpdate()->first();
                if (!$plannedLot) {
                    throw ValidationException::withMessages([
                        'dispense' => 'Medicine lot no longer exists. Contact an administrator.',
                    ]);
                }

                $plannedQty = (int) $row->quantity;
                $qty = $requestedQuantities->has((int) $row->id)
                    ? (int) $requestedQuantities->get((int) $row->id)
                    : $plannedQty;

                if ($qty < 0 || $qty > $plannedQty) {
                    throw ValidationException::withMessages([
                        'dispense' => "Invalid quantity for {$plannedLot->name}. Enter 0 to {$plannedQty} only.",
                    ]);
                }
                if ($qty === 0) {
                    DB::table('clinic_record_medicine')->where('id', $row->id)->delete();
                    $partialSummary[] = "Only 0 of {$plannedLot->name} released (prescribed {$plannedQty}).";
                    continue;
                }

                // Prescription rows point at a batch from when the doctor saved. Stock may later move to
                // newer batches — take from all non-expired batches of the same medicine (FEFO), like consult save.
                $medicineKey = $this->normalizeMedicineName((string) $plannedLot->name);

                $familyLots = Medicine::query()
                    ->where(function ($query) use ($today) {
                        $query->whereNull('expiration_date')
                            ->orWhereDate('expiration_date', '>=', $today);
                    })
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->filter(fn (Medicine $m) => $this->normalizeMedicineName((string) $m->name) === $medicineKey)
                    ->sortBy(function (Medicine $m) {
                        return sprintf(
                            '%s-%s-%020d',
                            $m->expiration_date?->format('Y-m-d') ?? '9999-99-99',
                            $m->arrival_date?->format('Y-m-d') ?? '0000-00-00',
                            $m->id
                        );
                    })
                    ->values();

                if ($familyLots->isEmpty()) {
                    throw ValidationException::withMessages([
                        'dispense' => "Cannot release {$plannedLot->name}: no non-expired batches found.",
                    ]);
                }

                $availableStock = $familyLots->sum(fn (Medicine $m) => (int) $m->stock);
                if ($availableStock < $qty) {
                    throw ValidationException::withMessages([
                        'dispense' => "Insufficient stock for {$plannedLot->name}. Need {$qty}, have {$availableStock} (non-expired batches only).",
                    ]);
                }

                $remaining = $qty;
                $takes = [];

                foreach ($familyLots as $candidate) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $canTake = min($remaining, (int) $candidate->stock);
                    if ($canTake <= 0) {
                        continue;
                    }

                    $candidate->decrement('stock', $canTake);
                    $candidate->refresh();

                    InventoryLog::create([
                        'medicine_id' => $candidate->id,
                        'transaction_type' => 'stock_out',
                        'quantity' => -$canTake,
                        'balance_after' => (int) $candidate->stock,
                        'reference' => "Dispensed for consultation #{$record->id}",
                        'created_by' => Auth::id(),
                    ]);

                    $takes[] = [
                        'medicine_id' => $candidate->id,
                        'quantity' => $canTake,
                        'label' => $candidate->name,
                    ];

                    $remaining -= $canTake;
                }

                if ($remaining > 0) {
                    throw ValidationException::withMessages([
                        'dispense' => "Could not reserve stock for {$plannedLot->name}. Please retry.",
                    ]);
                }

                $now = now();
                $remainingPlan = $plannedQty - $qty;
                if ($remainingPlan > 0) {
                    DB::table('clinic_record_medicine')->where('id', $row->id)->delete();
                    $partialSummary[] = "Only {$qty} of {$plannedLot->name} released (prescribed {$plannedQty}).";
                } else {
                    DB::table('clinic_record_medicine')->where('id', $row->id)->delete();
                }

                foreach ($takes as $take) {
                    DB::table('clinic_record_medicine')->insert([
                        'clinic_record_id' => $record->id,
                        'medicine_id' => $take['medicine_id'],
                        'quantity' => $take['quantity'],
                        'dispensed_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $summary[] = "{$take['label']} (x{$take['quantity']})";
                }
                $hasAnyReleased = true;
            }

            if ($summary !== []) {
                $bhwName = $this->bhwDisplayName();
                $record->refresh();
                $releaseNoteText = $manualReleaseNote !== '' ? (' | BHW note: ' . $manualReleaseNote) : '';
                $record->update([
                    'medicines_given' => 'Given: ' . implode(', ', $summary) . ' | Released by BHW: ' . $bhwName . ' on ' . now()->format('M d, Y g:i A') . $releaseNoteText,
                    'published_to_registry_at' => now(),
                ]);
            }
        });

        $record->refresh();
        $remainingPendingRows = DB::table('clinic_record_medicine')
            ->where('clinic_record_id', $record->id)
            ->whereNull('dispensed_at')
            ->count();

        if ($remainingPendingRows === 0 && $record->published_to_registry_at === null) {
            $record->update([
                'published_to_registry_at' => now(),
            ]);
            $record->refresh();
        }

        if ($record->published_to_registry_at && $remainingPendingRows === 0) {
            ActivityLogger::log(
                $summary !== [] ? 'medicine_dispensed' : 'consultation_published_to_registry',
                $summary !== []
                    ? "BHW released medicines for {$record->first_name} {$record->last_name}"
                    : "BHW published visit to registry for {$record->first_name} {$record->last_name}",
                $record,
                $request
            );

            $patientLabel = trim("{$record->first_name} {$record->last_name}");
            $msg = $summary !== []
                ? "Medicines confirmed. {$patientLabel} now appears on Clinic Records for this visit."
                : "{$patientLabel} is now visible on Clinic Records.";
            if ($partialSummary !== []) {
                $msg .= ' ' . implode(' ', $partialSummary);
            }

            return redirect()
                ->route($this->patientRecordsIndexRoute())
                ->with('success', $msg)
                ->with('show_patients', true);
        }

        if ($hasAnyReleased) {
            $msg = 'Release saved.';
            if ($partialSummary !== []) {
                $msg .= ' ' . implode(' ', $partialSummary);
            }
            $msg .= ' Remaining medicines stay in queue.';

            return redirect()
                ->route($this->routePrefix() . '.dispensing.show', $record)
                ->with('success', $msg);
        }

        return redirect()
            ->route($this->dispensingIndexRoute())
            ->with('info', 'Nothing left to release for this visit.');
    }

    private function recordHasPendingDispensing(ClinicRecord $record): bool
    {
        return ClinicRecord::query()
            ->whereKey($record->id)
            ->awaitingMedicineDispensing()
            ->exists();
    }

    private function bhwDisplayName(): string
    {
        $u = Auth::user();
        if (!$u) {
            return 'BHW';
        }

        $name = trim(implode(' ', array_filter([
            $u->first_name ?? null,
            $u->middle_name ?? null,
            $u->last_name ?? null,
        ])));

        return $name !== '' ? $name : 'BHW';
    }
}
