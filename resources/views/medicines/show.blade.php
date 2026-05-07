@extends('layouts.app')

@section('content')
@php
    $isDoctorRole = (auth()->user()->role ?? null) === 'doctor';
    $batchLabel = $medicine->batch_number ?? 'LOT-' . $medicine->id;
@endphp
<div class="max-w-5xl mx-auto py-6 px-4 pb-14">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <a href="{{ route('medicines.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-3 text-sm font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inventory
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Batch activity</h1>
            <p class="text-gray-500 text-sm mt-1">
                <span class="font-semibold text-slate-700">{{ $medicine->name }}</span>
                <span class="text-gray-400 mx-1">·</span>
                <span>Stock #{{ $batchLabel }}</span>
            </p>
        </div>
        @unless($isDoctorRole)
            <a href="{{ route('medicines.edit', $medicine) }}" class="shrink-0 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#EFF6FF] text-[#2563EB] rounded-xl text-sm font-bold hover:bg-blue-100 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit batch
            </a>
        @endunless
    </div>

    @include('medicines.partials.batch-inventory-activity')
</div>
@endsection

@push('scripts')
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
