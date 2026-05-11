@extends('layouts.app')

@section('content')
@php
    $clinicName = config('clinic.name');
    $clinicAddress = config('clinic.address') ?: 'Centralized operations and system insights.';
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="space-y-2">
            <div>
                <h1 class="text-5xl font-black text-slate-800 tracking-tight">Admin Dashboard</h1>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Today</p>
            <p class="text-2xl font-black text-slate-700">{{ now()->format('F d, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Patient Records</p>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">👥</span>
            </div>
            <p class="mt-2 text-4xl font-black leading-none text-slate-800">{{ $totalPatientRecords }}</p>
            <p class="mt-3 text-[10px] font-semibold uppercase text-emerald-600">Live registry count</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Today's Patient Records</p>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">📅</span>
            </div>
            <p class="mt-2 text-4xl font-black leading-none text-slate-800">{{ $todaysPatientRecords }}</p>
            <p class="mt-3 text-[10px] font-semibold uppercase text-slate-500">Current day census</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Total Consultations</p>
                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">🩺</span>
            </div>
            <p class="mt-2 text-4xl font-black leading-none text-slate-800">{{ $totalConsultations }}</p>
            <p class="mt-3 text-[10px] font-semibold uppercase text-indigo-600">All-time consultations</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-amber-500">Pending Consultations</p>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">⏳</span>
            </div>
            <p class="mt-2 text-4xl font-black leading-none text-amber-600">{{ $pendingConsultations }}</p>
            <p class="mt-3 text-[10px] font-semibold uppercase text-amber-600">Needs doctor review</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-[11px] font-bold uppercase tracking-widest text-rose-500">Low Stock Medicines</p>
                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">⚠️</span>
            </div>
            <p class="mt-2 text-4xl font-black leading-none text-rose-600">{{ $lowStockMedicines->count() }}</p>
            <p class="mt-3 text-[10px] font-semibold uppercase text-rose-600">Critical inventory</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 px-5 py-4 flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <span class="text-blue-500">🧾</span> Recent Activity Logs
                </h2>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">View All</a>
            </div>
            <div id="recentActivityLogs" class="divide-y divide-slate-100">
                @forelse($recentLogs as $log)
                    @php
                        $displayName = $log->user?->full_name ?? 'System';
                        $nameParts = preg_split('/\s+/', trim((string) $displayName)) ?: [];
                        $avatarInitials = strtoupper(
                            (isset($nameParts[0][0]) ? $nameParts[0][0] : 'S') .
                            (isset($nameParts[1][0]) ? $nameParts[1][0] : '')
                        );
                        $avatarPalette = ['bg-blue-600', 'bg-slate-500', 'bg-emerald-600', 'bg-amber-700', 'bg-indigo-600'];
                        $avatarClass = $avatarPalette[crc32((string) $displayName) % count($avatarPalette)];
                    @endphp
                    <div class="recent-log-item flex items-start justify-between gap-3 px-5 py-3">
                        <div class="min-w-0 flex items-start gap-3">
                            @if(!empty($log->user?->profile_photo_path))
                                <img
                                    src="{{ asset('storage/'.$log->user->profile_photo_path) }}"
                                    alt="{{ $displayName }}"
                                    class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0"
                                >
                            @else
                                <div class="w-8 h-8 rounded-full {{ $avatarClass }} text-white text-[11px] font-black flex items-center justify-center shrink-0">
                                    {{ $avatarInitials }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-base font-normal text-slate-800 truncate">{{ $displayName }} - {{ $log->action }}</p>
                                <p class="text-sm text-slate-500">{{ $log->description }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs font-semibold text-slate-400 mt-1">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="px-5 py-4 text-sm text-slate-500">No recent logs.</p>
                @endforelse
            </div>
            <div id="recentActivityPagination" class="border-t border-slate-100 px-5 py-3"></div>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xl font-black text-slate-800 mb-4 flex items-center gap-2">
                    <span class="text-blue-500">📈</span> Weekly Patient Records Trend
                </h3>

                @if($weeklyPatientRecords->count() > 0)
                    <div class="relative h-64 rounded-xl border border-slate-100 bg-white p-3">
                        <canvas id="weeklyPatientRecordsTrendChart"></canvas>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500">
                        No recent patient record trend data.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logItems = Array.from(document.querySelectorAll('#recentActivityLogs .recent-log-item'));
        if (logItems.length === 0) return;

        const pager = $('#recentActivityPagination');
        pager.pagination({
            dataSource: logItems,
            pageSize: 10,
            showSizeChanger: false,
            callback: function (data) {
                logItems.forEach(item => item.style.display = 'none');
                data.forEach(item => item.style.display = '');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('weeklyPatientRecordsTrendChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const rawRows = @json($weeklyPatientRecords->values()->map(function ($row) {
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
                datasets: [
                    {
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
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#f8fafc',
                        bodyColor: '#f8fafc',
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#475569',
                            font: { size: 11, weight: '600' }
                        },
                        border: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 2,
                            color: '#475569',
                            font: { size: 11, weight: '600' }
                        },
                        grid: {
                            color: '#e2e8f0',
                            lineWidth: 1
                        },
                        border: { display: false }
                    }
                },
                elements: {
                    line: { borderWidth: 3 }
                }
            }
        });
    });
</script>
@endsection
