@extends('layouts.app')

@section('content')
{{-- 1. ADD SELECT2 DEPENDENCIES --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* ✅ 2. MODAL SEARCH STYLING */
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        display: flex;
        align-items: center;
    }
    .select2-container--open { z-index: 9999 !important; }
</style>

{{-- Hidden data container --}}
<div id="medicine-data" data-medicines="{{ json_encode($allMedicines ?? []) }}"></div>

<div class="max-w-7xl mx-auto pb-20 px-4 sm:px-6 lg:px-8"> 
    {{-- Header Section with Filter and Search --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 mt-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Clinic Records</h1>
            <p class="text-gray-500 text-sm mt-1">Showing unique patient history</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            {{-- Age Filter --}}
          <select id="ageFilter" class="px-4 py-2.5 rounded-xl border ...">
    <option value="all">All Ages</option>
    <option value="0-11">Infants (0-11 months)</option>
    <option value="12-59">Children (12-59 months)</option>
    <option value="senior">Seniors (60+ years)</option>
</select>

            {{-- Search Bar --}}
            <div class="relative flex-grow md:flex-grow-0">
                <input type="text" id="searchInput" placeholder="Search patients..." 
                    class="pl-10 pr-4 py-2.5 w-full md:w-64 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">
                <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Patient Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Age / Gender</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Diagnosis</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="recordsTableBody" class="divide-y divide-gray-100">
                @forelse($records as $record)
                @php
                    $birthDate = \Carbon\Carbon::parse($record->birthday);
                    // Force the age to be an integer (whole number)
                    $ageYears = (int)$birthDate->diffInYears(now()); 
                    $ageMonths = (int)$birthDate->diffInMonths(now());
                @endphp
                <tr class="hover:bg-blue-50/50 transition patient-row" 
                    data-age-years="{{ $ageYears }}" 
                    data-age-months="{{ $ageMonths }}">
                    
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}
                    </td>
                    
                    <td class="px-6 py-4 text-sm">
                        <div class="font-bold text-slate-800 capitalize patient-name">{{ $record->first_name }} {{ $record->last_name }}</div>
                        <div class="text-[10px] font-bold text-blue-500 uppercase tracking-tight">
                            DOB: {{ $birthDate->format('M d, Y') }}
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-500">
    <span class="font-medium text-slate-700">
        @php
            $birthDate = \Carbon\Carbon::parse($record->birthday);
            $totalMonths = (int)$birthDate->diffInMonths(now());
            $years = (int)$birthDate->diffInYears(now());
        @endphp

        @if($totalMonths < 12)
            {{ $totalMonths }} months {{-- Infants --}}
        @elseif($totalMonths >= 12 && $totalMonths <= 59)
            {{ $totalMonths }} months {{-- Children (1-4 years) --}}
        @else
            {{ $years }} years {{-- Everyone 5 and up --}}
        @endif
    </span> / {{ $record->gender }}
</td>
                    
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $record->address_purok }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ Str::limit($record->diagnosis, 30) }}</td>
                    
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-3">
                            <button type="button" 
                                    data-record='{!! json_encode($record) !!}'
                                    onclick="handleOpenModal(this)"
                                    title="Quick Add Consultation"
                                    class="flex items-center justify-center w-9 h-9 rounded-full bg-green-600 text-white hover:bg-green-700 shadow-md transition-transform hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <a href="{{ route('record.show', $record->id) }}" 
                               title="View Patient History"
                               class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-all hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL SECTION --}}
