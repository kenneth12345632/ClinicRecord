@extends('layouts.app')

@php
    $dispensingRoutePrefix = $dispensingRoutePrefix ?? 'bhw';
@endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route($dispensingRoutePrefix . '.dispensing.index') }}" class="text-sm font-bold text-green-600 hover:underline mb-6 inline-block">← Back to queue</a>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h1 class="text-xl font-black text-slate-800">
                {{ \Illuminate\Support\Str::title(trim($record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)) }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">Consultation {{ optional($record->consultation_date)->format('M d, Y') ?? '—' }}</p>
        </div>
        <div class="px-6 py-4 space-y-2 text-sm text-slate-700">
            <p><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider">Assessment / Diagnosis</span><br>{{ $record->diagnosis ?: '—' }}</p>
            <p><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider">Treatment plan</span><br>{{ $record->follow_up_recommendation ?: '—' }}</p>
        </div>
    </div>

    @if($errors->has('dispense'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ $errors->first('dispense') }}</div>
    @endif
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('info') }}</div>
    @endif

    {{-- Plan / Medicines style panel (light: green card; dark: navy + forest header per design) --}}
    <form action="{{ route($dispensingRoutePrefix . '.dispensing.dispense', $record) }}" method="POST" onsubmit="return confirm(@json($pendingMedicines->isNotEmpty() ? 'Record entered quantities as given and deduct from inventory?' : 'Add this visit to Clinic Records for this patient?'));"
        class="rounded-2xl border-2 border-green-500 bg-white shadow-lg shadow-green-500/10 overflow-hidden dark:border-[#1f5c3d] dark:bg-[#0f151c] dark:shadow-xl dark:shadow-black/50">
        @csrf
        <div class="px-5 py-3.5 border-b-2 border-green-500 bg-green-50/90 flex items-center justify-between gap-3 dark:border-[#2a6b45] dark:bg-[#0c2216]">
            <div class="flex items-center gap-2">
                <span class="bg-green-600 text-white w-7 h-7 flex items-center justify-center rounded font-bold text-xs shrink-0 dark:bg-green-500 dark:shadow-[0_0_0_1px_rgba(74,222,128,0.35)]">P</span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-green-700 dark:text-white">Plan / Medicines</p>
                    <p class="text-[11px] text-slate-500 mt-0.5 dark:text-green-100/45">Edit quantity if releasing less than prescribed, then confirm to finalize this visit.</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-green-100 dark:divide-[#1a3d2c]/90 dark:bg-[#0a1018]">
            @forelse($pendingMedicines as $med)
                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 dark:bg-[#0a1018]">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $med->name }}</p>
                        @if($med->batch_number)
                            <p class="text-[11px] text-slate-500 mt-0.5 dark:text-slate-400">Batch {{ $med->batch_number }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-6 shrink-0 text-sm">
                        <div>
                            <label for="dispense-qty-{{ $med->pivot->id }}" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block dark:text-slate-500">Quantity</label>
                            <input id="dispense-qty-{{ $med->pivot->id }}"
                                   type="number"
                                   name="dispense_quantities[{{ $med->pivot->id }}]"
                                   value="{{ old('dispense_quantities.' . $med->pivot->id, (int) $med->pivot->quantity) }}"
                                   min="0"
                                   max="{{ (int) $med->pivot->quantity }}"
                                   class="mt-1 w-24 rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-800 focus:border-green-400 focus:outline-none dark:border-white/40 dark:bg-[#050a12] dark:text-white dark:[color-scheme:dark] dark:focus:border-green-400 dark:focus:ring-1 dark:focus:ring-green-500/40">
                            <p class="text-[10px] text-slate-400 mt-1 dark:text-slate-500">Prescribed: {{ (int) $med->pivot->quantity }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block dark:text-slate-500">Expiry</span>
                            <span class="font-semibold text-slate-700 dark:text-white">{{ optional($med->expiration_date)->format('M d, Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-6 text-sm text-slate-600 leading-relaxed dark:bg-[#0a1018] dark:text-slate-300">
                    No medicines were prescribed on this visit. Confirm below to <span class="font-semibold text-slate-800 dark:text-white">add it to Clinic Records</span> for this patient.
                </div>
            @endforelse
        </div>

        <div class="px-5 py-4 border-t border-green-100 bg-white dark:border-t dark:border-[#1a3d2c]/90 dark:bg-[#0f151c]">
            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-green-600 text-white font-bold text-sm shadow-md hover:bg-green-700 transition dark:bg-green-500 dark:hover:bg-green-400 dark:shadow-lg dark:shadow-green-900/40">
                {{ $pendingMedicines->isNotEmpty() ? 'Confirm entered quantities' : 'Confirm visit on Clinic Records' }}
            </button>
        </div>
    </form>

    @if($completedMedicines->isNotEmpty())
        <div class="mt-10 rounded-xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-3">Already released</p>
            <ul class="space-y-2 text-sm text-slate-700">
                @foreach($completedMedicines as $med)
                    <li class="flex justify-between gap-2">
                        <span class="font-semibold">{{ $med->name }}</span>
                        <span>× {{ $med->pivot->quantity }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
