@php
    $hasValue = $hasValue ?? fn ($value) => !is_null($value) && trim((string) $value) !== '' && strtoupper(trim((string) $value)) !== 'N/A';
    $portalPrefix = $portalPrefix ?? null;
    $routeIndex = $portalPrefix ? $portalPrefix . '.record.index' : 'record.index';
    $routePendingName = $portalPrefix ? $portalPrefix . '.pending.index' : null;
    $routePending = ($routePendingName && Route::has($routePendingName)) ? $routePendingName : null;
    $routeShow = $portalPrefix ? $portalPrefix . '.record.show' : 'record.show';
    $routePrint = $portalPrefix ? $portalPrefix . '.record.print' : 'record.print';
@endphp
<style>
    .consultation-record-scope {
        font-family: "Segoe UI", "Segoe UI Variable", Tahoma, Geneva, Verdana, sans-serif;
        color: #1e293b;
    }
    .consultation-record-scope .panel-card {
        border: 1px solid #dbe4f0;
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }
    .consultation-record-scope .fld {
        border-radius: 0.625rem;
        background: #f8fbff;
        border: 1px solid #dce7f7;
        padding: 0.62rem 0.8rem;
        font-size: 0.92rem;
        color: #0f172a;
        line-height: 1.35;
    }
    .consultation-record-scope .soap-badge {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 0.25rem;
        background: #16a34a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .consultation-record-scope .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #1e293b;
    }
    .consultation-record-scope .field-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
    }

    /* Dark mode: align with Individual Treatment Record (dark panels, green accents) */
    .dark .consultation-record-scope {
        color: #e2e8f0;
    }
    .dark .consultation-record-scope .panel-card {
        border: 1px solid #22543d !important;
        background: #0f1d1a !important;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.35);
    }
    .dark .consultation-record-scope .fld {
        background: #111827 !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    .dark .consultation-record-scope .section-title {
        color: #22c55e !important;
    }
    .dark .consultation-record-scope .field-label {
        color: #94a3b8 !important;
    }
    .dark .consultation-record-scope .consultation-record-date-value {
        color: #f1f5f9 !important;
    }
    .dark .consultation-record-scope .rounded-xl.border.border-slate-100.bg-slate-50 {
        background-color: #111827 !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope .rounded-xl.border.border-green-100.bg-green-50 {
        background-color: #14532d !important;
        border-color: #166534 !important;
    }
    .dark .consultation-record-scope .text-green-600 {
        color: #86efac !important;
    }
    .dark .consultation-record-scope .text-green-800 {
        color: #ecfdf5 !important;
    }
    .dark .consultation-record-scope .border-slate-200.bg-slate-50,
    .dark .consultation-record-scope .rounded-2xl.border.border-slate-200.bg-slate-50 {
        background-color: #0f172a !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope .border-dashed.border-slate-300.bg-slate-50\/50 {
        background-color: #111827 !important;
        border-color: #475569 !important;
    }
    .dark .consultation-record-scope .overflow-hidden.rounded-xl.border.border-slate-200 {
        border-color: #334155 !important;
        background-color: #111827 !important;
    }
    .dark .consultation-record-scope table thead tr {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope table thead th {
        color: #94a3b8 !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope table tbody td {
        color: #e2e8f0 !important;
        border-color: #1e293b !important;
    }
    .dark .consultation-record-scope table tbody tr {
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope .border-t.border-amber-100,
    .dark .consultation-record-scope .border-t.border-amber-200 {
        border-color: #92400e !important;
    }
    .dark .consultation-record-scope .bg-amber-50,
    .dark .consultation-record-scope .bg-amber-50\/60 {
        background-color: #422006 !important;
    }
    .dark .consultation-record-scope .text-amber-800 {
        color: #fcd34d !important;
    }
    .dark .consultation-record-scope .rounded-xl.border.border-green-100.bg-green-50\/80 {
        background-color: #14532d !important;
        border-color: #166534 !important;
    }
    .dark .consultation-record-scope .rounded-lg.border.border-white\/80.bg-white\/90 {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope a.block.rounded-xl.border.p-3 {
        background-color: #1e293b !important;
    }
    .dark .consultation-record-scope a.block.rounded-xl.border.border-green-500 {
        background-color: #14532d !important;
        border-color: #22c55e !important;
    }
    .dark .consultation-record-scope a.block.rounded-xl.border.border-transparent {
        background-color: #0f172a !important;
    }
    .dark .consultation-record-scope a.block.rounded-xl.border.border-transparent:hover {
        background-color: #1e293b !important;
    }
    .dark .consultation-record-scope .block.overflow-hidden.rounded-xl.border.border-slate-200.bg-slate-50 {
        background-color: #111827 !important;
        border-color: #334155 !important;
    }
    .dark .consultation-record-scope .truncate.px-2.py-1.bg-white {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
    }
    .dark .consultation-record-scope a.rounded-lg.border.border-slate-300.bg-slate-100 {
        background-color: #1e293b !important;
        border-color: #475569 !important;
        color: #f1f5f9 !important;
    }
    .dark .consultation-record-scope a.rounded-lg.border.border-slate-300.bg-slate-100:hover {
        background-color: #334155 !important;
    }
    .dark .consultation-record-scope .rounded-2xl.border.border-slate-200.bg-slate-50 h2 {
        color: #94a3b8 !important;
    }
    .dark .consultation-record-scope .text-slate-400.italic {
        color: #94a3b8 !important;
    }
    .dark .consultation-record-scope svg.text-slate-300 {
        color: #64748b !important;
    }
    .dark .consultation-record-scope .border-dashed.border-slate-300 .text-slate-500 {
        color: #cbd5e1 !important;
    }
    .dark .consultation-record-scope .fld .text-slate-400 {
        color: #94a3b8 !important;
    }

    @media print {
        .consultation-record-scope .no-print { display: none !important; }
        .consultation-record-scope .consultation-grid {
            display: block !important;
        }
        .consultation-record-scope .consultation-grid > div {
            width: 100% !important;
            margin-bottom: 0.75rem !important;
        }
    }
</style>

<div class="consultation-record-scope max-w-7xl mx-auto py-6 px-4">
    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between no-print">
        <div class="flex items-start gap-3 min-w-0">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-600 text-white" aria-hidden="true">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                        d="M9 12h6m-8 4h11a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12m5 4v-8M9 21l3-3m0 0l3 3m-3-3v8"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-[1.75rem] font-bold uppercase tracking-tight text-slate-900">Consultation Record</h1>
                <p class="text-slate-600 text-sm mt-0.5 font-medium">Detailed Patient Encounter</p>
            </div>
        </div>
        <div class="flex flex-col items-stretch sm:items-end gap-3 shrink-0">
            <div class="flex flex-wrap items-center justify-end gap-2">
                <a href="{{ route($routePrint, $record->id) }}" target="_blank"
                    class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition">
                    Print Record
                </a>
                @php
                    $fromPending = request()->query('from') === 'pending';
                    $backUrl = ($fromPending && $routePending) ? route($routePending) : route($routeIndex);
                @endphp
                <a href="{{ $backUrl }}"
                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">
                    <span aria-hidden="true">←</span> Back
                </a>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Consultation Date</p>
                <p class="consultation-record-date-value text-base font-semibold text-slate-900">{{ \Carbon\Carbon::parse($record->consultation_date)->format('m/d/Y') }}</p>
            </div>
        </div>
    </div>

    <div class="consultation-grid grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-start">
        {{-- Left: patient demographics + history --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="panel-card p-5">
                <div class="space-y-3">
                    <div>
                        <p class="field-label mb-1">Full Name</p>
                        <div class="fld uppercase">{{ $record->last_name }}, {{ $record->first_name }} {{ $record->middle_name }}</div>
                    </div>
                    <div>
                        <p class="field-label mb-1">Birthday</p>
                        <div class="fld flex items-center gap-2">
                            <span class="text-slate-400" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            {{ $record->birthday ? \Carbon\Carbon::parse($record->birthday)->format('M d, Y') : '—' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="field-label mb-1">Age</p>
                            <div class="fld">{{ is_numeric($record->age) ? round($record->age) . ' yrs' : ($record->age ?: '—') }}</div>
                        </div>
                        <div>
                            <p class="field-label mb-1">Gender</p>
                            <div class="fld">{{ $record->gender }}</div>
                        </div>
                    </div>
                    <div>
                        <p class="field-label mb-1">Cellphone Number</p>
                        <div class="fld">{{ $record->contact_number ?: 'N/A' }}</div>
                    </div>
                    <div>
                        <p class="field-label mb-1">Civil Status</p>
                        <div class="fld">{{ $record->civil_status }}</div>
                    </div>
                    <div>
                        <p class="field-label mb-1">Address</p>
                        <div class="fld uppercase">{{ $record->address_purok }}</div>
                    </div>
                </div>
            </div>

            @if(isset($history) && $history->count() > 0)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 no-print">
                    <h2 class="text-[11px] font-semibold uppercase tracking-widest text-slate-500 mb-3">History</h2>
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                        @foreach($history as $visit)
                            <a href="{{ route($routeShow, $visit->id) }}"
                                class="block rounded-xl border p-3 transition {{ $visit->id == $record->id ? 'border-green-500 bg-white shadow-sm' : 'border-transparent bg-white/60 hover:bg-white' }}">
                                <p class="text-xs font-semibold {{ $visit->id == $record->id ? 'text-green-700' : 'text-slate-600' }}">
                                    {{ \Carbon\Carbon::parse($visit->consultation_date)->format('M d, Y') }}
                                </p>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $visit->diagnosis }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Middle: V, S, A, P --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="panel-card p-5">
                <div class="flex items-center gap-2 mb-4">
                    <span class="soap-badge">V</span>
                    <h2 class="section-title uppercase">Vitals</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">T</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">
                            {{ $hasValue($record->display_temp ?? null) ? $record->display_temp : '—' }}@if($hasValue($record->display_temp ?? null))<span class="text-xs"> °C</span>@endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">BP</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $record->display_bp ?: '—' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">PR</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">
                            {{ $hasValue($record->display_pr ?? null) ? $record->display_pr : '—' }}@if($hasValue($record->display_pr ?? null))<span class="text-xs"> bpm</span>@endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">RR</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">
                            {{ $hasValue($record->display_rr ?? null) ? $record->display_rr : '—' }}@if($hasValue($record->display_rr ?? null))<span class="text-xs"> cpm</span>@endif
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mt-2">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">WT</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">
                            {{ $hasValue($record->display_weight ?? null) ? $record->display_weight : '—' }}@if($hasValue($record->display_weight ?? null))<span class="text-xs"> kg</span>@endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-slate-500">HT</p>
                        <p class="text-sm font-semibold text-slate-800 mt-0.5">
                            {{ $hasValue($record->display_height ?? null) ? $record->display_height : '—' }}@if($hasValue($record->display_height ?? null))<span class="text-xs"> cm</span>@endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-green-100 bg-green-50 px-2 py-2 text-center">
                        <p class="text-[9px] font-semibold uppercase text-green-600">BMI</p>
                        <p class="text-sm font-semibold text-green-800 mt-0.5">{{ $record->display_bmi ?: '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="panel-card p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="soap-badge">S</span>
                    <h2 class="section-title uppercase">Subjective Findings</h2>
                </div>
                <div class="fld min-h-[4rem] whitespace-pre-line text-slate-700">{{ $record->subjective ?: '—' }}</div>
            </div>

            <div class="panel-card p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="soap-badge">A</span>
                    <h2 class="section-title uppercase">Assessment / Diagnosis</h2>
                </div>
                <div class="fld min-h-[3.5rem] whitespace-pre-line text-slate-800">{{ $record->diagnosis }}</div>
            </div>

            <div class="panel-card p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="soap-badge">P</span>
                    <h2 class="section-title uppercase">Plan / Medicines</h2>
                </div>
                @if($record->follow_up_recommendation)
                    <div class="fld mb-3 whitespace-pre-line text-slate-700">{{ $record->follow_up_recommendation }}</div>
                @endif
                @if($record->medicines && $record->medicines->count() > 0)
                    <div class="overflow-hidden rounded-xl border border-slate-200 {{ $record->medicinesGivenReleaseFooter() ? 'flex min-h-[7rem] flex-col' : '' }}">
                        <div class="{{ $record->medicinesGivenReleaseFooter() ? 'min-h-0 flex-1' : '' }}">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50 text-left">
                                        <th class="px-3 py-2 font-semibold text-slate-600">Medicine</th>
                                        <th class="px-3 py-2 font-semibold text-slate-600 text-right w-24">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($record->medicines as $medicine)
                                        <tr class="border-b border-slate-100 last:border-0">
                                            <td class="px-3 py-2 text-slate-800">{{ $medicine->name }}</td>
                                            <td class="px-3 py-2 text-right text-slate-700">{{ $medicine->pivot->quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($record->medicinesGivenSupplementaryNote())
                                <div class="border-t border-amber-100 bg-amber-50/60 px-3 py-2 text-xs font-semibold text-amber-800 whitespace-pre-line text-left">
                                    {{ $record->medicinesGivenSupplementaryNote() }}
                                </div>
                            @endif
                        </div>
                        @if($record->medicinesGivenReleaseFooter())
                            <div class="mt-auto border-t border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800 whitespace-pre-line text-right">
                                {{ $record->medicinesGivenReleaseFooter() }}
                            </div>
                        @endif
                    </div>
                @elseif($hasValue($record->medicines_given))
                    <div class="overflow-hidden rounded-xl border border-slate-200 {{ $record->medicinesGivenReleaseFooter() ? 'flex min-h-[4rem] flex-col' : '' }}">
                        <div class="px-3 py-2 {{ $record->medicinesGivenReleaseFooter() ? 'min-h-0 flex-1' : '' }}">
                            @if($record->medicinesGivenSupplementaryNote())
                                <div class="fld whitespace-pre-line text-slate-700">{{ $record->medicinesGivenSupplementaryNote() }}</div>
                            @endif
                        </div>
                        @if($record->medicinesGivenReleaseFooter())
                            <div class="mt-auto border-t border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-800 whitespace-pre-line text-right">
                                {{ $record->medicinesGivenReleaseFooter() }}
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No medications prescribed.</p>
                @endif

                @if(!empty($consultationTeam ?? []))
                    <div class="mt-4 rounded-xl border border-green-100 bg-green-50/80 p-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-green-600 mb-2">Consultation Team</p>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            @foreach(($consultationTeam ?? []) as $member)
                                @php
                                    $memberValue = trim((string) $member);
                                    $memberLower = strtolower($memberValue);
                                    $roleLabel = str_starts_with($memberLower, 'dr.') ? 'Doctor' : (str_starts_with($memberLower, 'nurse') ? 'Nurse' : 'BHW');
                                @endphp
                                <div class="rounded-lg border border-white/80 bg-white/90 px-2 py-1.5">
                                    <p class="text-[9px] font-semibold uppercase text-slate-400">{{ $roleLabel }}</p>
                                    <p class="text-xs font-semibold text-slate-800">{{ $memberValue }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: O, L --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="panel-card p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="soap-badge">O</span>
                    <h2 class="section-title uppercase">Objective Findings</h2>
                </div>
                <div class="fld min-h-[5rem] whitespace-pre-line text-slate-700 border-dashed">{{ $record->objective ?: '—' }}</div>
            </div>

            <div class="panel-card p-5">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="soap-badge">L</span>
                        <h2 class="section-title uppercase">Laboratory Upload</h2>
                    </div>
                    @if($record->laboratoryFiles && $record->laboratoryFiles->count() > 0)
                        <span class="text-[10px] font-semibold uppercase text-slate-400">{{ $record->laboratoryFiles->count() }} file(s)</span>
                    @endif
                </div>
                @if($record->laboratoryFiles && $record->laboratoryFiles->count() > 0)
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                        @foreach($record->laboratoryFiles as $file)
                            <a href="{{ asset('storage/'.$file->path) }}" target="_blank"
                               class="block overflow-hidden rounded-xl border border-slate-200 bg-slate-50 hover:border-green-300 transition">
                                <img src="{{ asset('storage/'.$file->path) }}"
                                     alt="{{ $file->original_name ?? 'Lab' }}"
                                     class="h-24 w-full object-cover"
                                     loading="lazy">
                                <div class="truncate px-2 py-1 text-[10px] text-slate-600 bg-white">{{ $file->original_name ?? 'File' }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-slate-300 bg-slate-50/50 py-10 text-center">
                        <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3"/>
                        </svg>
                        <p class="text-sm text-slate-500">No laboratory files uploaded for this visit.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
