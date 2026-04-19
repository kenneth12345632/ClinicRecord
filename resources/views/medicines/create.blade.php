@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4">
    <div class="mb-6">
        <a href="{{ route('medicines.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-3 text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Add New Medicine</h1>
        <p class="text-gray-500 text-sm mt-1">Input the brand and generic details to register the medicine.</p>
    </div>

    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8">
        <form action="{{ route('medicines.store') }}" method="POST" id="medicineForm">
            @csrf
            
            <input type="hidden" name="stock" value="0">
            <input type="hidden" name="expiration_date" value="{{ now()->addYear()->format('Y-m-d') }}">
            <input type="hidden" name="arrival_date" value="{{ now()->format('Y-m-d') }}">
            
            {{-- This hidden input will hold the combined name sent to the database --}}
            <input type="hidden" name="name" id="combined_name">

            <div class="space-y-5">
                {{-- Brand Name Input --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Brand Name</label>
                    <input type="text" id="brand_name" placeholder="e.g. Biogesic" required autofocus
                        class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                </div>

                {{-- Generic Name Input --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Generic Name</label>
                    <input type="text" id="generic_name" placeholder="e.g. Paracetamol tablet 500mg" required
                        class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                    <p class="mt-2 text-[10px] text-gray-400 italic">Example output: Brand (Generic Name)</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex items-center gap-3">
                <button type="submit" class="flex-1 py-3.5 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">
                    Save to Inventory
                </button>
                <a href="{{ route('medicines.index') }}" class="flex-1 py-3.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition text-center border border-gray-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('medicineForm').addEventListener('submit', function(e) {
        const brand = document.getElementById('brand_name').value.trim();
        const generic = document.getElementById('generic_name').value.trim();
        
        // Combines them into "Brand (Generic)" format before sending to Controller
        document.getElementById('combined_name').value = `${brand} (${generic})`;
    });
</script>
@endsection