<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Record | CLINIC OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        {{-- SIDEBAR --}}
        <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col shadow-xl z-10">
            <div class="p-6 text-xl font-bold border-b border-slate-800 flex items-center gap-2">
                <span class="text-blue-500 font-bold">+</span> CLINIC OS
            </div>
            
            <nav class="mt-6 flex-1 px-4 space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-800/50 text-white border-l-4 border-blue-500 shadow-sm' : 'text-slate-400 hover:bg-slate-800/30 hover:text-white' }}">
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                
                {{-- Clinic Records (Active state for viewing/showing records) --}}
                <a href="{{ route('record.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('record.*') && !request()->routeIs('record.create') ? 'bg-slate-800/50 text-white border-l-4 border-blue-500 shadow-sm' : 'text-slate-400 hover:bg-slate-800/30 hover:text-white' }}">
                    <span class="font-medium text-sm">Clinic Records</span>
                </a>
                
                {{-- Inventory Medicine --}}
                <a href="{{ route('medicines.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('medicines.*') ? 'bg-slate-800/50 text-white border-l-4 border-blue-500 shadow-sm' : 'text-slate-400 hover:bg-slate-800/30 hover:text-white' }}">
                    <span class="font-medium text-sm">Inventory Medicine</span>
                </a>

                <div class="pt-4 mt-4 border-t border-slate-800">
                    {{-- Add New Consultation --}}
                    <a href="{{ route('record.create') }}" 
                       class="flex items-center gap-2 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('record.create') ? 'bg-slate-800/50 text-white border-l-4 border-blue-500 shadow-sm' : 'text-slate-400 hover:bg-slate-800/30 hover:text-white' }}">
                        <span class="text-blue-500 font-bold">+</span>
                        <span class="font-medium text-sm">Add New Consultation</span>
                    </a>
                </div>
            </nav>
            
            {{-- Logout Section --}}
            <div class="p-4 border-t border-slate-800 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-red-400 hover:bg-slate-800/50 rounded-lg transition-all duration-200 group">
                        <svg class="w-5 h-5 transition-colors duration-200 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-8 overflow-y-auto bg-slate-50">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                {{-- Header Section --}}
                <div class="p-8 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-800 capitalize">
                            {{ $record->first_name }} {{ $record->middle_name }} {{ $record->last_name }}
                        </h2>
                        <p class="text-blue-600 font-medium mt-1">Patient Clinical File</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Date of Consultation</span>
                        <p class="text-lg font-semibold text-slate-700">
                            {{ \Carbon\Carbon::parse($record->consultation_date)->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                {{-- Primary Info Grid --}}
                <div class="grid grid-cols-3 gap-8 p-8 bg-slate-50/50 border-b border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Gender</label>
                        <p class="text-slate-800 font-medium">{{ $record->gender }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Age</label>
                        <p class="text-slate-800 font-medium">
                            @php
                                $birthday = \Carbon\Carbon::parse($record->birthday);
                                $years = $birthday->diffInYears(now());
                                $months = $birthday->diffInMonths(now());
                            @endphp
                            {{ $years > 0 ? (int)$years . ' Yrs' : (int)$months . ' Months' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Birthday</label>
                        <p class="text-slate-800 font-medium">
                            {{ \Carbon\Carbon::parse($record->birthday)->format('F d, Y') }}
                        </p>
                    </div>
                </div>

                {{-- Contact Info Grid --}}
                <div class="grid grid-cols-3 gap-8 p-8 bg-slate-50/50">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Civil Status</label>
                        <p class="text-slate-800 font-medium">{{ $record->civil_status ?? 'N/A' }}</p>
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

                <div class="p-8 space-y-8">
                    {{-- Diagnosis --}}
                    <section>
                        <h3 class="flex items-center gap-2 text-sm font-bold text-slate-800 uppercase mb-3">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span> Current Diagnosis
                        </h3>
                        <div class="p-5 bg-white border border-gray-200 rounded-xl text-slate-700 leading-relaxed italic">
                            {{ $record->diagnosis }}
                        </div>
                    </section>

                    {{-- Medicines --}}
                    <section>
                        <h3 class="flex items-center gap-2 text-sm font-bold text-slate-800 uppercase mb-3">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span> Medicines Given
                        </h3>
                        <div class="p-5 bg-white border border-gray-200 rounded-xl text-slate-700">
                            @if($record->medicines->count() > 0)
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($record->medicines as $medicine)
                                        <li>{{ $medicine->name }} (x{{ $medicine->pivot->quantity }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400 italic">No medicines recorded for this visit.</span>
                            @endif
                        </div>
                    </section>
                </div>

                {{-- Consultation History --}}
                <div class="p-8 bg-slate-50 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-slate-800 uppercase mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Past Consultations ({{ $history->count() }})
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($history as $visit)
                            <div class="p-4 bg-white border rounded-xl shadow-sm flex justify-between items-center {{ $visit->id == $record->id ? 'ring-2 ring-blue-500' : '' }}">
                                <div>
                                    <p class="font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($visit->consultation_date)->format('M d, Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">Diagnosis: {{ $visit->diagnosis }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($visit->id == $record->id)
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-1 rounded uppercase">Current View</span>
                                    @else
                                        <a href="{{ route('record.show', $visit->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">View History →</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Actions --}}
                <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-4">
                    <a href="{{ route('record.index') }}" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 font-semibold hover:bg-white transition">Back to List</a>
                    
                    <a href="{{ route('record.print', $record->id) }}" target="_blank" class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 font-semibold hover:bg-white transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Print PDF
                    </a>
                    
                    <a href="{{ route('record.edit', $record->id) }}" class="px-5 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-md transition">
                        Edit Entry
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>