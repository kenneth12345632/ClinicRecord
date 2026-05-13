@extends('layouts.app')

@section('content')
@php
    $role = auth()->user()->role ?? 'bhw';
    $isNurse = $role === 'nurse';
    $canEncodeFindings = $isNurse;
    $birthdayOld = old('birthday');
@endphp
@include('partials.birthday-material-picker-assets')
<style>
    .itr-form .itr-card {
        border: 1px solid #cbd5e1;
        border-radius: 0.95rem;
        background: #fff;
    }
    .itr-form .itr-label {
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #334155;
    }
    .itr-form .itr-input,
    .itr-form textarea,
    .itr-form select {
        min-height: 2.85rem;
        font-size: 0.96rem;
    }
    .itr-form label {
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        color: #374151 !important;
        letter-spacing: 0.01em;
    }
    .itr-form input,
    .itr-form select,
    .itr-form textarea {
        border: 1.5px solid #64748b !important;
        background-color: #ffffff !important;
        color: #111827 !important;
        font-size: 0.95rem !important;
    }
    .itr-form .select2-container--default .select2-selection--single {
        border: 1.5px solid #64748b !important;
        border-radius: 0.5rem !important;
        background: #fff !important;
        height: 2.85rem !important;
    }
    .itr-form .select2-container--default.select2-container--open .select2-selection--single {
        background: #fff !important;
        border-color: #16a34a !important;
    }
    .itr-form .select2-container--default .select2-selection__rendered {
        color: #111827 !important;
        font-weight: 500 !important;
        font-size: 0.95rem !important;
    }
    .select2-results__option[aria-disabled="true"] {
        display: none !important;
    }
    .itr-form textarea {
        min-height: 4.6rem;
        line-height: 1.35;
    }
    .itr-form input::placeholder,
    .itr-form textarea::placeholder {
        color: #6b7280 !important;
        opacity: 1;
    }
    .itr-form input[readonly] {
        border-color: #cbd5e1 !important;
        background-color: #f8fafc !important;
    }
    .itr-form .bg-gray-50,
    .itr-form .bg-gray-50\/40,
    .itr-form .bg-gray-50\/50 {
        background-color: #f8fafc !important;
    }
    .itr-form .text-gray-400 {
        color: #6b7280 !important;
    }
    .itr-form .text-gray-500 {
        color: #374151 !important;
    }
    .itr-form .itr-consult-date label {
        display: block;
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        color: #64748b !important;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.1rem;
    }
    .itr-form .itr-consult-date input {
        border: none !important;
        background: transparent !important;
        min-height: 0 !important;
        padding: 0 !important;
        font-size: 1.45rem !important;
        line-height: 1.1;
        font-weight: 700 !important;
        color: #000000 !important;
        text-align: right;
        box-shadow: none !important;
    }

    /* Dark mode for ITR form */
    .dark .itr-form .itr-card {
        border: 1px solid #22543d !important;
        background: #0f1d1a !important;
    }
    .dark .itr-form .itr-label {
        color: #22c55e !important;
    }
    .dark .itr-form label {
        color: #cbd5e1 !important;
    }
    .dark .itr-form input,
    .dark .itr-form select,
    .dark .itr-form textarea {
        border: 1.5px solid #334155 !important;
        background-color: #111827 !important;
        color: #e2e8f0 !important;
        border-radius: 0.65rem !important;
    }
    .dark .itr-form input::placeholder,
    .dark .itr-form textarea::placeholder {
        color: #f1f5f9 !important;
        opacity: 1;
    }
    .dark .itr-form input[readonly] {
        border-color: #1f3329 !important;
        background-color: #0d1912 !important;
        color: #64748b !important;
    }
    .dark .itr-form .bg-gray-50,
    .dark .itr-form .bg-gray-50\/40,
    .dark .itr-form .bg-gray-50\/50 {
        background-color: #0b1120 !important;
    }
    .dark .itr-form .text-gray-400 {
        color: #64748b !important;
    }
    .dark .itr-form .text-gray-500 {
        color: #94a3b8 !important;
    }
    .dark .itr-form h3 {
        color: #22c55e !important;
        border-color: #1f3329 !important;
    }
    .dark .itr-form .itr-consult-date label {
        color: #94a3b8 !important;
    }
    .dark .itr-form .itr-consult-date input {
        color: #f1f5f9 !important;
        background: transparent !important;
        border: none !important;
    }
    .dark .itr-form .select2-container--default .select2-selection--single {
        border: 1.5px solid #334155 !important;
        background: #111827 !important;
        border-radius: 0.65rem !important;
    }
    .dark .itr-form .select2-container--default .select2-selection__rendered {
        color: #e2e8f0 !important;
    }
    .dark .itr-form .select2-container--default .select2-selection__placeholder {
        color: #f1f5f9 !important;
    }
    .dark .itr-form .select2-container--default.select2-container--open .select2-selection--single {
        background: #111827 !important;
        border-color: #22c55e !important;
    }
    .dark .itr-form input:focus,
    .dark .itr-form select:focus,
    .dark .itr-form textarea:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.2) !important;
    }
    /* Outer form wrapper */
    .dark .bg-white.rounded-3xl {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
    }
    /* Header bar */
    .dark .itr-form .bg-slate-50\/70,
    .dark .bg-slate-50\/70 {
        background-color: #111827 !important;
        border-color: #1e293b !important;
    }
    /* Form body area */
    .dark .itr-form .bg-slate-50\/40,
    .dark .bg-slate-50\/40 {
        background-color: #0b1120 !important;
    }
    /* Header title */
    .dark .itr-form h2,
    .dark .bg-slate-50\/70 h2 {
        color: #f1f5f9 !important;
    }
    /* "Add New Consultation" subtitle */
    .dark .bg-slate-50\/70 .text-slate-500,
    .dark .bg-slate-50\/70 p {
        color: #22c55e !important;
    }
    /* Section icon badges */
    .dark .itr-form .itr-card .w-6.h-6,
    .dark .itr-form .itr-card .w-5.h-5 {
        color: #22c55e !important;
    }
