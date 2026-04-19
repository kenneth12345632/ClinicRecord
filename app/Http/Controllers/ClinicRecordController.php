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
    public function index(Request $request)
    {
        $search = $request->get('search');

        $records = ClinicRecord::whereIn('id', function ($query) use ($search) {
            $query->select(DB::raw('MAX(id)'))
                ->from('clinic_records')
                ->groupBy('first_name', 'middle_name', 'last_name', 'birthday');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('middle_name', 'like', "%{$search}%");
                });
            }
        })
        ->orderBy('consultation_date', 'desc')
        ->get();

        return view('record.index', [
            'records' => $records,
            'allMedicines' => Medicine::where('stock', '>', 0)->get()
        ]);
    }

    public function create()
    {
        return view('record.create', [
            'allMedicines' => Medicine::where('stock', '>', 0)->get()
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'last_name'         => 'required|string|max:255',
            'consultation_date' => 'required|date',
            'birthday'          => 'required|date',
            'gender'            => 'required|string',
            'civil_status'      => 'required|string',
            'contact_number'    => 'nullable|string',
            'address_purok'     => 'required|string',
            'diagnosis'         => 'nullable|string',
            'medicines'         => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // 1. Calculate age for storage
            $birth = Carbon::parse($validated['birthday']);
            $diff = $birth->diff(Carbon::now());
            $validated['age'] = ($diff->y === 0) ? $diff->m . ' Mon' : $diff->y . ' yrs';

            // 2. Create the record
            $record = ClinicRecord::create($validated);

            // 3. Handle Medicines & Stock
            if ($request->has('medicines')) {
                foreach ($request->medicines as $item) {
                    if (!empty($item['id'])) {
                        $medicine = Medicine::find($item['id']);
                        if ($medicine && $medicine->stock >= $item['quantity']) {
                            $medicine->decrement('stock', $item['quantity']);
                            // Attach to pivot table
                            $record->medicines()->attach($item['id'], ['quantity' => $item['quantity']]);
                        }
                    }
                }
            }
        });

        return redirect()->route('record.index')->with('success', 'Record saved successfully!');
    }

    public function show($id)
    {
        $record = ClinicRecord::with('medicines')->findOrFail($id);

        // history filters by Name and Birthday
        $history = ClinicRecord::where('first_name', $record->first_name)
            ->where('last_name', $record->last_name)
            ->where('birthday', $record->birthday)
            ->orderBy('consultation_date', 'desc')
            ->get();

        return view('record.show', [
            'record'  => $record,
            'history' => $history 
        ]);
    }

    public function edit($id)
    {
        $record = ClinicRecord::with('medicines')->find($id);
        
        if (!$record) {
            return redirect()->route('record.index')->with('error', 'Record not found.');
        }

        return view('record.edit', [
            'record' => $record,
            'allMedicines' => Medicine::where('stock', '>', 0)->get()
        ]);
    }

 public function update(Request $request, $id): RedirectResponse
{
    $record = ClinicRecord::findOrFail($id);
    
    // 1. Validate all fields used for patient identification
    $validated = $request->validate([
        'first_name'        => 'required|string|max:255',
        'middle_name'       => 'nullable|string|max:255',
        'last_name'         => 'required|string|max:255',
        'birthday'          => 'required|date',
        'consultation_date' => 'required|date',
        'gender'            => 'required|string',
        'civil_status'      => 'required|string',
        'contact_number'    => 'nullable|string',
        'address_purok'     => 'required|string',
        'diagnosis'         => 'required|string',
        'medicines'         => 'nullable|array',
    ]);

    DB::transaction(function () use ($request, $record, &$validated) {
        // 2. Recalculate age in case birthday changed
        $birth = Carbon::parse($validated['birthday']);
        $diff = $birth->diff(Carbon::now());
        $validated['age'] = ($diff->y === 0) ? $diff->m . ' Mon' : $diff->y . ' yrs';

        // 3. Handle Medicine Sync and Stock Restoration
        $medicineDescriptions = [];
        if ($request->has('medicines')) {
            $syncData = [];
            foreach ($request->medicines as $item) {
                if (!empty($item['id'])) {
                    $medicine = Medicine::find($item['id']);
                    $qty = $item['quantity'];

                    if ($medicine) {
                        $syncData[$item['id']] = ['quantity' => $qty];
                        $medicineDescriptions[] = "{$medicine->name} (x{$qty})";
                        
                        // Note: To properly manage stock on update, you would need to 
                        // compare old vs new quantities. For now, we update the display string.
                    }
                }
            }
            // Sync pivot table for the 'medicines' relationship
            $record->medicines()->sync($syncData);
        }

        // 4. Update the text string for the "Medicines Given" box
        $validated['medicines_given'] = implode(', ', $medicineDescriptions);

        $record->update($validated);
    });

    return redirect()->route('record.show', $id)->with('success', 'Record updated successfully!');
}
    public function destroy($id)
    {
        $record = ClinicRecord::findOrFail($id);
        $record->delete();
        return redirect()->route('record.index')->with('success', 'Record deleted successfully.');
    }

    public function dashboard()
    {
        return view('dashboard', [
            'totalPatients' => ClinicRecord::count(),
            'lowStock' => Medicine::where('stock', '<=', 10)->count()
        ]);
    }
   public function print($id)
{
    // 1. Get the main record with its medicines
    $record = ClinicRecord::with('medicines')->findOrFail($id);

    // 2. Fetch history safely
    // We use where('id', '!=', $id) to exclude the current visit from the 'Past' list
    $history = ClinicRecord::where('first_name', $record->first_name)
        ->where('last_name', $record->last_name)
        ->where('id', '!=', $id) 
        ->orderBy('consultation_date', 'desc')
        ->get();

    // 3. Explicitly pass them. If you prefer not using compact(), do this:
    return view('record.print', [
        'record' => $record,
        'history' => $history
    ]);
}
}