@extends('layouts.app')

@php
    $dispensingRoutePrefix = $dispensingRoutePrefix ?? 'bhw';
@endphp

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Medicine to give</h1>
        <p class="text-slate-500 text-sm mt-1 max-w-2xl leading-relaxed">Only patients whose consultation was <span class="font-semibold text-slate-600">saved by a doctor</span> appear here (nurse triage alone does not add a row). Open each patient to release prescribed items from inventory. If <span class="font-semibold text-slate-600">no medicines</span> were ordered, BHW still opens the visit and taps <span class="font-semibold text-slate-600">Confirm visit on Clinic Records</span> so that consultation appears on Clinic Records—the badge <span class="font-semibold text-slate-600">No medicines to release</span> only means there is nothing to deduct from stock.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800">{{ session('info') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($records as $rec)
                <a href="{{ route($dispensingRoutePrefix . '.dispensing.show', $rec) }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 hover:bg-slate-50 transition">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 truncate">
                            {{ \Illuminate\Support\Str::title(trim($rec->first_name . ' ' . ($rec->middle_name ? $rec->middle_name . ' ' : '') . $rec->last_name)) }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">Consultation {{ optional($rec->consultation_date)->format('M d, Y') ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">
                            @if(($rec->pending_medicine_lines ?? 0) > 0)
                                {{ $rec->pending_medicine_lines }} line(s)
                            @else
                                No medicines to release
                            @endif
                        </span>
                        <span class="text-blue-600 font-bold text-sm">Open →</span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-14 text-center text-slate-400 text-sm font-medium">No patients waiting for medicine release.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $records->links() }}</div>
</div>
@endsection
