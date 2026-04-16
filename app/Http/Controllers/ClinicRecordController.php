<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicRecord; 
use App\Models\Medicine;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ClinicRecordController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $ageGroup = $request->input('age_group');

        // Group records by patient_name and get the latest ID for unique history rows
        $latestRecordIds = ClinicRecord::selectRaw('MAX(id) as id')
            ->groupBy('patient_name')
            ->pluck('id');

        $query = ClinicRecord::whereIn('id', $latestRecordIds)
            ->orderBy('consultation_date', 'desc');

        // Multi-Column Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('age', 'like', "%{$search}%")
                  ->orWhere('medicines_given', 'like', "%{$search}%");
            });
        }

        // Age Group Filtering logic
        if ($request->filled('age_group')) {
            $today = now();
            if ($ageGroup == 'infant') {
                $query->where('birthday', '>=', $today->copy()->subMonths(11));
            } elseif ($ageGroup == 'child') {
                $query->whereBetween('birthday', [
                    $today->copy()->subMonths(59), 
                    $today->copy()->subMonths(12)
                ]);
            } elseif ($ageGroup == 'senior') {
                $query->where('birthday', '<=', $today->copy()->subYears(60));
            }
        }

        $records = $query->get();

        return view('record.index', [
            'records'   => $records,
            'search'    => $search,
            'age_group' => $ageGroup
        ]);
    }

    public function create(): View
    {
        /**
         * UPDATED: FEFO (First Expiry, First Out) Logic
         * We sort by expiration_date first so that unique() picks the batch 
         * expiring soonest (the "Priority" batch).
         */
        $allMedicines = Medicine::where('stock', '>', 0)
            ->orderBy('expiration_date', 'asc') 
            ->get()
            ->unique('name'); // Removes duplicates from the dropdown

        return view('record.create', [
            'allMedicines' => $allMedicines
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_name'      => 'required|string|max:255',
            'consultation_date' => 'required|date',
            'birthday'          => 'required|date',
            'gender'            => 'required|string',
            'diagnosis'         => 'nullable|string',
            'medicines'         => 'nullable|array', 
        ]);

        $validated['patient_name'] = trim($request->patient_name);

        DB::transaction(function () use ($request, $validated) {
            $medicineDescriptions = [];

            if ($request->has('medicines')) {
                foreach ($request->medicines as $item) {
                    $medicine = Medicine::find($item['id']);
                    $qty = $item['quantity'];

                    if ($medicine && $medicine->stock >= $qty) {
                        $medicine->decrement('stock', $qty);
                        $medicineDescriptions[] = "{$medicine->name} (x{$qty})";
                    }
                }
            }

            $validated['medicines_given'] = implode(', ', $medicineDescriptions);

            /**
             * UPDATED: Age Format Sync
             * Calculates age in months for infants (0-11) to match your 
             * table display and filtering needs.
             */
            $birth = Carbon::parse($request->birthday);
            $now = Carbon::now();
            $diff = $birth->diff($now);

            if ($diff->y === 0) {
                $validated['age'] = $diff->m . ' Mon';
            } else {
                $validated['age'] = $diff->y . ' yrs';
            }

            ClinicRecord::create($validated);
        });

        return redirect()->route('record.index')->with('success', 'Record saved and stock updated!');
    }

    public function show(ClinicRecord $record): View
    {
        $history = ClinicRecord::where('patient_name', $record->patient_name)
            ->orderBy('consultation_date', 'desc')
            ->get();

        return view('record.show', [
            'record'  => $record,
            'history' => $history 
        ]);
    }

    public function dashboard()
    {
        $totalPatients = ClinicRecord::count();
        $lowStock = Medicine::where('stock', '<=', 10)->count();

        return view('dashboard', [
            'totalPatients' => $totalPatients,
            'lowStock' => $lowStock
        ]);
    }
}