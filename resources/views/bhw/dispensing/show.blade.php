@extends('layouts.app')

@php
    $dispensingRoutePrefix = $dispensingRoutePrefix ?? 'bhw';
@endphp

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route($dispensingRoutePrefix . '.dispensing.index') }}" class="text-sm font-bold text-blue-600 hover:underline mb-6 inline-block">← Back to queue</a>

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
        <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800">{{ session('info') }}</div>
    @endif

    {{-- Plan / Medicines style panel --}}
    <form action="{{ route($dispensingRoutePrefix . '.dispensing.dispense', $record) }}" method="POST" onsubmit="return confirm(@json($pendingMedicines->isNotEmpty() ? 'Record entered quantities as given and deduct from inventory?' : 'Add this visit to Clinic Records for this patient?'));"
        class="rounded-2xl border-2 border-blue-500 bg-white shadow-lg shadow-blue-500/10 overflow-hidden">
        @csrf
        <div class="px-5 py-3.5 border-b-2 border-blue-500 bg-blue-50/90 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="bg-blue-600 text-white w-7 h-7 flex items-center justify-center rounded font-bold text-xs shrink-0">P</span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Plan / Medicines</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Edit quantity if releasing less than prescribed, then confirm to finalize this visit.</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-blue-100">
            @forelse($pendingMedicines as $med)
                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900">{{ $med->name }}</p>
                        @if($med->batch_number)
                            <p class="text-[11px] text-slate-500 mt-0.5">Batch {{ $med->batch_number }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-6 shrink-0 text-sm">
                        <div>
                            <label for="dispense-qty-{{ $med->pivot->id }}" class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Quantity</label>
                            <input id="dispense-qty-{{ $med->pivot->id }}"
                                   type="number"
                                   name="dispense_quantities[{{ $med->pivot->id }}]"
                                   value="{{ old('dispense_quantities.' . $med->pivot->id, (int) $med->pivot->quantity) }}"
                                   min="0"
                                   max="{{ (int) $med->pivot->quantity }}"
                                   class="mt-1 w-24 rounded-lg border border-slate-200 px-3 py-1.5 font-bold text-slate-800 focus:border-blue-500 focus:outline-none">
                            <p class="text-[10px] text-slate-400 mt-1">Prescribed: {{ (int) $med->pivot->quantity }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Expiry</span>
                            <span class="font-semibold text-slate-700">{{ optional($med->expiration_date)->format('M d, Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-6 text-sm text-slate-600 leading-relaxed">
                    No medicines were prescribed on this visit. Confirm below to <span class="font-semibold text-slate-800">add it to Clinic Records</span> for this patient.
                </div>
            @endforelse
        </div>

        @if($pendingMedicines->isNotEmpty())
            <div class="px-5 py-4 border-t border-blue-100 bg-blue-50/40">
                <label for="release_note" class="text-[11px] font-bold uppercase tracking-wider text-blue-700 block mb-1">BHW note</label>
                <textarea id="release_note" name="release_note" rows="2"
                    placeholder="Type reason for limited release (optional), e.g. Supply is limited today; partial quantity given."
                    class="w-full rounded-xl border border-blue-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none">{{ old('release_note') }}</textarea>
            </div>
        @endif

        <div class="px-5 py-4 border-t border-blue-100 bg-white">
            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-md hover:bg-blue-700 transition">
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