<div id="quickAddModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50" onclick="closeQuickAdd()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl sm:max-w-lg w-full overflow-hidden">
            <form action="{{ route('record.store') }}" method="POST">
                @csrf
                <input type="hidden" name="first_name" id="modal_first_name">
                <input type="hidden" name="middle_name" id="modal_middle_name">
                <input type="hidden" name="last_name" id="modal_last_name">
                <input type="hidden" name="birthday" id="modal_birthday">
                <input type="hidden" name="gender" id="modal_gender">
                <input type="hidden" name="civil_status" id="modal_civil_status">
                <input type="hidden" name="address_purok" id="modal_address">
                <input type="hidden" name="contact_number" id="modal_contact">
                <input type="hidden" name="consultation_date" value="{{ now()->format('Y-m-d') }}">

                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">New Consultation</h3>
                    <div class="bg-blue-50 rounded-lg p-3 mb-6">
                        <p class="text-[10px] text-blue-600 font-bold uppercase">Target Patient</p>
                        <p id="display_name" class="font-bold text-slate-800"></p>
                        <p id="display_dob" class="text-xs text-slate-500"></p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase block mb-1">Diagnosis</label>
                            <textarea name="diagnosis" rows="3" required class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter current condition..."></textarea>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-xs font-bold text-slate-500 uppercase">Medicines Given</label>
                                <button type="button" onclick="createMedicineRow()" class="text-blue-600 text-xs font-bold hover:underline">+ ADD MEDICINE</button>
                            </div>
                            <div id="medicine-rows-container" class="space-y-2"></div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t flex justify-end gap-3">
                    <button type="button" onclick="closeQuickAdd()" class="text-sm font-bold text-slate-500 hover:text-slate-700">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-md">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Search Logic
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.patient-row');
    
    rows.forEach(row => {
        const name = row.querySelector('.patient-name').innerText.toLowerCase();
        row.style.display = name.includes(searchTerm) ? '' : 'none';
    });
});

// Age Filter Logic
document.getElementById('ageFilter').addEventListener('change', function() {
    const filter = this.value;
    const rows = document.querySelectorAll('.patient-row');
    rows.forEach(row => {
        const years = parseInt(row.getAttribute('data-age-years'));
        const months = parseInt(row.getAttribute('data-age-months'));
        let show = false;
        if (filter === 'all') show = true;
        else if (filter === '0-11' && years === 0 && months <= 11) show = true;
        else if (filter === '12-59' && (years >= 1 && years < 5)) show = true;
        else if (filter === 'senior' && years >= 60) show = true;
        row.style.display = show ? '' : 'none';
    });
});

const medicineDataElement = document.getElementById('medicine-data');
const allMedicines = medicineDataElement ? JSON.parse(medicineDataElement.dataset.medicines) : [];
const container = document.getElementById('medicine-rows-container');
let rowIndex = 0;

function createMedicineRow() {
    const div = document.createElement('div');
    const selectId = `med-select-${rowIndex}`;
    div.className = "flex items-center gap-2 animate-in fade-in slide-in-from-top-1 duration-200";
    
    let options = '<option value="">Search medicine...</option>';
    allMedicines.forEach(med => {
        options += `<option value="${med.id}">${med.name} (Stock: ${med.stock})</option>`;
    });

    div.innerHTML = `
        <div class="flex-1">
            <select id="${selectId}" name="medicines[${rowIndex}][id]" required class="w-full">
                ${options}
            </select>
        </div>
        <input type="number" name="medicines[${rowIndex}][quantity]" placeholder="Qty" required min="1" class="w-20 p-2 border border-gray-200 rounded-lg text-sm h-[42px] outline-none">
        <button type="button" class="text-red-400 hover:text-red-600 text-lg px-1" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.appendChild(div);

    $(`#${selectId}`).select2({
        dropdownParent: $('#quickAddModal'),
        width: '100%'
    });

    rowIndex++;
}

function handleOpenModal(button) {
    try {
        const record = JSON.parse(button.getAttribute('data-record'));
        openQuickAdd(record);
    } catch (e) { console.error("Error parsing patient data:", e); }
}

function openQuickAdd(record) {
    document.getElementById('modal_first_name').value = record.first_name;
    document.getElementById('modal_middle_name').value = record.middle_name || '';
    document.getElementById('modal_last_name').value = record.last_name;
    document.getElementById('modal_birthday').value = record.birthday;
    document.getElementById('modal_gender').value = record.gender;
    document.getElementById('modal_civil_status').value = record.civil_status || 'Single';
    document.getElementById('modal_address').value = record.address_purok;
    document.getElementById('modal_contact').value = record.contact_number || '';
    document.getElementById('display_name').innerText = `${record.first_name} ${record.last_name}`;
    
    const dob = new Date(record.birthday).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('display_dob').innerText = `Date of Birth: ${dob}`;
    
    container.innerHTML = '';
    createMedicineRow();
    
    document.getElementById('quickAddModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
}

function closeQuickAdd() {
    document.getElementById('quickAddModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection