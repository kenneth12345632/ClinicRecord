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
            <p class="text-xs text-slate-500 mt-1">Consultation {{ optional($record->consultation_date)->format('M d, Y') ?? '—' }} · Record #{{ $record->id }}</p>
        </div>
        <div class="px-6 py-4 space-y-2 text-sm text-slate-700">
            <p><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider">Assessment / Diagnosis</span><br>{{ $record->diagnosis ?: '—' }}</p>
            <p><span class="font-bold text-slate-500 uppercase text-[10px] tracking-wider">Treatment plan</span><br>{{ $record->follow_up_recommendation ?: '—' }}</p>
        </div>
    </div>

    @if($errors->has('dispense'))
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ $errors->first('dispense') }}</div>
    @endif

    {{-- Plan / Medicines style panel --}}
    <div class="rounded-2xl border-2 border-blue-500 bg-white shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="px-5 py-3.5 border-b-2 border-blue-500 bg-blue-50/90 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="bg-blue-600 text-white w-7 h-7 flex items-center justify-center rounded font-bold text-xs shrink-0">P</span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Plan / Medicines</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Confirm each line when the patient has received the medicine.</p>
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
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Quantity</span>
                            <span class="font-bold text-slate-800">{{ $med->pivot->quantity }}</span>
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
    </div>

    <form action="{{ route($dispensingRoutePrefix . '.dispensing.dispense', $record) }}" method="POST" class="mt-6" onsubmit="return confirm(@json($pendingMedicines->isNotEmpty() ? 'Record all listed medicines as given and deduct from inventory?' : 'Add this visit to Clinic Records for this patient?'));">
        @csrf
        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-md hover:bg-blue-700 transition">
            {{ $pendingMedicines->isNotEmpty() ? 'Confirm all medicines given' : 'Confirm visit on Clinic Records' }}
        </button>
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
