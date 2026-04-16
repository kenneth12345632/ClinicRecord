<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index() 
    {
        // Keep index sorted by expiration for visibility (FEFO)
        $medicines = Medicine::orderBy('expiration_date', 'asc')->get();
        return view('medicines.index', ['medicines' => $medicines]);
    }

    public function create()
    {
        return view('medicines.create'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        Medicine::create($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'New batch added successfully!');
    }

    /**
     * Deduct stock from the latest batch first (LIFO)
     * You can call this method from your ClinicRecordController 
     * when a medicine is prescribed/given.
     */
    public function autoDeduct(Request $request)
    {
        $medicineName = $request->medicine_name;
        $amountToDeduct = $request->quantity;

        // 1. Get all batches of this medicine, newest first (LIFO)
        $batches = Medicine::where('name', $medicineName)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc') // This ensures newest deducts first
            ->get();

        $remainingToDeduct = $amountToDeduct;

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) break;

            if ($batch->stock >= $remainingToDeduct) {
                // Current batch has enough for the whole request
                $batch->decrement('stock', $remainingToDeduct);
                $remainingToDeduct = 0;
            } else {
                // Batch doesn't have enough; empty this batch and move to next
                $remainingToDeduct -= $batch->stock;
                $batch->update(['stock' => 0]);
            }
        }

        if ($remainingToDeduct > 0) {
            return back()->with('error', "Not enough total stock. Missing: $remainingToDeduct units.");
        }

        return back()->with('success', 'Stock deducted starting from the latest batch.');
    }

    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', ['medicine' => $medicine]);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'expiration_date' => 'required|date',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')
            ->with('success', 'Medicine removed from inventory.');
    }
}