@extends('layouts.app')

@section('content')
@php
    $roleNormalized = strtolower(trim((string) (auth()->user()->role ?? 'doctor')));
    $routePrefix = $roleNormalized === 'nurse' ? 'nurse' : 'doctor';
@endphp

<div class="max-w-7xl mx-auto pb-20 px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pending Patient</h1>
            <p class="text-gray-500 text-sm mt-1">A list of pending patient records for the current session.</p>
        </div>
        <div class="w-full md:w-auto flex gap-2">
            <input type="text" id="searchInput" placeholder="Search pending patient records..."
                class="pl-4 pr-4 py-2.5 w-full md:w-72 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-400 outline-none shadow-sm">
            <button type="button" class="w-11 h-11 rounded-xl border border-gray-200 bg-white text-slate-500 inline-flex items-center justify-center hover:bg-green-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18l-7 8v5l-4 2v-7L3 5z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-5 py-3.5 text-left uppercase">Patient Record</th>
                    <th class="px-5 py-3.5 text-left uppercase">Age / Gender</th>
                    <th class="px-5 py-3.5 text-left uppercase">Address</th>
                    <th class="px-5 py-3.5 text-left uppercase">Status</th>
                    <th class="px-5 py-3.5 text-center uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="recordsTableBody" class="divide-y divide-gray-100">
                @forelse($records as $record)
                    @php
                        $birthDate = \Carbon\Carbon::parse($record->birthday);
                        $ageYears = (int) $birthDate->diffInYears(now());
                        $ageMonths = (int) $birthDate->diffInMonths(now());
                        $ageDisplay = $ageMonths < 12 ? $ageMonths . ($ageMonths === 1 ? ' month' : ' months') : $ageYears . ($ageYears === 1 ? ' year' : ' years');
                        $subjective = $record->subjective ?? '--';
                    @endphp
                    <tr class="hover:bg-green-50/30 transition patient-row"
                        data-name="{{ strtolower(trim($record->first_name . ' ' . $record->last_name)) }}"
                    >
                        <td class="px-5 py-4 text-sm">
                            <span class="font-semibold text-gray-800 capitalize">{{ $record->first_name }} {{ $record->last_name }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">
                            {{ $ageDisplay }}/{{ $record->gender }}
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">{{ $record->address_purok }}</td>
                        <td class="px-5 py-4 text-sm">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                <span class="text-gray-600 font-medium">In Progress</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route($routePrefix . '.record.create', ['patient_record_id' => $record->id]) }}"
                                    class="flex items-center justify-center w-9 h-9 rounded-full bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all"
                                    title="Add consultation">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="{{ route($routePrefix . '.record.show', $record->id) }}?from=pending"
                                    class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-800 hover:text-white transition-all"
                                    title="View patient">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">No pending patient records in queue.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="recordsPagination" class="mt-4"></div>
</div>

<script>
const allRows = Array.from(document.querySelectorAll('#recordsTableBody .patient-row')).map(row => ({
    html: row.outerHTML,
    name: (row.dataset.name || '').toLowerCase(),
}));

function renderPendingPagination(filteredRows) {
    renderPaginationTable({
        pagerSelector: '#recordsPagination',
        tableBodySelector: '#recordsTableBody',
        rows: filteredRows.map(item => item.html),
        emptyRowHtml: '<tr><td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">No pending patient records found.</td></tr>'
    });
}

function applyPendingSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filtered = allRows.filter(row => row.name.includes(searchTerm));
    renderPendingPagination(filtered);
}

document.getElementById('searchInput').addEventListener('keyup', applyPendingSearch);
document.addEventListener('DOMContentLoaded', function () {
    applyPendingSearch();
});
</script>
@endsection
