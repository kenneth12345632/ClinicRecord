{{-- resources/views/record/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Add New Patient Record</h2>
        </div>

        <form action="{{ route('record.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            {{-- Patient Name --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Patient Name</label>
                <input type="text" name="patient_name" placeholder="Enter full name" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition bg-gray-50">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Date of Consultation --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Date of Consultation</label>
                    <input type="date" name="consultation_date" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition">
                </div>
                {{-- Birthday with JS Trigger --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Birthday</label>
                    <input type="date" name="birthday" id="birthday" onchange="calculateAge()" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition bg-gray-50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gender</label>
                    <select name="gender" required 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition bg-white">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                {{-- Auto-calculated Age Display --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Age</label>
                    <input type="text" id="age_display" placeholder="Auto-calculated" disabled
                        class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-gray-50 text-gray-900 outline-none cursor-not-allowed">
                </div>
            </div>

            {{-- Diagnosis --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Diagnosis</label>
                <textarea name="diagnosis" rows="3" placeholder="Describe symptoms/results"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none transition"></textarea>
            </div>

            {{-- Dynamic Medicine Picker --}}
            <div class="bg-gray-50 p-6 rounded-2xl space-y-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-bold text-gray-700">Medicines Given</label>
                    <button type="button" onclick="addMedicineRow()" 
                        class="text-xs bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 shadow-sm transition">
                        + ADD MEDICINE
                    </button>
                </div>

                <div id="medicine-container" class="space-y-3">
                    <div class="medicine-row flex items-center gap-3">
                        <div class="flex-grow">
                            <select name="medicines[0][id]" required 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm appearance-none bg-white">
                                <option value="" disabled selected>Click to select medicine...</option>
                                @foreach($allMedicines as $medicine)
                                    <option value="{{ $medicine->id }}">
                                        {{ $medicine->name }} (Available: {{ $medicine->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-28">
                            <input type="number" name="medicines[0][quantity]" min="1" placeholder="Qty" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 outline-none text-sm text-center">
                        </div>
                        <button type="button" onclick="this.closest('.medicine-row').remove()" 
                            class="text-red-400 hover:text-red-600 font-bold p-2 transition">
                            &times;
                        </button>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="pt-6 flex gap-4">
                <button type="submit" class="flex-grow bg-blue-600 text-white py-4 rounded-xl font-bold hover:bg-blue-700 shadow-lg transition">
                    Save Record
                </button>
                <a href="{{ route('record.index') }}" class="px-8 py-4 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Real-time Age Calculation Logic
    function calculateAge() {
        const birthdayInput = document.getElementById('birthday').value;
        const display = document.getElementById('age_display');
        
        if (!birthdayInput) return;

        const birthDate = new Date(birthdayInput);
        const today = new Date();
        
        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) {
            years--;
            months += 12;
        }

        // Display format matches your table records (e.g., 11 Mon or 25 yrs)
        if (years === 0) {
            display.value = `${months} Mon`;
        } else {
            display.value = `${years} yrs`;
        }
    }

    // 2. Dynamic Medicine Row Logic
    let rowCount = 1;
    function addMedicineRow() {
        const container = document.getElementById('medicine-container');
        const firstRow = container.querySelector('.medicine-row');
        
        if (firstRow) {
            const newRow = firstRow.cloneNode(true);
            
            // Assign unique indexes for the array submission
            newRow.querySelector('select').name = `medicines[${rowCount}][id]`;
            newRow.querySelector('input').name = `medicines[${rowCount}][quantity]`;
            
            // Clear cloned values
            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('input').value = '';
            
            container.appendChild(newRow);
            rowCount++;
        }
    }
</script>
@endsection