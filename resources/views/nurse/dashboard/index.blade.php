@extends('layouts.app')

@section('content')
@php
    $isNurseRole = (auth()->user()->role ?? '') === 'nurse';
    $recordIndexRoute = $isNurseRole ? route('nurse.record.index') : route('doctor.record.index');
    $clinicName = config('clinic.name');
    $clinicAddress = config('clinic.address') ?: 'Daily summary and center management overview.';
@endphp
<div class="max-w-7xl mx-auto">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 md:p-6 space-y-4 shadow-sm">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 md:p-6 space-y-4">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-5xl font-black text-slate-800">Nurse Dashboard</h1>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Current Date</p>
                    <p class="text-4xl font-black text-slate-700">{{ now()->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Patient Records</p>
                        <p class="text-4xl leading-none mt-1 font-black text-slate-800">{{ $totalPatientRecords ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Today's Patient Records</p>
                        <p class="text-4xl leading-none mt-1 font-black text-slate-800">{{ $todayConsultations ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-widest text-rose-500">Low Stock Medicines</p>
                        <p class="text-4xl leading-none mt-1 font-black text-rose-600">{{ $lowStockCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Recent Activity</h3>
                        <a href="{{ $recordIndexRoute }}" class="text-sm text-blue-600 font-bold hover:underline">View History</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($recentRecords ?? [] as $recent)
                            <div class="p-4 flex items-center justify-between gap-3 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-[11px] font-black uppercase text-slate-500 shrink-0">
                                        {{ strtoupper(substr($recent->first_name, 0, 1)) }}{{ strtoupper(substr($recent->last_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800 truncate">
                                            {{ \Illuminate\Support\Str::title(trim($recent->first_name . ' ' . ($recent->middle_name ? $recent->middle_name . ' ' : '') . $recent->last_name)) }}
                                        </p>
                                        <p class="text-xs text-slate-500 truncate">{{ $recent->diagnosis }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-slate-400 shrink-0">{{ \Carbon\Carbon::parse($recent->consultation_date)->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 italic text-sm">No recent consultations recorded.</div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h3 class="text-xl font-black text-slate-800 mb-4 flex items-center gap-2">
                        <span class="text-blue-500">📈</span> Weekly Patient Records Trend
                    </h3>
                    @if(($weeklyPatientRecords ?? collect())->count() > 0)
                        <div class="relative h-64 rounded-xl border border-slate-100 bg-white p-3">
                            <canvas id="weeklyPatientRecordsTrendChart"></canvas>
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">
                            No recent patient trend data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('weeklyPatientRecordsTrendChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const rawRows = @json(($weeklyPatientRecords ?? collect())->values()->map(function ($row) {
            return [
                'label' => \Carbon\Carbon::parse($row->day)->format('D M d'),
                'value' => (int) $row->total,
            ];
        }));

        if (!Array.isArray(rawRows) || rawRows.length === 0) return;

        const labels = rawRows.map(row => row.label);
        const values = rawRows.map(row => row.value);
        const ctx = canvas.getContext('2d');
        const gradientA = ctx.createLinearGradient(0, 0, 0, 260);
        gradientA.addColorStop(0, 'rgba(16, 185, 129, 0.45)');
        gradientA.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data: values,
                    borderColor: '#059669',
                    backgroundColor: gradientA,
                    fill: true,
                    tension: 0.2,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#16a34a',
                    pointBorderColor: '#16a34a',
                    pointBorderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: '#f1f5f9' }, border: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, stepSize: 2 },
                        grid: { color: '#e2e8f0', lineWidth: 1 },
                        border: { display: false }
                    }
                },
                elements: { line: { borderWidth: 3 } }
            }
        });
    });
</script>
@endsection

