@extends('layouts.app')

@section('content')
@include('partials.medicine-expiry-picker-assets')
@php
    $p = $parsedMedicineName;
    $dosageVal = old('dosage_value', ($p['dosage_value'] ?? '') !== '' ? $p['dosage_value'] : (($medicine->dosage_value !== null && $medicine->dosage_value !== '') ? (string) $medicine->dosage_value : ''));
    $dosageUnitVal = old('dosage_unit', $p['dosage_unit'] ?? $medicine->dosage_unit ?? 'mg');
    $arrivalDefault = old('arrival_date', optional($medicine->arrival_date)->format('Y-m-d'));
    $expiryDefault = old('expiration_date', optional($medicine->expiration_date)->format('Y-m-d'));
@endphp
<div class="max-w-5xl mx-auto py-6 px-4 pb-14">
    <div class="mb-6">
        <a href="{{ route('medicines.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-3 text-sm font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit medicine batch</h1>
        <p class="text-gray-500 text-sm mt-1">Correct typos or stock details for this batch. Display name builds the same way as Add Medicine.</p>
        @unless($p['parsed'] ?? false)
            <p class="mt-2 text-sm text-amber-700 rounded-xl border border-amber-200 bg-amber-50 px-4 py-2">
                This batch’s stored label is not in the standard <strong>Brand (Generic) Dosage Unit Type</strong> format. Fields below are prefilled from what we could detect — adjust and save.
            </p>
        @endunless
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            @foreach ($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8 mb-10">
        <form action="{{ route('medicines.update', $medicine) }}" method="POST" id="medicineForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" id="combined_name">

            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Generic Name</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="generic_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="genericDropdownWrap">
                        <input type="text" id="generic_name" name="generic_name_virtual" placeholder="Search or type generic name..." autocomplete="off" required value="{{ old('generic_name_virtual', $p['generic'] ?? '') }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                        <div id="generic_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-64 overflow-y-auto z-30"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Brand Name</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="brand_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="brandDropdownWrap">
                        <input type="text" id="brand_name" name="brand_name_virtual" placeholder="Search or type brand name..." autocomplete="off" required value="{{ old('brand_name_virtual', $p['brand'] ?? '') }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                        <div id="brand_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-64 overflow-y-auto z-30"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</label>
                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-blue-600">
                            <input type="checkbox" id="type_add_new" class="rounded border-gray-300">
                            Add New
                        </label>
                    </div>
                    <div class="relative" id="medicineTypeDropdownWrap">
                        <input type="hidden" name="type" id="medicine_type" required value="{{ old('type', $p['type'] ?? $medicine->type ?? '') }}">
                        <input type="text" id="medicine_type_search" placeholder="Search or type medicine type..." autocomplete="off"
                            value="{{ old('type_search', $p['type'] ?? $medicine->type ?? '') }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition bg-white">
                        <div id="medicine_type_dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-xl shadow-lg max-h-56 overflow-y-auto z-30"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Dosage</label>
                    <div class="grid grid-cols-[1fr_120px] gap-3">
                        <input type="number" name="dosage_value" id="dosage_value" placeholder="e.g. 500" min="0.01" step="0.01" required value="{{ $dosageVal }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                        <select name="dosage_unit" id="dosage_unit" required class="w-full px-4 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition bg-white">
                            @foreach (['mcg', 'mg', 'g', 'ml'] as $u)
                                <option value="{{ $u }}" @selected(old('dosage_unit', $dosageUnitVal) === $u)>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-400 italic">Example output: Brand (Generic Name) 500mg Capsule</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Stock Number</label>
                        <input type="text" name="batch_number" placeholder="e.g. LOT-001" required value="{{ old('batch_number', $medicine->batch_number ?? '') }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Date Received</label>
                        <div class="relative w-full">
                            <input type="text" name="arrival_date" id="arrival_date" required autocomplete="off"
                                data-medicine-arrival
                                data-inventory-edit-dates="true"
                                data-default="{{ $arrivalDefault }}"
                                data-alt-class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-semibold text-gray-900 transition"
                                placeholder="dd/mm/yyyy"
                                class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-semibold text-gray-900 transition">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[10px] text-gray-400">Past dates allowed when correcting intake records.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Expiration Date</label>
                        <div class="relative w-full">
                            <input type="text" name="expiration_date" id="expiration_date" required autocomplete="off"
                                data-medicine-expiry
                                data-inventory-edit-dates="true"
                                data-default="{{ $expiryDefault }}"
                                data-alt-class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-semibold text-gray-900 transition"
                                placeholder="dd/mm/yyyy"
                                class="w-full px-5 py-3.5 pr-12 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-semibold text-gray-900 transition">
                            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                        </div>
                        <p class="mt-1.5 text-[10px] text-gray-400">Past dates allowed only for fixing data entry mistakes.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Quantity</label>
                        <input type="number" name="stock" min="0" required value="{{ old('stock', $medicine->stock) }}"
                            class="w-full px-5 py-3.5 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button type="submit" class="flex-1 py-3.5 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 shadow-md transition">
                    Save changes
                </button>
                <a href="{{ route('medicines.index') }}" class="flex-1 py-3.5 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition text-center border border-gray-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <div class="mb-8">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800">Activity for this batch</h2>
            <p class="text-gray-500 text-sm mt-1">Stock movements linked to medicine ID #{{ $medicine->id }}.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="space-y-4">
                @forelse($inventoryLogs as $log)
                    @php
                        $actionLabel = match($log->transaction_type) {
                            'stock_in' => 'Stock-In',
                            'stock_out' => 'Dispense',
                            default => 'Adjustment',
                        };
                        $actionClass = match($log->transaction_type) {
                            'stock_in' => 'bg-emerald-100 text-emerald-700',
                            'stock_out' => 'bg-blue-100 text-blue-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                        $consultationId = null;
                        if ($log->transaction_type === 'stock_out' && preg_match('/Dispensed for consultation #(\d+)/i', (string) $log->reference, $matches)) {
                            $consultationId = (int) $matches[1];
                        }
                        $patientName = $consultationId ? ($consultationNames[$consultationId] ?? null) : null;
                    @endphp
                    <div class="rounded-xl border border-gray-100 p-4 bg-gray-50/40">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">User</p>
                                <p class="font-semibold text-slate-800">{{ $log->user?->full_name ?? 'System' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Medicine Name</p>
                                <p class="font-semibold text-slate-800">{{ $log->medicine?->name ?? $medicine->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Quantity</p>
                                <p class="font-semibold text-slate-800">{{ abs((int) $log->quantity) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Date/Time</p>
                                <p class="font-semibold text-slate-800 inventory-log-time"
                                   data-timestamp="{{ $log->created_at->toIso8601String() }}">
                                    {{ $log->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Action Type</p>
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $actionClass }}">{{ $actionLabel }}</span>
                                @if($patientName)
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-2">Patient</p>
                                    <p class="font-semibold text-slate-800">{{ $patientName }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center border border-dashed border-gray-200 rounded-xl">
                        <p class="text-gray-400 font-semibold">No inventory logs for this batch yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('medicines.partials.medicine-batch-form-scripts')
@endsection

@push('scripts')
    @include('partials.medicine-expiry-picker-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nodes = document.querySelectorAll('.inventory-log-time');
            if (!nodes.length) return;

            var formatter = new Intl.DateTimeFormat(undefined, {
                month: 'short',
                day: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            });

            function renderTimes() {
                nodes.forEach(function (node) {
                    var iso = node.getAttribute('data-timestamp');
                    if (!iso) return;
                    var date = new Date(iso);
                    if (Number.isNaN(date.getTime())) return;
                    node.textContent = formatter.format(date);
                });
            }

            renderTimes();
            setInterval(renderTimes, 1000);
        });
    </script>
@endpush
