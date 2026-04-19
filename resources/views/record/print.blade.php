<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Record - {{ $record->last_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { margin: 0; }
            body { margin: 1.6cm; background: white; }
        }
        /* Ensure background colors show in PDF */
        .print-bg { 
            background-color: #f8fafc !important; 
            -webkit-print-color-adjust: exact; 
        }
    </style>
</head>
<body class="bg-white font-sans antialiased" onload="window.print()">
    <div class="max-w-4xl mx-auto border border-gray-300 rounded-xl overflow-hidden">
        
        {{-- Header --}}
        <div class="p-8 border-b border-gray-200 flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 uppercase tracking-tight">
                    {{ $record->first_name }} {{ $record->middle_name }} {{ $record->last_name }}
                </h2>
                <p class="text-blue-600 font-bold mt-1">Patient Clinical File</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Date of Consultation</span>
                <p class="text-lg font-semibold text-slate-700">
                    {{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}
                </p>
            </div>
        </div>

        {{-- Demographics Grid --}}
        <div class="grid grid-cols-3 gap-8 p-8 print-bg border-b border-gray-200">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Gender</label>
                <p class="text-slate-800 font-medium">{{ $record->gender }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Age</label>
                <p class="text-slate-800 font-medium">
                    {{ \Carbon\Carbon::parse($record->birthday)->age }} Yrs
                </p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Birthday</label>
                <p class="text-slate-800 font-medium">
                    {{ \Carbon\Carbon::parse($record->birthday)->format('F d, Y') }}
                </p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="grid grid-cols-3 gap-8 p-8 print-bg">
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Civil Status</label>
                <p class="text-slate-800 font-medium">{{ $record->civil_status ?? 'Married' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Contact Number</label>
                <p class="text-slate-800 font-medium">{{ $record->contact_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase">Purok / Address</label>
                <p class="text-slate-800 font-medium">{{ $record->address_purok ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Clinical Data --}}
        <div class="p-8 space-y-10">
            <section>
                <h3 class="flex items-center gap-2 text-sm font-black text-slate-800 uppercase mb-3">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Current Diagnosis
                </h3>
                <div class="p-5 bg-white border border-gray-200 rounded-xl text-slate-700 italic leading-relaxed">
                    {{ $record->diagnosis }}
                </div>
            </section>

            <section>
                <h3 class="flex items-center gap-2 text-sm font-black text-slate-800 uppercase mb-3">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Medicines Given
                </h3>
                <div class="p-5 bg-white border border-gray-200 rounded-xl text-slate-700">
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($record->medicines as $medicine)
                            <li>{{ $medicine->name }} (x{{ $medicine->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        </div>

        {{-- Footer --}}
        <div class="p-8 mt-10 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                Generated via CLINIC OS - {{ now()->format('Y-m-d H:i') }}
            </p>
        </div>
    </div>
</body>
</html>