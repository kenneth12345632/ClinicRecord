<?php

namespace App\Http\Controllers;

use App\Models\InventoryLog;
use App\Models\Medicine;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    private function ensureDoctorCannotMutateInventory(): void
    {
        if ((auth()->user()->role ?? null) === 'doctor') {
            abort(403, 'Doctors are not allowed to modify inventory.');
        }
    }

    /**
     * Display the main inventory list.
     */
    public function index() 
    {
        $medicines = Medicine::orderBy('arrival_date', 'desc')->get();
        $today = now()->startOfDay();
        $expiryThreshold = $today->copy()->addDays(30);

        $expiringSoonMedicines = $medicines
            ->filter(function (Medicine $medicine) use ($today, $expiryThreshold) {
                return $medicine->stock > 0
                    && $medicine->expiration_date
                    && $medicine->expiration_date->gte($today)
                    && $medicine->expiration_date->lte($expiryThreshold);
            })
            ->groupBy('name')
            ->map(function ($lots, $name) use ($today) {
                $nearestLot = $lots->sortBy('expiration_date')->first();

                return [
                    'name' => $name, 
                    'expiration_date' => $nearestLot->expiration_date,
                    'days_left' => $today->diffInDays($nearestLot->expiration_date),
                    'total_stock' => $lots->sum('stock'),
                ];
            })
            ->sortBy('days_left')
            ->values();

        $inventoryLogs = InventoryLog::with(['medicine', 'user'])
            ->latest()
            ->get()
            ->groupBy(function ($log) {
                return (string) optional($log->medicine)->name;
            });

        return view('medicines.index', [
            'medicines' => $medicines,
            'expiringSoonMedicines' => $expiringSoonMedicines,
            'inventoryLogs' => $inventoryLogs,
        ]);
    }

    public function create()
    {
        $this->ensureDoctorCannotMutateInventory();

        $catalog = $this->medicineCatalogSuggestionCollections();

        return view('medicines.create', $catalog);
    }

    /**
     * @return array{dbGenericOptions: \Illuminate\Support\Collection, dbBrandOptions: \Illuminate\Support\Collection, dbTypeOptions: \Illuminate\Support\Collection}
     */
    private function medicineCatalogSuggestionCollections(): array
    {
        $rows = Medicine::query()
            ->select(['name', 'type'])
            ->get();

        $dbGenericOptions = [];
        $dbBrandOptions = [];
        $dbTypeOptions = [];

        foreach ($rows as $row) {
            $fullName = trim((string) ($row->name ?? ''));
            $type = trim((string) ($row->type ?? ''));

            if ($type !== '') {
                $dbTypeOptions[] = $type;
            }

            if ($fullName !== '' && preg_match('/^(.*?)\s*\((.*?)\)/', $fullName, $matches)) {
                $brand = trim((string) ($matches[1] ?? ''));
                $generic = trim((string) ($matches[2] ?? ''));

                if ($brand !== '') {
                    $dbBrandOptions[] = $brand;
                }
                if ($generic !== '') {
                    $dbGenericOptions[] = $generic;
                }
            }
        }

        return [
            'dbGenericOptions' => collect($dbGenericOptions)->filter()->unique()->values(),
            'dbBrandOptions' => collect($dbBrandOptions)->filter()->unique()->values(),
            'dbTypeOptions' => collect($dbTypeOptions)->filter()->unique()->values(),
        ];
    }

    /**
     * Display names from Add Medicine: "Brand (Generic) 500mg Tablet".
     *
     * @return array{brand: string, generic: string, dosage_value: string, dosage_unit: string, type: string, parsed: bool}
     */
    private function parseMedicineDisplayName(Medicine $medicine): array
    {
        $name = trim((string) $medicine->name);
        $pattern = '/^(?<brand>.+?)\s*\((?<generic>.+)\)\s*(?<dosage>[\d.]+)\s*(?<unit>mcg|mg|g|ml)\s+(?<type>.+)$/u';

        $fallbackUnit = $medicine->dosage_unit && in_array($medicine->dosage_unit, ['mcg', 'mg', 'g', 'ml'], true)
            ? $medicine->dosage_unit
            : 'mg';

        $out = [
            'brand' => '',
            'generic' => '',
            'dosage_value' => $medicine->dosage_value !== null ? (string) $medicine->dosage_value : '',
            'dosage_unit' => $fallbackUnit,
            'type' => $medicine->type ? trim((string) $medicine->type) : '',
            'parsed' => false,
        ];

        if ($name !== '' && preg_match($pattern, $name, $m)) {
            $out['brand'] = trim($m['brand']);
            $out['generic'] = trim($m['generic']);
            $out['dosage_value'] = $m['dosage'];
            $out['dosage_unit'] = $m['unit'];
            $out['type'] = trim($m['type']);
            $out['parsed'] = true;

            return $out;
        }

        if ($name !== '' && preg_match('/^(?<brand>.+?)\s*\((?<generic>.+)\)/u', $name, $m)) {
            $out['brand'] = trim($m['brand']);
            $out['generic'] = trim($m['generic']);
        } elseif ($name !== '') {
            $out['brand'] = $name;
        }

        return $out;
    }

    /**
     * Store a new medicine batch/lot.
     */
    public function store(Request $request)
    {
        $this->ensureDoctorCannotMutateInventory();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // For "Add New Medicine" these are provided; for "Add Stock" modal they can be omitted.
            'type' => 'nullable|string|max:50',
            'dosage_value' => 'nullable|numeric|min:0.01',
            'dosage_unit' => 'nullable|in:mcg,mg,g,ml',
            'batch_number' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'expiration_date' => 'required|date|after_or_equal:today',
            // Changed from created_at to arrival_date
            'arrival_date' => 'required|date|after_or_equal:today',
        ]);

        $hasPreviousStockInForSameName = InventoryLog::query()
            ->where('transaction_type', 'stock_in')
            ->whereHas('medicine', function ($query) use ($validated) {
                $query->where('name', $validated['name']);
            })
            ->exists();

        $medicine = Medicine::create($validated);
        InventoryLog::create([
            'medicine_id' => $medicine->id,
            'transaction_type' => 'stock_in',
            'quantity' => (int) $medicine->stock,
            'balance_after' => (int) $medicine->stock,
            'reference' => $hasPreviousStockInForSameName ? 'Stock Replenishment' : 'Initial stock entry',
            'created_by' => auth()->id(),
        ]);
        ActivityLogger::log('medicine_created', "Added medicine {$medicine->name}", $medicine, $request);

        return redirect()->route('medicines.index')
            ->with('success', 'New batch added successfully!');
    }

    public function edit(Medicine $medicine)
    {
        $this->ensureDoctorCannotMutateInventory();

        $catalog = $this->medicineCatalogSuggestionCollections();
        $parsed = $this->parseMedicineDisplayName($medicine);

        $inventoryLogs = InventoryLog::with(['medicine', 'user'])
            ->where('medicine_id', $medicine->id)
            ->latest()
            ->get();

        $consultationIds = $inventoryLogs
            ->pluck('reference')
            ->filter()
            ->map(function (string $reference) {
                if (preg_match('/Dispensed for consultation #(\d+)/i', $reference, $matches)) {
                    return (int) $matches[1];
                }

                return null;
            })
            ->filter()
            ->unique()
            ->values();

        $consultationNames = \App\Models\ClinicRecord::query()
            ->whereIn('id', $consultationIds)
            ->get()
            ->mapWithKeys(function (\App\Models\ClinicRecord $record) {
                $fullName = trim($record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name);
                return [$record->id => $fullName];
            });

        return view('medicines.edit', [
            'medicine' => $medicine,
            'parsedMedicineName' => $parsed,
            'inventoryLogs' => $inventoryLogs,
            'consultationNames' => $consultationNames,
        ] + $catalog);
    }

    /**
     * Update the specified medicine batch.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $this->ensureDoctorCannotMutateInventory();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'dosage_value' => 'nullable|numeric|min:0.01',
            'dosage_unit' => 'nullable|in:mcg,mg,g,ml',
            'batch_number' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
            // Ensure the update also handles the custom arrival date
            'arrival_date' => 'required|date',
        ]);

        $oldStock = (int) $medicine->stock;
        $medicine->update($validated);
        $newStock = (int) $medicine->stock;
        $delta = $newStock - $oldStock;
        if ($delta !== 0) {
            InventoryLog::create([
                'medicine_id' => $medicine->id,
                'transaction_type' => $delta > 0 ? 'stock_in' : 'adjustment',
                'quantity' => $delta,
                'balance_after' => $newStock,
                'reference' => 'Manual inventory adjustment',
                'created_by' => auth()->id(),
            ]);
        }
        ActivityLogger::log('medicine_updated', "Updated medicine {$medicine->name}", $medicine, $request);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine updated successfully!');
    }

    /**
     * Remove a specific single lot/batch.
     */
    public function destroy(Medicine $medicine)
    {
        $this->ensureDoctorCannotMutateInventory();
        ActivityLogger::log('medicine_deleted', "Deleted medicine {$medicine->name}", $medicine, request());
        $medicine->delete();
        return redirect()->route('medicines.index')
            ->with('success', 'Specific batch removed.');
    }

    /**
     * Remove ALL batches/lots belonging to a specific medicine name.
     */
    public function destroyGroup(Request $request)
    {
        $this->ensureDoctorCannotMutateInventory();
        // Deletes the entire medicine category (e.g., all Paracetamol lots)
        Medicine::where('name', $request->name)->delete();
        ActivityLogger::log('medicine_group_deleted', "Deleted medicine group {$request->name}", null, $request);
        
        return redirect()->route('medicines.index')
            ->with('success', 'All records for ' . $request->name . ' have been deleted.');
    }
}