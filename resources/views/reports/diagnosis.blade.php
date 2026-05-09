@extends('layouts.app')

@section('content')
@include('partials.material-calendar-flatpickr-assets')
@php
    $diagnosisPrivacyBhw = $diagnosisPrivacyBhw ?? false;
    $diagnosisEmptyTableMessage = 'No diagnosis records found.';
    $diagnosisIndexRoute = $diagnosisPrivacyBhw ? route('bhw.reports.diagnosis') : route('reports.diagnosis');
    $diagnosisExportRoute = $diagnosisPrivacyBhw ? route('bhw.reports.diagnosis.export') : route('reports.diagnosis.export');
    $paginationEmptyRowHtml = '<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">' . e($diagnosisEmptyTableMessage) . '</td></tr>';
    $diagnosisRowsHiddenMessageHtml = '<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">Records are hidden. Click &quot;Show Patient Records&quot; to display them.</td></tr>';
    $diagnosisExportQuery = array_filter([
        'search' => $search,
        'from_date' => $fromDate ?? null,
        'to_date' => $toDate ?? null,
        'month' => $month ?? null,
    ], fn ($v) => $v !== null && $v !== '');
    $diagnosisExportUrl = $diagnosisExportRoute . ($diagnosisExportQuery !== [] ? '?' . http_build_query($diagnosisExportQuery) : '');
