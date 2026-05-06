{{-- resources/views/medicines/index.blade.php --}}
@extends('layouts.app')

@section('content')
@include('partials.medicine-expiry-picker-assets')
@php
    $isDoctorRole = (auth()->user()->role ?? null) === 'doctor';
@endphp
{{-- Load Alpine.js for floating overlays --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-4 py-6" x-data="{ openDetails: null, openAddLot: null, openStacks: null }">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Inventory Medicine</h1>
            <p class="text-gray-500 text-sm mt-1">One row per medicine; the <span class="text-slate-600 font-medium">chevron</span> lists every releasable batch (qty &amp; expiry, soonest first). Open <span class="text-slate-600 font-medium">View</span> for full history.</p>
        </div>
        @unless($isDoctorRole)
            <a href="{{ route('medicines.create') }}"
                class="inline-flex items-center justify-center gap-1.5 shrink-0 whitespace-nowrap px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-sm hover:bg-blue-700 transition self-start md:self-auto">
                <span class="text-base leading-none font-bold" aria-hidden="true">+</span>
                <span>Add Medicine</span>
            </a>
        @endunless
    </div>

    {{-- Search Bar --}}
    <div class="mb-6 relative max-w-md">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
        </span>
        <input type="text" id="inventorySearch" onkeyup="runFilters()" placeholder="Search medicine..." class="pl-10 pr-4 py-3 w-full rounded-xl border border-gray-200 outline-none shadow-sm focus:border-blue-500 transition">
    </div>

    @if(($expiringSoonMedicines ?? collect())->isNotEmpty())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-amber-800">Medicines Expiring Soon</h2>
                    <p class="text-xs text-amber-700">Active stocks that will expire within 30 days.</p>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-800">
                    {{ $expiringSoonMedicines->count() }} item(s)
                </span>
            </div>
            <div class="mt-3 grid md:grid-cols-2 gap-2">
                @foreach($expiringSoonMedicines as $item)
                    <div class="rounded-lg bg-white border border-amber-100 px-3 py-2 flex justify-between items-center gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-[11px] text-slate-500">Expires {{ $item['expiration_date']->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-bold text-amber-700">{{ $item['days_left'] }} day(s) left</p>
                            <p class="text-[11px] text-slate-500">Stock: {{ $item['total_stock'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 table-fixed" id="inventoryTable">
            <thead class="bg-slate-50">
                <tr>
                    {{-- Set a fixed width for the name to force wrapping --}}
                    <th class="w-1/3 px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Medicine Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" title="Most recent receipt among batches that currently have releasable stock (or latest receipt overall if none).">Date Received</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" title="Units at the soonest expiry date only (same date as Expiry column); multiple batches on that day are summed together, not across later expiries.">Quantity</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" title="Soonest expiry among releasable batches (FIFO/FEFO &quot;next&quot; batch).">Expiry Date</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            @php
                $todayInv = \Illuminate\Support\Carbon::today();
                $medicinesByName = $medicines->groupBy('name');
                $sortedNames = $medicinesByName->keys()->sortBy(function ($name) use ($medicinesByName, $todayInv) {
                    $lotsForName = $medicinesByName[$name];
                    $available = $lotsForName->filter(function ($l) use ($todayInv) {
                        return (int) $l->stock > 0 && (!$l->expiration_date || $l->expiration_date->gte($todayInv));
                    });
                    $hasActive = $available->isNotEmpty();
                    $nearestExpiry = $available->filter(fn ($l) => $l->expiration_date)->sortBy(fn ($l) => $l->expiration_date->timestamp)->first();
                    $nearestTs = $nearestExpiry ? $nearestExpiry->expiration_date->timestamp : PHP_INT_MAX;

                    return sprintf('%d-%010d-%s', $hasActive ? 0 : 1, $nearestTs, $name);
                })->values();
            @endphp
            @forelse($sortedNames as $name)
                @php
                    $grpKey = (string) $loop->index;
                    $stackKey = 'inv-stack-grp-' . $grpKey;
                    $lots = $medicinesByName[$name] ?? collect();
                    $availableLots = $lots->filter(function ($l) use ($todayInv) {
                        return (int) $l->stock > 0 && (!$l->expiration_date || $l->expiration_date->gte($todayInv));
                    });
                    $arrivalsActive = $availableLots->filter(fn ($l) => $l->arrival_date !== null);
                    $summaryArrivalLot = $arrivalsActive->isNotEmpty()
                        ? $arrivalsActive->sortByDesc(fn ($l) => $l->arrival_date->timestamp)->first()
                        : null;
                    if (!$summaryArrivalLot) {
                        $summaryArrivalLot = $lots->filter(fn ($l) => $l->arrival_date !== null)->sortByDesc(fn ($l) => $l->arrival_date->timestamp)->first();
                    }
                    $nearestExpiryLot = $availableLots->filter(fn ($l) => $l->expiration_date)->sortBy(fn ($l) => $l->expiration_date->timestamp)->first();
                    if ($nearestExpiryLot && $nearestExpiryLot->expiration_date) {
                        $nextExpiryDay = $nearestExpiryLot->expiration_date->format('Y-m-d');
                        $qtyAtNextExpiry = (int) $availableLots->filter(function ($l) use ($nextExpiryDay) {
                            return $l->expiration_date && $l->expiration_date->format('Y-m-d') === $nextExpiryDay;
                        })->sum('stock');
                    } else {
                        $qtyAtNextExpiry = (int) $availableLots->sum('stock');
                    }
                    $historyLots = $lots->sortBy(function ($l) use ($todayInv) {
                        $isExpired = $l->expiration_date && $l->expiration_date->lt($todayInv);
                        $isHistoryOnly = (int) $l->stock <= 0 || $isExpired;
                        $expiryRank = $l->expiration_date ? $l->expiration_date->timestamp : PHP_INT_MAX;
                        $arrivalRank = $l->arrival_date ? $l->arrival_date->timestamp : PHP_INT_MAX;

                        return sprintf('%d-%010d-%010d', $isHistoryOnly ? 1 : 0, $expiryRank, $arrivalRank);
                    })->values();
                    $releaseStacks = $availableLots
                        ->sortBy(function ($l) {
                            $exp = $l->expiration_date ? $l->expiration_date->timestamp : PHP_INT_MAX;
                            $arr = $l->arrival_date ? $l->arrival_date->timestamp : PHP_INT_MAX;

                            return sprintf('%010d-%010d', $exp, $arr);
                        })
                        ->values();
                @endphp
                <tbody class="inventory-lots divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition inventory-row">
                    <td class="px-6 py-4 whitespace-normal">
                        <button type="button"
                            @click="openStacks = openStacks === '{{ $stackKey }}' ? null : '{{ $stackKey }}'"
                            class="group w-full text-left flex items-start gap-3 rounded-xl border-2 border-transparent px-3 py-2 -mx-1 -my-1 transition hover:border-blue-200 hover:bg-blue-50/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            :class="openStacks === '{{ $stackKey }}' ? 'border-blue-500 bg-white shadow-sm' : ''"
                            :aria-expanded="openStacks === '{{ $stackKey }}' ? 'true' : 'false'"
                            aria-controls="panels-{{ $stackKey }}">
                            <span class="mt-0.5 shrink-0 text-blue-600 transition-transform duration-200"
                                :class="openStacks === '{{ $stackKey }}' ? 'rotate-180' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                            <span class="min-w-0 flex flex-col items-start">
                                <span class="text-sm font-bold text-slate-800 medicine-name break-words group-hover:text-blue-900">{{ $name }}</span>
                                @if($lots->count() > 1)
                                    <span class="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400 text-left">{{ $lots->count() }} batch(es)</span>
                                @endif
                            </span>
                        </button>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                        @if($summaryArrivalLot && $summaryArrivalLot->arrival_date)
                            {{ $summaryArrivalLot->arrival_date->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $qtyAtNextExpiry <= 0 ? 'text-red-500' : 'text-slate-700' }}">
                        {{ $qtyAtNextExpiry }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        @if($nearestExpiryLot && $nearestExpiryLot->expiration_date)
                            {{ $nearestExpiryLot->expiration_date->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </td>
                    
                    {{-- w-px and whitespace-nowrap prevents the buttons from disappearing or stacking --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center w-px">
                        <div class="flex justify-center items-center gap-2">
                            <button type="button" @click="openDetails = '{{ $grpKey }}'" title="View details" aria-label="View details" class="inline-flex items-center justify-center p-2.5 bg-[#E9F3F1] text-[#2D8A80] rounded-xl hover:opacity-80 transition shadow-sm shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>

                            @unless($isDoctorRole)
                                <button type="button" @click="openAddLot = '{{ $grpKey }}'" title="Add stock" aria-label="Add stock" class="p-2 bg-[#ECFDF5] text-[#10B981] rounded-xl hover:opacity-80 transition shadow-sm shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </button>
                            @endunless

                            @unless($isDoctorRole)
                                <form action="{{ route('medicines.destroy_group') }}" method="POST" onsubmit="return confirm({{ json_encode('Delete ALL batches for '.$name.'? This cannot be undone.') }})" class="m-0">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <button type="submit" title="Delete all batches for this medicine" aria-label="Delete all batches for this medicine" class="p-2 bg-[#FFF1F1] text-[#FF5C5C] rounded-xl hover:opacity-80 transition shadow-sm shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endunless
                        </div>

                        {{-- MODAL 1: VIEW DETAILS (all batches sharing this medicine name) --}}
                        <div x-show="openDetails == '{{ $grpKey }}'" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
                            <div @click.away="openDetails = null" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-hidden flex flex-col text-left whitespace-normal">
                                <div class="px-10 py-8 border-b border-gray-50 flex justify-between items-center shrink-0">
                                    <div class="pr-8">
                                        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight break-words">{{ $name }}</h2>
                                        <p class="text-gray-400 text-xs font-bold mt-1 uppercase tracking-widest">Inventory History</p>
                                    </div>
                                    <button type="button" @click="openDetails = null" class="text-gray-300 hover:text-gray-600 transition text-2xl font-bold">✕</button>
                                </div>
                                <div class="p-10 overflow-y-auto space-y-6 bg-gray-50/30">
                                    @forelse($historyLots as $hist)
                                    @php
                                        $isExpiredHist = $hist->expiration_date && $hist->expiration_date->lt($todayInv);
                                        $isHistoryOnlyHist = (int) $hist->stock <= 0 || $isExpiredHist;
                                    @endphp
                                    <div class="bg-white border {{ $isHistoryOnlyHist ? 'border-gray-50 bg-gray-50/50' : 'border-gray-100' }} rounded-[2rem] p-8 flex items-center gap-8 relative shadow-sm transition hover:shadow-md mb-6 last:mb-0 {{ ($nearestExpiryLot && $hist->id === $nearestExpiryLot->id) ? 'ring-2 ring-blue-200' : '' }}">

                                        <div class="grow grid grid-cols-3 gap-8 {{ $isHistoryOnlyHist ? 'opacity-60' : '' }}">
                                            <div class="text-left">
                                                <h4 class="font-black text-gray-800 text-lg uppercase">{{ $hist->batch_number ?? 'LOT-'.$hist->id }}</h4>
                                                <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest block mt-1">
                                                    Received: @if($hist->arrival_date) {{ $hist->arrival_date->format('M d, Y') }} @else N/A @endif
                                                </span>
                                                @if((int) $hist->stock <= 0)
                                                    <span class="text-[10px] text-red-400 font-black uppercase mt-1 block italic">Out of Stock</span>
                                                @elseif($isExpiredHist)
                                                    <span class="text-[10px] text-amber-500 font-black uppercase mt-1 block italic">Expired</span>
                                                @endif
                                            </div>
                                            <div class="text-left text-sm font-bold text-gray-600 flex flex-col justify-center">
                                                <p class="text-[9px] text-gray-400 uppercase tracking-widest">Expiry</p>
                                                <p>@if($hist->expiration_date) {{ $hist->expiration_date->format('M d, Y') }} @else N/A @endif</p>
                                            </div>
                                            <div class="text-right font-black text-slate-800 flex flex-col justify-center">
                                                <p class="text-[9px] text-gray-400 uppercase tracking-widest">Current Stock</p>
                                                <span>{{ $hist->stock }} <small class="text-xs text-slate-400 tracking-normal">units</small></span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2 pl-6 border-l border-gray-50">
                                            <a href="{{ route('medicines.edit', $hist) }}" class="px-5 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase text-center hover:bg-blue-100 transition">View</a>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="bg-white border-2 border-dashed border-gray-200 rounded-[2rem] p-12 text-center w-full">
                                        <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">No stocks recorded</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- MODAL 2: ADD NEW LOT --}}
                        <div x-show="openAddLot == '{{ $grpKey }}'" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;" x-transition.opacity>
                            <div @click.away="openAddLot = null" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-left whitespace-normal">
                                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900">Add New Stock</h2>
                                        <p class="text-gray-500 text-xs font-bold uppercase break-words">{{ $name }}</p>
                                    </div>
                                    <button type="button" @click="openAddLot = null" class="text-gray-400 hover:text-gray-600 text-xl font-bold">✕</button>
                                </div>
                                <form action="{{ route('medicines.store') }}" method="POST" class="p-8 space-y-5">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $name }}">
                                    <div><label class="block text-xs font-black text-gray-400 uppercase mb-1 tracking-widest">Stock Number</label><input type="text" name="batch_number" placeholder="ABC-000" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none shadow-sm" required></div>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-1 tracking-widest">Date Received</label>
                                        <div class="relative w-full">
                                            <input type="text" name="arrival_date" required autocomplete="off" placeholder="dd/mm/yyyy"
                                                data-medicine-arrival
                                                data-default="{{ now()->format('Y-m-d') }}"
                                                data-alt-class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 outline-none shadow-sm text-sm font-semibold text-gray-900"
                                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 outline-none shadow-sm text-sm font-semibold text-gray-900">
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <p class="mt-1 text-[10px] text-gray-400">Past dates are disabled.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-1 tracking-widest">Expiration Date</label>
                                        <div class="relative w-full">
                                            <input type="text" name="expiration_date" required autocomplete="off" placeholder="dd/mm/yyyy"
                                                data-medicine-expiry
                                                data-alt-class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 outline-none shadow-sm text-sm font-semibold text-gray-900"
                                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 outline-none shadow-sm text-sm font-semibold text-gray-900">
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-500" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <p class="mt-1 text-[10px] text-gray-400">Past dates are disabled.</p>
                                    </div>
                                    <div><label class="block text-xs font-black text-gray-400 uppercase mb-1 tracking-widest">Quantity</label><input type="number" name="stock" value="1" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none shadow-sm" required></div>
                                    <div class="flex gap-3 pt-4">
                                        <button type="submit" class="flex-grow py-4 bg-blue-600 text-white font-black rounded-xl shadow-lg hover:bg-blue-700 transition uppercase text-xs">Add Stock</button>
                                        <button type="button" @click="openAddLot = null" class="px-6 py-4 bg-gray-50 text-gray-500 font-bold rounded-xl text-xs uppercase">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="panels-{{ $stackKey }}" x-show="openStacks === '{{ $stackKey }}'" class="inventory-expand-row bg-slate-50/80 divide-y divide-gray-100 border-b border-gray-100 last:border-b-0" style="display: none;">
                    <td colspan="5" class="px-6 py-5 align-top">
                        <div class="rounded-xl border-2 border-blue-500 bg-white shadow-lg shadow-blue-500/10 overflow-hidden max-w-full">
                            <div class="px-5 py-3.5 border-b-2 border-blue-500 bg-blue-50/90 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-blue-700">Current stacks (releasable)</p>
                                    <p class="text-sm font-bold text-slate-900 mt-0.5 break-words">{{ $name }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">Active stock only — soonest expiry first. Use the rows below for each batch (qty &amp; expiry).</p>
                                </div>
                                <span class="text-xs font-semibold text-blue-600 whitespace-nowrap shrink-0">{{ $releaseStacks->count() }} batch(es)</span>
                            </div>
                            <div class="divide-y divide-blue-100">
                                @forelse($releaseStacks as $stackLot)
                                    @php
                                        $stackLabel = $stackLot->batch_number ? 'Batch ' . $stackLot->batch_number : 'Lot #' . $stackLot->id;
                                    @endphp
                                    <a href="{{ route('medicines.edit', $stackLot) }}"
                                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-5 py-4 text-left transition hover:bg-blue-50 focus:outline-none focus-visible:bg-blue-50 group">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 group-hover:text-blue-700">{{ $stackLabel }}</p>
                                            <p class="text-xs text-slate-500 mt-1">
                                                Received
                                                @if($stackLot->arrival_date)
                                                    <span class="font-semibold text-slate-700">{{ $stackLot->arrival_date->format('M d, Y') }}</span>
                                                @else
                                                    <span class="font-semibold text-slate-400">N/A</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 sm:gap-8 shrink-0 text-sm">
                                            <div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Quantity</span>
                                                <span class="font-bold text-slate-800">{{ $stackLot->stock }}</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Expiry</span>
                                                <span class="font-semibold text-slate-700">
                                                    @if($stackLot->expiration_date){{ $stackLot->expiration_date->format('M d, Y') }}@else N/A @endif
                                                </span>
                                            </div>
                                            <span class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-50 p-2.5 text-blue-600 ring-1 ring-inset ring-blue-100 group-hover:bg-blue-100" title="View batch">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span class="sr-only">View batch</span>
                                            </span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-5 py-8 text-center text-sm text-slate-500">
                                        No releasable batches for this medicine — check expired / depleted lots in <span class="font-semibold text-slate-700">View details</span> or add stock.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            @empty
            <tbody class="inventory-lots">
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">No medicine records yet.</td>
                </tr>
            </tbody>
            @endforelse
        </table>
    </div>
    <div id="inventoryPagination" class="mt-4"></div>
    <div id="inventoryEmptyMessage" class="px-6 py-8 text-center text-gray-400 italic hidden">No medicine records found.</div>
</div>

<script>
    const inventoryRows = Array.from(document.querySelectorAll('#inventoryTable tbody.inventory-lots'))
        .filter((tbody) => tbody.querySelector('tr.inventory-row'))
        .map((tbody) => {
            const name = tbody.querySelector('.medicine-name')?.textContent.toUpperCase() ?? '';
            return { element: tbody, name };
        });

    function renderInventoryPagination(filteredRows) {
        const pager = $('#inventoryPagination');
        const emptyMessage = document.getElementById("inventoryEmptyMessage");

        if (pager.data('pagination')) {
            pager.pagination('destroy');
        }

        inventoryRows.forEach(item => {
            item.element.style.display = 'none';
        });

        if (filteredRows.length === 0) {
            emptyMessage.classList.remove('hidden');
            return;
        }

        emptyMessage.classList.add('hidden');

        pager.pagination({
            dataSource: filteredRows,
            pageSize: 10,
            showSizeChanger: false,
            callback: function (data) {
                inventoryRows.forEach(item => {
                    item.element.style.display = 'none';
                });
                data.forEach(item => {
                    item.element.style.display = '';
                });
            }
        });
    }

    function runFilters() {
        const input = document.getElementById("inventorySearch").value.toUpperCase();
        const filtered = inventoryRows.filter(row => row.name.includes(input));
        renderInventoryPagination(filtered);
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderInventoryPagination(inventoryRows);
    });
</script>
@endsection

@push('scripts')
    @include('partials.medicine-expiry-picker-scripts')
@endpush