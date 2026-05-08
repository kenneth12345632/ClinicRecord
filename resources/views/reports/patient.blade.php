@extends('layouts.app')

@section('content')
@php
    $patientReportPrivacyBhw = $patientReportPrivacyBhw ?? false;
    $patientReportIndexRoute = $patientReportPrivacyBhw ? route('bhw.reports.patients') : route('reports.patients');
    $patientReportExportRoute = $patientReportPrivacyBhw ? route('bhw.reports.patients.export') : route('reports.patients.export');
    $patientReportExportQuery = array_filter([
        'search' => $search,
        'age_group' => $ageGroup,
        'gender' => $gender,
        'address' => $address,
    ], fn ($v) => $v !== null && $v !== '');
    $patientReportExportUrl = $patientReportExportRoute . ($patientReportExportQuery !== [] ? '?' . http_build_query($patientReportExportQuery) : '');
    $paginationEmptyRowHtml = '<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">No patient records found.</td></tr>';
    $patientRowsHiddenMessageHtml = '<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">Records are hidden. Click &quot;Show Patients&quot; to display them.</td></tr>';
@endphp
<div class="max-w-7xl mx-auto pb-20 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 mt-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Patient Report</h1>
            <p class="text-gray-500 text-sm mt-1">Unique patient list and latest consultation details</p>
        </div>
        <a href="{{ $patientReportExportUrl }}"
            class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition">
            Export Excel
        </a>
    </div>

    @if($patientReportPrivacyBhw)
        <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            The table stays blank until you click <strong>Show Patients</strong>. Use the filters and search to narrow results, then click <strong>Show Patients</strong> to view matching rows (changing a dropdown reloads the page with your choices).
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <form method="GET" action="{{ $patientReportIndexRoute }}" class="flex flex-col gap-4">
                <div class="flex flex-wrap gap-3 items-end">
                    @if($patientReportPrivacyBhw)
                        <button type="button" id="togglePatientReportBtn"
                            class="px-4 py-2.5 rounded-xl border border-blue-200 text-sm font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition shadow-sm shrink-0">
                            Show Patients
                        </button>
                    @endif
                    <select id="age_group_filter" name="age_group"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer">
                        <option value="all" {{ $ageGroup === 'all' ? 'selected' : '' }}>All Ages</option>
                        <option value="0-11" {{ $ageGroup === '0-11' ? 'selected' : '' }}>Infants (0-11 months)</option>
                        <option value="12-59" {{ $ageGroup === '12-59' ? 'selected' : '' }}>Children (12-59 months)</option>
                        <option value="senior" {{ $ageGroup === 'senior' ? 'selected' : '' }}>Seniors (60+ years)</option>
                    </select>
                    <select id="gender_filter" name="gender"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer">
                        <option value="all" {{ $gender === 'all' ? 'selected' : '' }}>All Gender</option>
                        <option value="male" {{ $gender === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $gender === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <select id="address_filter" name="address"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 outline-none shadow-sm cursor-pointer min-w-[180px]">
                        <option value="all" {{ $address === 'all' ? 'selected' : '' }}>All Address</option>
                        @foreach(($addressOptions ?? []) as $addressOption)
                            <option value="{{ $addressOption }}" {{ $address === $addressOption ? 'selected' : '' }}>{{ strtoupper($addressOption) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-wrap gap-3 items-center w-full">
                    <div class="relative flex-1 min-w-[min(100%,16rem)] md:max-w-2xl">
                        <input type="text" name="search" id="patient_report_search" value="{{ $search }}"
                            placeholder="Search patients..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shrink-0">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patient Name</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Age / Gender</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Address</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Diagnosis</th>
                </tr>
            </thead>
            <tbody id="patientReportTableBody" class="divide-y divide-gray-50">
                @forelse($patients as $record)
                    @php
                        $birthDate = \Carbon\Carbon::parse($record->birthday);
                        $ageYears = (int) $birthDate->diffInYears(now());
                        $ageMonths = (int) $birthDate->diffInMonths(now());
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition patient-report-row">
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                            {{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-bold text-gray-800 capitalize">{{ $record->first_name }} {{ $record->last_name }}</div>
                            <div class="text-[10px] font-bold text-blue-500 uppercase tracking-tight">
                                DOB: {{ $birthDate->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <span class="font-bold text-gray-700">
                                @if($ageMonths < 12)
                                    {{ $ageMonths }} mon
                                @else
                                    {{ $ageYears }} yrs
                                @endif
                            </span> <span class="text-gray-300 mx-1">|</span> {{ $record->gender }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $record->address_purok }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 italic">"{{ \Illuminate\Support\Str::limit($record->resolved_diagnosis ?? $record->diagnosis, 40) }}"</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">
                            No patient records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="patientReportPagination" class="mt-4"></div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('patientReportTableBody');
        const pagerEl = document.getElementById('patientReportPagination');
        const ageFilter = document.getElementById('age_group_filter');
        const genderFilter = document.getElementById('gender_filter');
        const addressFilter = document.getElementById('address_filter');

        const paginationEmptyRowHtml = {!! json_encode($paginationEmptyRowHtml) !!};
        const patientRowsHiddenMessageHtml = {!! json_encode($patientRowsHiddenMessageHtml) !!};
        const patientReportPrivacyBhw = @json((bool) ($patientReportPrivacyBhw ?? false));

        const patientReportRows = Array.from(document.querySelectorAll('#patientReportTableBody .patient-report-row')).map(row => row.outerHTML);

        let reportRowsVisible = !patientReportPrivacyBhw;

        function destroyPatientPager() {
            if (!window.jQuery || !pagerEl) return;
            var $p = window.jQuery('#patientReportPagination');
            if ($p.length && typeof $p.pagination === 'function' && $p.data('pagination')) {
                try {
                    $p.pagination('destroy');
                } catch (err) {
                    /* ignore */
                }
            }
            pagerEl.innerHTML = '';
        }

        function updatePatientReportToggleLabel() {
            const btn = document.getElementById('togglePatientReportBtn');
            if (!btn) return;
            btn.textContent = reportRowsVisible ? 'Hide Patients' : 'Show Patients';
        }

        function renderPatientReportPagination() {
            if (!tbody) return;

            destroyPatientPager();

            if (patientReportPrivacyBhw && !reportRowsVisible) {
                tbody.innerHTML = patientReportRows.length > 0
                    ? patientRowsHiddenMessageHtml
                    : paginationEmptyRowHtml;
                return;
            }

            if (patientReportRows.length === 0) {
                tbody.innerHTML = paginationEmptyRowHtml;
                return;
            }

            if (typeof window.renderPaginationTable === 'function') {
                window.renderPaginationTable({
                    pagerSelector: '#patientReportPagination',
                    tableBodySelector: '#patientReportTableBody',
                    rows: patientReportRows,
                    emptyRowHtml: paginationEmptyRowHtml,
                    pageSize: Math.max(patientReportRows.length, 1)
                });
            } else {
                tbody.innerHTML = patientReportRows.join('');
            }
        }

        if (ageFilter && ageFilter.form) {
            [ageFilter, genderFilter, addressFilter].forEach((filterEl) => {
                if (!filterEl) return;
                filterEl.addEventListener('change', function () {
                    ageFilter.form.submit();
                });
            });
        }

        const toggleBtn = document.getElementById('togglePatientReportBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                reportRowsVisible = !reportRowsVisible;
                updatePatientReportToggleLabel();
                renderPatientReportPagination();
            });
        }

        renderPatientReportPagination();
        updatePatientReportToggleLabel();
    });
</script>
@endpush
