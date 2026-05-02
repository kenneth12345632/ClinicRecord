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
            abort(404);
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

        $summary = [];

        DB::transaction(function () use ($record, &$summary) {
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
                $lot = Medicine::query()->whereKey($row->medicine_id)->lockForUpdate()->first();
                if (!$lot) {
                    throw ValidationException::withMessages([
                        'dispense' => 'Medicine lot no longer exists. Contact an administrator.',
                    ]);
                }

                if ($lot->expiration_date && $lot->expiration_date->lt($today)) {
                    throw ValidationException::withMessages([
                        'dispense' => "Lot {$lot->name} is expired and cannot be released.",
                    ]);
                }

                $qty = (int) $row->quantity;
                if ($qty <= 0) {
                    continue;
                }

                if ($lot->stock < $qty) {
                    throw ValidationException::withMessages([
                        'dispense' => "Insufficient stock for {$lot->name}. Need {$qty}, have {$lot->stock}.",
                    ]);
                }

                $lot->decrement('stock', $qty);
                $lot->refresh();

                InventoryLog::create([
                    'medicine_id' => $lot->id,
                    'transaction_type' => 'stock_out',
                    'quantity' => -$qty,
                    'balance_after' => (int) $lot->stock,
                    'reference' => "Dispensed for consultation #{$record->id}",
                    'created_by' => Auth::id(),
                ]);

                DB::table('clinic_record_medicine')
                    ->where('id', $row->id)
                    ->update(['dispensed_at' => now()]);

                $summary[] = "{$lot->name} (x{$qty})";
            }

            if ($summary !== []) {
                $bhwName = $this->bhwDisplayName();
                $record->refresh();
                $record->update([
                    'medicines_given' => 'Given: ' . implode(', ', $summary) . ' | Released by BHW: ' . $bhwName . ' on ' . now()->format('M d, Y g:i A'),
                    'published_to_registry_at' => now(),
                ]);
            }
        });

        $record->refresh();

        if ($record->published_to_registry_at) {
            ActivityLogger::log(
                $summary !== [] ? 'medicine_dispensed' : 'consultation_published_to_registry',
                $summary !== []
                    ? "BHW released medicines for {$record->first_name} {$record->last_name} (record #{$record->id})"
                    : "BHW published visit to registry for {$record->first_name} {$record->last_name} (record #{$record->id})",
                $record,
                $request
            );

            $patientLabel = trim("{$record->first_name} {$record->last_name}");
            $msg = $summary !== []
                ? "Medicines confirmed. {$patientLabel} now appears on Clinic Records for this visit."
                : "{$patientLabel} is now visible on Clinic Records.";

            return redirect()
                ->route($this->patientRecordsIndexRoute())
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
