@extends('layouts.app')

@section('content')
@include('partials.medicine-expiry-picker-assets')
@php
    $expirationOld = old('expiration_date');
    if ($expirationOld && \Illuminate\Support\Carbon::parse($expirationOld)->startOfDay()->lt(now()->startOfDay())) {
        $expirationOld = '';
    }
    $arrivalOld = old('arrival_date');
    if (!$arrivalOld || \Illuminate\Support\Carbon::parse($arrivalOld)->startOfDay()->lt(now()->startOfDay())) {
        $arrivalOld = now()->format('Y-m-d');
    }
@endphp
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
            
            {{-- This hidden input will hold the combined name sent to the database --}}
            <input type="hidden" name="name" id="combined_name">

            <div class="space-y-5">
                {{-- Generic Name Search Dropdown --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Generic Name</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="generic_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="genericDropdownWrap">
                        <input type="text" id="generic_name" placeholder="Search or type generic name..." required autocomplete="off"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition">
                        <div id="generic_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-64 overflow-y-auto z-30"></div>
                    </div>
                </div>

                {{-- Brand Name Input --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Brand Name</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="brand_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="brandDropdownWrap">
                        <input type="text" id="brand_name" placeholder="Search or type brand name..." required autofocus autocomplete="off"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition">
                        <div id="brand_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-64 overflow-y-auto z-30"></div>
                    </div>
                </div>

                {{-- Type --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="type_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="medicineTypeDropdownWrap">
                        <input type="hidden" name="type" id="medicine_type" required>
                        <input type="text" id="medicine_type_search" placeholder="Search or type medicine type..." autocomplete="off"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition bg-white">
                        <div id="medicine_type_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-56 overflow-y-auto z-30"></div>
                    </div>
                </div>

                {{-- Dosage (Value + Unit) --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Dosage</label>
                    <div class="grid grid-cols-[1fr_120px] gap-3">
                        <input type="number" name="dosage_value" id="dosage_value" placeholder="e.g. 500" min="0.01" step="0.01" required
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition">
                        <select name="dosage_unit" id="dosage_unit" required
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition bg-white">
                            <option value="mcg">mcg</option>
                            <option value="mg" selected>mg</option>
                            <option value="g">g</option>
                            <option value="ml">ml</option>
                        </select>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400 italic">Example output: Brand (Generic Name) 500mg Capsule</p>
                </div>

                {{-- Inventory Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Stock Number</label>
                        <input type="text" name="batch_number" placeholder="e.g. LOT-001" value="{{ old('batch_number') }}" required
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Date Received</label>
                        <div class="relative w-full">
                            <input type="text" name="arrival_date" id="arrival_date" required autocomplete="off"
                                data-medicine-arrival
                                data-default="{{ $arrivalOld }}"
                                data-alt-class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-semibold text-gray-900 transition"
                                placeholder="dd/mm/yyyy"
                                class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-semibold text-gray-900 transition">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[10px] text-gray-400">Only today and future dates can be selected.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Expiration Date</label>
                        <div class="relative w-full">
                            <input type="text" name="expiration_date" id="expiration_date" required autocomplete="off"
                                data-medicine-expiry
                                data-default="{{ $expirationOld }}"
                                data-alt-class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-semibold text-gray-900 transition"
                                placeholder="dd/mm/yyyy"
                                class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-semibold text-gray-900 transition">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[10px] text-gray-400">Only today and future dates can be selected.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Quantity</label>
                        <input type="number" name="stock" min="1" value="{{ old('stock', 1) }}" required
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-green-400 focus:ring-4 focus:ring-green-50 outline-none text-base font-medium transition">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex items-center gap-3">
                <button type="submit" class="flex-1 py-3.5 bg-green-600 text-white text-sm font-black rounded-xl hover:bg-green-700 shadow-md transition transform hover:-translate-y-0.5">
                    Save to Inventory
                </button>
                <a href="{{ route('medicines.index') }}" class="flex-1 py-3.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition text-center border border-gray-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@include('medicines.partials.medicine-batch-form-scripts')
@endsection

@push('scripts')
    @include('partials.medicine-expiry-picker-scripts')
@endpush