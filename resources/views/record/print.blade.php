<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Individual Treatment Record</title>
    <style>
        :root {
            --line: #9aa4b2;
            --text: #0f172a;
            --muted: #475569;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Times New Roman", Times, serif;
            color: var(--text);
            background: #e5e7eb;
        }
        .paper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 12mm 12mm 10mm;
            background: #fff;
            border: 1px solid #cbd5e1;
        }
        .top-header {
            display: grid;
            grid-template-columns: minmax(92px, 92px) 1fr minmax(92px, 92px);
            gap: 14px;
            align-items: center;
            margin-bottom: 8px;
        }
        .top-header-spacer {
            width: 92px;
            height: 1px;
        }
        .logo-wrap {
            width: 92px;
            height: 92px;
            border: 1px solid var(--line);
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        .logo-wrap img {
            width: 96%;
            height: 96%;
            object-fit: contain;
        }
        .heading {
            text-align: center;
            line-height: 1.15;
        }
        .heading .small { font-size: 13px; color: #334155; font-weight: 400; }
        .heading .mid { font-size: 18px; font-weight: 400; letter-spacing: .15px; line-height: 1.25; }
        .heading .title { font-size: 44px; font-weight: 400; letter-spacing: .15px; margin-top: 4px; }
        .patient-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 8px 0 6px;
            margin-top: 6px;
        }
        .patient-name { font-size: 24px; font-weight: 400; text-transform: uppercase; }
        .patient-file { font-size: 13px; color: #1d4ed8; font-weight: 400; margin-top: 2px; }
        .consult-date-label { font-size: 10px; text-transform: uppercase; color: var(--muted); font-weight: 400; }
        .consult-date { font-size: 18px; font-weight: 400; color: #1e40af; margin-top: 2px; text-align: right; }
        .grid-info {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            column-gap: 12px;
            row-gap: 6px;
            padding: 8px 0 8px;
            border-bottom: 1px solid var(--line);
        }
        .label { font-size: 9px; font-weight: 400; text-transform: uppercase; color: #64748b; }
        .value { font-size: 16px; font-weight: 400; margin-top: 2px; text-transform: uppercase; }
        .section {
            margin-top: 8px;
        }
        .section-title {
            font-size: 9px;
            font-weight: 400;
            text-transform: uppercase;
            color: #334155;
            margin-bottom: 2px;
        }
        .box {
            border: 1px solid var(--line);
            min-height: 28px;
            border-radius: 3px;
            padding: 6px 8px;
            font-size: 15px;
            line-height: 1.35;
            white-space: pre-wrap;
        }
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 6px;
        }
        .vital {
            border: 1px solid var(--line);
            border-radius: 4px;
            min-height: 44px;
            padding: 5px 5px 6px;
            text-align: center;
        }
        .vital .k {
            font-size: 9px;
            font-weight: 400;
            color: #64748b;
            text-transform: uppercase;
            line-height: 1.05;
        }
        .vital .v {
            font-size: 15px;
            font-weight: 400;
            margin-top: 3px;
            line-height: 1.1;
        }
        .med-list {
            margin: 0;
            padding-left: 18px;
            font-size: 15px;
        }
        .foot {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            font-weight: 400;
            color: #475569;
            letter-spacing: .35px;
        }
        @media print {
            @page { size: A4 portrait; margin: 10mm; }
            html { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            body { background: #fff !important; }
            .paper {
                border: none;
                margin: 0;
                width: auto;
                min-height: auto;
                padding: 2mm 0 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body onload="window.print()">
@php
    $hasValue = fn ($value) => !is_null($value) && trim((string) $value) !== '' && strtoupper(trim((string) $value)) !== 'N/A';
    $clinicLogoPath = \App\Models\Setting::getValue('clinic_logo');
    $clinicLogoUrl = $clinicLogoPath ? asset('storage/' . ltrim($clinicLogoPath, '/')) : null;
@endphp
<div class="paper">
    <div class="top-header">
        <div class="logo-wrap">
            @if($clinicLogoUrl)
                <img src="{{ $clinicLogoUrl }}" alt="Clinic logo">
            @else
                <span style="font-size:11px;font-weight:400;color:#64748b;">LOGO</span>
            @endif
        </div>
        <div class="heading">
            <div class="small">Republic of the Philippines</div>
            <div class="mid">BARANGAY BANILAD HEALTH CENTER</div>
            <div class="small">Dumaguete City</div>
            <div class="title">INDIVIDUAL TREATMENT RECORD</div>
        </div>
        <div class="top-header-spacer" aria-hidden="true"></div>
    </div>

    <div class="patient-head">
        <div>
            <div class="patient-name">{{ $record->first_name }} {{ $record->middle_name }} {{ $record->last_name }}</div>
            <div class="patient-file">Patient Clinical File</div>
        </div>
        <div>
            <div class="consult-date-label">Date of Consultation</div>
            <div class="consult-date">{{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}</div>
        </div>
    </div>

    <div class="grid-info">
        <div><div class="label">Sex</div><div class="value">{{ $record->gender ?: '--' }}</div></div>
        <div><div class="label">Age</div><div class="value">{{ $record->age ?: (\Carbon\Carbon::parse($record->birthday)->age . ' yrs') }}</div></div>
        <div><div class="label">Birthday</div><div class="value">{{ \Carbon\Carbon::parse($record->birthday)->format('M d, Y') }}</div></div>
        <div><div class="label">Civil Status</div><div class="value">{{ $record->civil_status ?: '--' }}</div></div>
        <div><div class="label">Contact Number</div><div class="value">{{ $record->contact_number ?: '--' }}</div></div>
        <div><div class="label">Purok / Address</div><div class="value">{{ $record->address_purok ?: '--' }}</div></div>
    </div>

    <div class="section">
        <div class="section-title">Subjective Findings</div>
        <div class="box">{{ $record->subjective ?: 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Vitals</div>
        <div class="vitals-grid">
            <div class="vital"><div class="k">Temp</div><div class="v">{{ $hasValue($record->temp) ? $record->temp . ' °C' : '--' }}</div></div>
            <div class="vital"><div class="k">BP</div><div class="v">{{ $hasValue($record->bp) ? $record->bp : '--' }}</div></div>
            <div class="vital"><div class="k">Pulse</div><div class="v">{{ $hasValue($record->pr) ? $record->pr . ' bpm' : '--' }}</div></div>
            <div class="vital"><div class="k">Resp</div><div class="v">{{ $hasValue($record->rr) ? $record->rr . ' cpm' : '--' }}</div></div>
            <div class="vital"><div class="k">Weight</div><div class="v">{{ $hasValue($record->weight) ? $record->weight . ' kg' : '--' }}</div></div>
            <div class="vital"><div class="k">Height</div><div class="v">{{ $hasValue($record->height) ? $record->height . ' cm' : '--' }}</div></div>
            <div class="vital"><div class="k">BMI</div><div class="v">{{ $hasValue($record->bmi) ? $record->bmi : '--' }}</div></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Objective Findings</div>
        <div class="box">{{ $record->objective ?: 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Current Diagnosis</div>
        <div class="box">{{ $record->diagnosis ?: 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Treatment Plan</div>
        <div class="box">{{ $record->follow_up_recommendation ?: 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Medicine</div>
        <div class="box">
            @if($record->medicines && $record->medicines->count() > 0)
                <ul class="med-list">
                    @foreach($record->medicines as $medicine)
                        <li>{{ $medicine->name }} (x{{ $medicine->pivot->quantity }})</li>
                    @endforeach
                </ul>
                @if($hasValue($record->medicines_given))
                    <div style="margin-top:6px;font-size:12px;color:#92400e;white-space:pre-wrap;">
                        {{ $record->medicines_given }}
                    </div>
                @endif
            @elseif($hasValue($record->medicines_given))
                {{ $record->medicines_given }}
            @else
                N/A
            @endif
        </div>
    </div>

    <div class="foot">GENERATED VIA CLINIC OS - {{ now()->format('Y-m-d H:i') }}</div>
</div>
</body>
</html>