</style>
{{-- Hidden data provider for JavaScript --}}
<div id="medicine-data" data-list='@json($allMedicines ?? [])' style="display: none;"></div>
{{-- Select2 loaded globally --}}

<div class="max-w-[1400px] mx-auto py-6 px-4 lg:px-6">
    {{-- Error Alerts --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-xl shadow-sm">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-bold">Please correct the following errors:</p>
                    <ul class="text-xs text-red-600 list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <form class="itr-form" action="{{ route('record.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Header Section --}}
            <div class="px-6 py-5 lg:px-8 border-b border-slate-200 bg-slate-50/70 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-800 uppercase leading-tight">Individual Treatment Record</h2>
                        <p class="text-sm text-slate-500 mt-1">Add New Consultation</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 lg:gap-4 lg:ms-auto">
                    <div class="text-right itr-consult-date">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Consultation Date</label>
                        <input type="text" name="consultation_date" id="consultation_date" required autocomplete="off" placeholder="dd/mm/yyyy"
                            data-material-calendar data-fp-compact
                            data-default="{{ old('consultation_date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                            data-alt-class="border-none bg-transparent font-bold text-black text-xl p-0 focus:ring-0 text-right outline-none min-w-[9.5rem]"
                            class="border-none bg-transparent font-bold text-black text-xl p-0 focus:ring-0 text-right outline-none min-w-[9.5rem] cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="p-6 lg:p-8 bg-slate-50/40">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-7">
                    
                    {{-- LEFT COLUMN: PATIENT DATA --}}
                    <div class="lg:col-span-5 space-y-6 itr-card p-5 lg:p-6">
                        <h3 class="font-bold text-green-600 border-b pb-2 text-sm uppercase tracking-wider">Patient Information</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Full Name</label>
                                <div class="flex gap-2">
                                    <input type="text" name="last_name" placeholder="Last" value="{{ old('last_name') }}" required 
                                        class="w-1/3 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none uppercase">
                                    <input type="text" name="first_name" placeholder="First" value="{{ old('first_name') }}" required 
                                        class="w-1/3 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none uppercase">
                                    <input type="text" name="middle_name" placeholder="M.I." value="{{ old('middle_name') }}"
                                        class="w-1/4 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none uppercase">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Birthday</label>
                                <div class="relative w-full">
                                    <input type="text" name="birthday" id="birthday" autocomplete="off" required placeholder="dd/mm/yyyy"
                                        data-fp-compact
                                        data-default="{{ $birthdayOld }}"
                                        data-alt-class="w-full px-3 py-2 pr-10 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none font-semibold text-gray-900"
                                        class="w-full px-3 py-2 pr-10 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none font-semibold text-gray-900">
                                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Age</label>
                                <input type="text" id="age_display" readonly placeholder="Auto"
                                    class="w-full px-3 py-2 rounded-lg bg-gray-50 border-gray-100 text-sm text-gray-500 outline-none cursor-default">
                                <input type="hidden" name="age" id="age_hidden" value="{{ old('age') }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Gender</label>
                                <select name="gender" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none">
                                    <option value="" disabled hidden {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Civil Status</label>
                                <select name="civil_status" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm outline-none">
                                    <option value="" disabled hidden {{ old('civil_status') ? '' : 'selected' }}>Select civil status</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Cellphone Number</label>
                                <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none">
                            </div>

                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Address / Purok</label>
                                <select id="address_purok_select" name="address_purok" required
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-green-400 outline-none uppercase">
                                    <option value="" disabled hidden {{ old('address_purok') ? '' : 'selected' }}>Select address</option>
                                    @foreach (['BAYANIHAN', 'TABUNON', 'GABI', 'BALAANONG TUBIG', 'BALAHAN', 'RIBOMA', 'PAG-ASA', 'PUROK-ANO'] as $address)
                                        <option value="{{ $address }}" {{ old('address_purok') === $address ? 'selected' : '' }}>{{ $address }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Laboratory Upload (Optional) --}}
                        <div class="pt-2 relative opacity-60" x-data="labUploader()">
                            <div class="absolute inset-0 z-10 cursor-not-allowed" onclick="showDoctorOnlyNotice('Laboratory Upload')"></div>
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Laboratory Upload (Optional)</label>
                                <button type="button" @click="clearAll()" x-show="files.length > 0"
                                    class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 transition"
                                    style="display:none;">
                                    Clear
                                </button>
                            </div>

                            <input
                                x-ref="input"
                                type="file"
                                name="laboratory_images[]"
                                multiple
                                accept=".jpg,.jpeg,.png,.webp"
                                class="hidden"
                                @change="onPick($event)"
                            >

                            <div
                                class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-6 text-center cursor-pointer hover:border-green-300 hover:bg-green-50/30 transition"
                                @click="$refs.input.click()"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="onDrop($event)"
                                :class="isDragging ? 'border-green-400 bg-green-50/40' : ''"
                            >
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-10 h-10 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-600">Drag files to upload</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">or</p>
                                    <button type="button"
                                        class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-green-600 font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition">
                                        Browse Files
                                    </button>
                                    <p class="text-[10px] text-gray-400 mt-1">
                                        Max files: <span class="font-bold">5</span> • Max size: <span class="font-bold">5MB</span> each
                                    </p>
                                    <p class="text-[9px] text-gray-300 uppercase tracking-widest">JPG, PNG, WEBP only</p>
                                </div>
                            </div>

                            <template x-if="errors.length > 0">
                                <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-xl">
                                    <template x-for="(msg, idx) in errors" :key="idx">
                                        <p class="text-[10px] font-bold text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>

                            <template x-if="files.length > 0">
                                <div class="mt-4 space-y-2">
                                    <template x-for="(f, idx) in files" :key="f.key">
                                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                            <img :src="f.preview" class="w-12 h-12 rounded-xl object-cover border border-gray-100" alt="Preview" />
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-black text-gray-700 truncate" x-text="f.name"></p>
                                                <p class="text-[10px] text-gray-400" x-text="formatBytes(f.size)"></p>
                                            </div>
                                            <button type="button" class="w-9 h-9 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-red-500 hover:border-red-200 transition"
                                                @click="removeAt(idx)" title="Remove">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: S.O.A.P. --}}
                    <div class="lg:col-span-7 space-y-5">
                        
                        {{-- V - Vital Signs --}}
                        <div class="itr-card p-4 lg:p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-green-600 text-white w-6 h-6 flex items-center justify-center rounded font-bold text-xs">V</span>
                                <label class="text-xs font-bold text-gray-700 uppercase">Vitals</label>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">T</span><input type="text" name="temp" value="{{ old('temp') }}" placeholder="°C" class="w-full pl-6 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">BP</span><input type="text" name="bp" value="{{ old('bp') }}" placeholder="0/0" class="w-full pl-8 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">PR</span><input type="text" name="pr" value="{{ old('pr') }}" placeholder="bpm" class="w-full pl-8 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">RR</span><input type="text" name="rr" value="{{ old('rr') }}" placeholder="cpm" class="w-full pl-8 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">WT</span><input type="number" step="0.1" id="weight" name="weight" value="{{ old('weight') }}" oninput="calculateBMI()" placeholder="kg" class="w-full pl-8 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative"><span class="absolute left-2 top-2 text-[10px] font-bold text-gray-400">HT</span><input type="number" step="0.1" id="height" name="height" value="{{ old('height') }}" oninput="calculateBMI()" placeholder="cm" class="w-full pl-8 pr-2 py-2 border rounded-lg text-xs outline-none"></div>
                                <div class="relative col-span-2">
                                    <span class="absolute left-2 top-2 text-[10px] font-bold text-green-500">BMI</span>
                                    <input type="text" id="bmi_result" name="bmi" value="{{ old('bmi') }}" readonly placeholder="Auto" class="w-full pl-10 pr-2 py-2 border border-green-100 bg-green-50/50 rounded-lg text-xs font-bold text-green-700">
                                </div>
                            </div>
                        </div>

                        {{-- S - Subjective --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="itr-card p-4 lg:p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-green-600 text-white w-6 h-6 flex items-center justify-center rounded font-bold text-xs">S</span>
                                    <label class="text-xs font-bold text-gray-700 uppercase">Subjective Findings</label>
                                </div>
                                <textarea
                                    name="subjective"
                                    rows="4"
                                    placeholder="{{ $canEncodeFindings ? "Patient's complaints..." : 'Only nurse can fill this out.' }}"
                                    {{ $canEncodeFindings ? '' : 'readonly onclick=showNurseOnlyNotice(\'Subjective Findings\')' }}
                                    class="w-full px-4 py-3 rounded-xl border text-sm outline-none {{ $canEncodeFindings ? 'border-gray-200 focus:ring-2 focus:ring-green-100' : 'border-gray-100 bg-gray-50 text-gray-500 cursor-not-allowed' }}"
                                >{{ old('subjective') }}</textarea>
                            </div>

                            <div class="itr-card p-4 lg:p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="bg-green-600 text-white w-6 h-6 flex items-center justify-center rounded font-bold text-xs">O</span>
                                    <label class="text-xs font-bold text-gray-700 uppercase">Objective Findings</label>
                                </div>
                                <textarea
                                    name="objective"
                                    rows="4"
                                    placeholder="{{ $canEncodeFindings ? 'Physical examination details...' : 'Only nurse can fill this out.' }}"
                                    {{ $canEncodeFindings ? '' : 'readonly onclick=showNurseOnlyNotice(\'Objective Findings\')' }}
                                    class="w-full px-4 py-3 rounded-xl border text-sm outline-none {{ $canEncodeFindings ? 'border-gray-200 focus:ring-2 focus:ring-green-100' : 'border-gray-100 bg-gray-50 text-gray-500 cursor-not-allowed' }}"
                                >{{ old('objective') }}</textarea>
                            </div>
                        </div>

                        {{-- A - Assessment --}}
                        <div class="itr-card p-4 lg:p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-green-600 text-white w-6 h-6 flex items-center justify-center rounded font-bold text-xs">A</span>
                                <label class="text-xs font-bold text-gray-700 uppercase">Assessment / Diagnosis</label>
                            </div>
                            <textarea rows="2" readonly onclick="showDoctorOnlyNotice('Assessment / Diagnosis')" placeholder="Only doctor can fill this out"
                                class="w-full px-4 py-3 rounded-xl border-2 border-green-50 bg-gray-50 text-sm text-gray-500 outline-none cursor-not-allowed">Only doctor can fill this out.</textarea>
                        </div>

                        {{-- P - Plan / Medicines --}}
                        <div class="itr-card p-4 lg:p-5">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="bg-green-600 text-white w-6 h-6 flex items-center justify-center rounded font-bold text-xs">P</span>
                                    <label class="text-xs font-bold text-gray-700 uppercase">Plan / Medicines</label>
                                </div>
                                <button type="button" id="add-medicine-btn" onclick="showDoctorOnlyNotice('Plan / Medicines')"
                                    class="text-gray-400 text-[10px] font-bold tracking-widest cursor-not-allowed">+ ADD ITEM</button>
                            </div>
                            <div id="medicine-rows-container" class="space-y-3">
                                <div class="p-3 rounded-xl border border-dashed border-gray-200 bg-gray-50 text-xs font-semibold text-gray-500">
                                    Only doctor can fill Plan / Medicines.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-1">
                            <a href="{{ route('record.index') }}" class="px-6 py-3 bg-white border border-slate-300 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition">Cancel</a>
                            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 shadow-sm transition">Save Patient Record</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Select2 JS loaded globally --}}

<script>
    function labUploader() {
        const MAX_FILES = 5;
        const MAX_BYTES = 5 * 1024 * 1024; // 5MB
        const ALLOWED = ['image/jpeg', 'image/png', 'image/webp'];

        function fileKey(file) {
            return `${file.name}-${file.size}-${file.lastModified}`;
        }

        return {
            isDragging: false,
            files: [],
            errors: [],

            formatBytes(bytes) {
                if (!bytes && bytes !== 0) return '';
                const units = ['B', 'KB', 'MB', 'GB'];
                let i = 0;
                let value = bytes;
                while (value >= 1024 && i < units.length - 1) {
                    value /= 1024;
                    i++;
                }
                return `${value.toFixed(i === 0 ? 0 : 1)} ${units[i]}`;
            },

            validateFile(file) {
                if (!ALLOWED.includes(file.type)) {
                    return 'Only JPG, PNG, or WEBP images are allowed.';
                }
                if (file.size > MAX_BYTES) {
                    return 'A file exceeds the 5MB limit.';
                }
                return null;
            },

            addFiles(fileList) {
                this.errors = [];
                const incoming = Array.from(fileList || []);
                if (incoming.length === 0) return;

                for (const file of incoming) {
                    if (this.files.length >= MAX_FILES) {
                        this.errors.push(`Only up to ${MAX_FILES} files are allowed.`);
                        break;
                    }

                    const key = fileKey(file);
                    if (this.files.some(x => x.key === key)) continue;

                    const err = this.validateFile(file);
                    if (err) {
                        this.errors.push(`${file.name}: ${err}`);
                        continue;
                    }

                    const preview = URL.createObjectURL(file);
                    this.files.push({
                        key,
                        file,
                        name: file.name,
                        size: file.size,
                        preview,
                    });
                }

                this.syncToInput();
            },

            syncToInput() {
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f.file));
                this.$refs.input.files = dt.files;
            },

            onPick(e) {
                this.addFiles(e.target.files);
            },

            onDrop(e) {
                this.isDragging = false;
                this.addFiles(e.dataTransfer.files);
            },

            removeAt(idx) {
                const removed = this.files.splice(idx, 1);
                if (removed?.[0]?.preview) URL.revokeObjectURL(removed[0].preview);
                this.syncToInput();
            },

            clearAll() {
                this.files.forEach(f => f.preview && URL.revokeObjectURL(f.preview));
                this.files = [];
                this.errors = [];
                this.syncToInput();
            },
        };
    }

    function calculateAge() {
        const bday = document.getElementById('birthday').value;
        const display = document.getElementById('age_display');
        const hidden = document.getElementById('age_hidden');
        if (!bday) return;

        const birthDate = new Date(bday);
        const today = new Date();
        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();
        
        if (months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) { 
            years--; 
            months += 12; 
        }
        
        const ageString = (years <= 0) ? `${months} ${months === 1 ? 'month' : 'months'}` : `${years} ${years === 1 ? 'year' : 'years'}`;
        display.value = ageString;
        hidden.value = ageString;
    }

    window.calculateAge = calculateAge;

    function calculateBMI() {
        const w = parseFloat(document.getElementById('weight').value);
        const h = parseFloat(document.getElementById('height').value);
        const display = document.getElementById('bmi_result');
        if (w > 0 && h > 0) {
            const m = h / 100;
            const bmi = w / (m * m);
            display.value = bmi.toFixed(1);
        } else { 
            display.value = ""; 
        }
    }

    function showDoctorOnlyNotice(sectionName) {
        const existing = document.getElementById('doctor-only-alert');
        if (existing) existing.remove();

        const alert = document.createElement('div');
        alert.id = 'doctor-only-alert';
        alert.className = 'fixed top-5 right-5 z-[9999] bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold';
        alert.textContent = `${sectionName} can only be filled out by a Doctor.`;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 2200);
    }

    function showNurseOnlyNotice(sectionName) {
        const existing = document.getElementById('nurse-only-alert');
        if (existing) existing.remove();

        const alert = document.createElement('div');
        alert.id = 'nurse-only-alert';
        alert.className = 'fixed top-5 right-5 z-[9999] bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold';
        alert.textContent = `${sectionName} can only be filled out by a Nurse.`;
        document.body.appendChild(alert);

        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 2200);
    }

    function setupNativeRequiredValidation(form) {
        const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
        requiredFields.forEach((field) => {
            field.addEventListener('invalid', () => {
                if (field.validity.valueMissing) {
                    const label = field.closest('div')?.querySelector('label')?.textContent?.trim() || 'this field';
                    field.setCustomValidity(`Please fill out ${label}.`);
                } else {
                    field.setCustomValidity('');
                }
            });
            field.addEventListener('input', () => field.setCustomValidity(''));
            field.addEventListener('change', () => field.setCustomValidity(''));
        });

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                form.querySelector(':invalid')?.focus();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form.itr-form');
        if (form) {
            setupNativeRequiredValidation(form);
        }

        // Trigger initial calculations if values exist (e.g., after validation error)
        if(document.getElementById('birthday').value) calculateAge();
        if(document.getElementById('weight').value && document.getElementById('height').value) calculateBMI();

        const container = document.getElementById('medicine-rows-container');
        const addBtn = document.getElementById('add-medicine-btn');
        const allMedicines = JSON.parse(document.getElementById('medicine-data').dataset.list || '[]');

        $('#address_purok_select').select2({
            width: '100%',
            placeholder: 'Select address',
            allowClear: false
        });

        function createMedicineRow() {
            const div = document.createElement('div');
            div.className = "grid grid-cols-1 md:grid-cols-[minmax(0,1fr)_120px_auto] gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 medicine-row";
            
            let options = '<option value="" disabled selected>Select Medicine</option>';
            allMedicines.forEach(med => { 
                options += `<option value="${med.id}">${med.name} (Stock: ${med.stock})</option>`; 
            });

            div.innerHTML = `
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Medicine</label>
                    <select name="medicines[${rowIndex}][id]" class="med-select" required>${options}</select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Quantity</label>
                    <input type="number" name="medicines[${rowIndex}][quantity]" required min="1" value="1" placeholder="Qty" 
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg h-[42px] text-sm outline-none">
                </div>
                <button type="button" class="mb-1 self-end justify-self-end text-gray-300 hover:text-red-500 remove-row" title="Remove medicine row">✕</button>
            `;

            container.appendChild(div);
            $(div).find('.med-select').select2({ width: '100%' });
        }

        $(document).on('click', '.remove-row', function() {
            $(this).closest('.medicine-row').remove();
        });

        // Keep function available but BHW button is intentionally locked.
        if (addBtn) {
            addBtn.addEventListener('click', function (e) {
                e.preventDefault();
            });
        }
    });
</script>
@endsection

@push('scripts')
    @include('partials.birthday-material-picker-scripts')
@endpush