@endphp
<div class="max-w-7xl mx-auto pb-20 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 mt-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Diagnosis Report</h1>
            <p class="text-gray-500 text-sm mt-1">All consultation diagnosis records</p>
        </div>
        <a href="{{ $diagnosisExportUrl }}"
            class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition">
            Export Excel
        </a>
    </div>

    @if($diagnosisPrivacyBhw)
        <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            Patient record rows stay blank until you click <strong>Show Patient Records</strong> (optional: use Search to narrow by patient, diagnosis, or dates). Each diagnosis stays masked until you click <strong>Show diagnosis</strong> on that row.
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ $diagnosisIndexRoute }}" class="flex flex-wrap gap-3 items-end">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search diagnosis or patient..."
                    class="w-full md:w-80 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">From</label>
                    <input type="text" name="from_date" autocomplete="off" placeholder="dd/mm/yyyy"
                        data-material-calendar data-default="{{ $fromDate ?? '' }}"
                        data-alt-class="w-[11rem] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                        class="w-[11rem] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">To</label>
                    <input type="text" name="to_date" autocomplete="off" placeholder="dd/mm/yyyy"
                        data-material-calendar data-default="{{ $toDate ?? '' }}"
                        data-alt-class="w-[11rem] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                        class="w-[11rem] px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Month</label>
                    <input type="month" name="month" value="{{ $month ?? '' }}"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                    Search
                </button>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ $diagnosisIndexRoute }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition">
                        Clear
                    </a>
                    @if($diagnosisPrivacyBhw)
                        <button type="button" id="toggleDiagnosisRowsBtn"
                            class="px-4 py-2.5 rounded-xl border border-blue-200 text-sm font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition shadow-sm">
                            Show Patient Records
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patient Name</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Diagnosis</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Vital Signs</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Address</th>
                </tr>
            </thead>
            <tbody id="diagnosisReportTableBody" class="divide-y divide-gray-50">
                @forelse($diagnosisReports as $record)
                    <tr class="hover:bg-blue-50/30 transition diagnosis-report-row">
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-bold text-gray-800 capitalize">{{ $record->first_name }} {{ $record->last_name }}</div>
                            <div class="text-[10px] font-bold text-blue-500 uppercase tracking-tight">
                                Age: {{ $record->age ?: '--' }} / {{ $record->gender ?: '--' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($diagnosisPrivacyBhw)
                                @php $diagShort = \Illuminate\Support\Str::limit($record->diagnosis, 70); @endphp
                                <div class="diagnosis-privacy-cell space-y-1">
                                    <span class="diagnosis-visible-text hidden italic">"{{ $diagShort }}"</span>
                                    <span class="diagnosis-hidden-label text-xs text-gray-400">Hidden — click Show to view</span>
                                    <button type="button"
                                        class="diagnosis-toggle-btn text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline w-fit text-left">
                                        Show diagnosis
                                    </button>
                                </div>
                            @else
                                <span class="italic">"{{ \Illuminate\Support\Str::limit($record->diagnosis, 70) }}"</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            T: {{ $record->temp ?: '--' }},
                            BP: {{ $record->bp ?: '--' }},
                            PR: {{ $record->pr ?: '--' }},
                            RR: {{ $record->rr ?: '--' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $record->address_purok }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">
                            {{ $diagnosisEmptyTableMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="diagnosisReportPagination" class="mt-4"></div>
</div>
@endsection

@push('scripts')
@include('partials.material-calendar-flatpickr-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('diagnosisReportTableBody');
        const pagerEl = document.getElementById('diagnosisReportPagination');
        const paginationEmptyRowHtml = {!! json_encode($paginationEmptyRowHtml) !!};
        const diagnosisRowsHiddenMessageHtml = {!! json_encode($diagnosisRowsHiddenMessageHtml) !!};
        const diagnosisPrivacyBhw = @json((bool) ($diagnosisPrivacyBhw ?? false));

        const diagnosisReportRows = Array.from(document.querySelectorAll('#diagnosisReportTableBody .diagnosis-report-row')).map(row => row.outerHTML);

        let reportRowsVisible = !diagnosisPrivacyBhw;

        function destroyDiagnosisPager() {
            if (!window.jQuery || !pagerEl) return;
            var $p = window.jQuery('#diagnosisReportPagination');
            if ($p.length && typeof $p.pagination === 'function' && $p.data('pagination')) {
                try {
                    $p.pagination('destroy');
                } catch (err) {
                    /* ignore */
                }
            }
            pagerEl.innerHTML = '';
        }

        function updateDiagnosisToggleLabel() {
            const btn = document.getElementById('toggleDiagnosisRowsBtn');
            if (!btn) return;
            btn.textContent = reportRowsVisible ? 'Hide Patient Records' : 'Show Patient Records';
        }

        function renderDiagnosisPagination() {
            if (!tbody) return;

            destroyDiagnosisPager();

            if (diagnosisPrivacyBhw && !reportRowsVisible) {
                tbody.innerHTML = diagnosisReportRows.length > 0
                    ? diagnosisRowsHiddenMessageHtml
                    : paginationEmptyRowHtml;
                return;
            }

            if (diagnosisReportRows.length === 0) {
                tbody.innerHTML = paginationEmptyRowHtml;
                return;
            }

            if (typeof window.renderPaginationTable === 'function') {
                window.renderPaginationTable({
                    pagerSelector: '#diagnosisReportPagination',
                    tableBodySelector: '#diagnosisReportTableBody',
                    rows: diagnosisReportRows,
                    emptyRowHtml: paginationEmptyRowHtml,
                    pageSize: Math.max(diagnosisReportRows.length, 1)
                });
            } else {
                tbody.innerHTML = diagnosisReportRows.join('');
            }
        }

        const toggleBtn = document.getElementById('toggleDiagnosisRowsBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                reportRowsVisible = !reportRowsVisible;
                updateDiagnosisToggleLabel();
                renderDiagnosisPagination();
            });
        }

        renderDiagnosisPagination();
        updateDiagnosisToggleLabel();

        if (!tbody) return;

        tbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.diagnosis-toggle-btn');
            if (!btn || !tbody.contains(btn)) return;

            const cell = btn.closest('.diagnosis-privacy-cell');
            if (!cell) return;

            const visible = cell.querySelector('.diagnosis-visible-text');
            const hiddenLabel = cell.querySelector('.diagnosis-hidden-label');
            if (!visible || !hiddenLabel) return;

            const isShown = !visible.classList.contains('hidden');
            if (isShown) {
                visible.classList.add('hidden');
                hiddenLabel.classList.remove('hidden');
                btn.textContent = 'Show diagnosis';
            } else {
                visible.classList.remove('hidden');
                hiddenLabel.classList.add('hidden');
                btn.textContent = 'Hide diagnosis';
            }
        });
    });
</script>
@endpush
