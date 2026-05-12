@extends('layouts.app')

@section('content')
@php
    $clinicName = config('clinic.name');
    $clinicAddress = config('clinic.address') ?: 'Daily summary and center management overview.';
    $dashboardMedicineQueueCount = \App\Models\ClinicRecord::awaitingMedicineDispensing()->count();
@endphp

<div class="max-w-7xl mx-auto">
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 md:p-8 shadow-sm space-y-6">
        <div class="flex flex-wrap items-start justify-between gap-4 md:items-end">
            <div class="min-w-0">
                <h1 class="text-4xl font-black text-slate-800">BHW Dashboard</h1>
            </div>
            {{-- BHW dashboard: notification and date on the same line --}}
            <div class="flex w-full flex-shrink-0 items-center justify-end gap-3 sm:w-auto">
                <div class="text-right leading-tight">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Current Date</p>
                    <p class="text-2xl font-black text-slate-700">{{ now()->format('F d, Y') }}</p>
                </div>
                <a href="{{ route('bhw.dispensing.index') }}"
                   class="inline-flex items-center justify-center p-0.5 transition"
                   title="Medicine queue">
                    <span class="relative inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-900/40 dark:text-green-400 dark:ring-green-800">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a5 5 0 00-5 5v3.764c0 .52-.212 1.018-.586 1.383L5.05 13.51A1.5 1.5 0 006.11 16h11.78a1.5 1.5 0 001.06-2.56l-1.364-1.293A1.93 1.93 0 0117 10.764V7a5 5 0 00-5-5z"/>
                            <path d="M9.75 18a2.25 2.25 0 004.5 0h-4.5z"/>
                        </svg>
                        @if($dashboardMedicineQueueCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-black text-white leading-none">{{ $dashboardMedicineQueueCount > 99 ? '99+' : $dashboardMedicineQueueCount }}</span>
                        @endif
                    </span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Patient Records</p>
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center text-xs">👥</span>
                </div>
                <p class="text-4xl font-black mt-2 text-slate-800">{{ $totalPatientRecords ?? 0 }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-emerald-600 mt-2">Live registry count</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Today's Patient Records</p>
                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs">🏥</span>
                </div>
                <p class="text-4xl font-black mt-2 text-slate-800">{{ $todayConsultations ?? 0 }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-500 mt-2">Current day census</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-bold uppercase tracking-widest text-rose-500">Low Stock Medicines</p>
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center text-xs">⚠️</span>
                </div>
                <p class="text-4xl font-black mt-2 text-rose-600">{{ $lowStockCount ?? 0 }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-rose-500 mt-2">Critical inventory</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Recent Activity</h3>
                    <a href="{{ route('bhw.record.index') }}" class="text-sm text-blue-600 font-bold hover:underline">View History</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentRecords ?? [] as $recent)
                        <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-slate-50 transition">
                            <div class="min-w-0 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-[11px] font-black uppercase text-slate-600 shrink-0">
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
        gradientA.addColorStop(0, 'rgba(239, 68, 68, 0.45)');
        gradientA.addColorStop(1, 'rgba(239, 68, 68, 0.02)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    data: values,
                    borderColor: '#dc2626',
                    backgroundColor: gradientA,
                    fill: true,
                    tension: 0.2,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#ef4444',
                    pointBorderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { color: document.documentElement.classList.contains('dark') ? '#334155' : '#f1f5f9' },
                        border: { display: false },
                        ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 2,
                            color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                        },
                        grid: { color: document.documentElement.classList.contains('dark') ? '#334155' : '#e2e8f0', lineWidth: 1 },
                        border: { display: false }
                    }
                },
                elements: { line: { borderWidth: 3 } }
            }
        });
    });
</script>
@endsection

