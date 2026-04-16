{{-- inventory/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Inventory Medicine</h1>
            <p class="text-gray-500 text-sm mt-1">Manage clinic medical supplies by expiration and batch.</p>
        </div>
        <a href="{{ route('medicines.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-sm hover:bg-blue-700 transition">
            + Add Medicine
        </a>
    </div>

    {{-- Staff Search and Quick-Filters --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            </span>
            <input type="text" id="inventorySearch" onkeyup="runFilters()" 
                placeholder="Search medicine name or status..." 
                class="pl-10 pr-4 py-3 w-full rounded-xl border border-gray-200 focus:border-blue-500 outline-none shadow-sm transition">
        </div>

        {{-- Quick-Filter Buttons --}}
        <div class="flex gap-2">
            <button onclick="setQuickFilter('all')" id="filter-all" class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-bold shadow-sm">All</button>
            <button onclick="setQuickFilter('low')" id="filter-low" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50">Low Stock</button>
            <button onclick="setQuickFilter('priority')" id="filter-priority" class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50">Priority</button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200" id="inventoryTable">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Medicine Name</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Arrival/Batch</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Expiry Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Inventory Status</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($medicines as $medicine)
                <tr class="hover:bg-gray-50 transition inventory-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-slate-800 medicine-name">{{ $medicine->name }}</div>
                        @if($medicine->created_at->diffInDays(now()) < 3)
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-tight">New Batch</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $medicine->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                        {{ $medicine->stock }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-600 {{ $medicine->expiration_date->isPast() ? 'text-red-600 font-bold' : '' }}">
                            {{ $medicine->expiration_date->format('M d, Y') }}
                        </div>

                        @php
                            $allBatchesOfThisMedicine = $medicines->where('name', $medicine->name);
                            $earliestExpiryDate = $allBatchesOfThisMedicine->min('expiration_date');
                            $isPriority = ($medicine->expiration_date == $earliestExpiryDate && $allBatchesOfThisMedicine->count() > 1 && !$medicine->expiration_date->isPast());
                        @endphp

                        @if($isPriority)
                            <span class="text-[10px] font-bold text-orange-600 uppercase tracking-tight block mt-1 priority-label">
                                Priority: Use First
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap status-cell">
                        @if($medicine->stock <= 0)
                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">❌ Out of Stock</span>
                        @elseif($medicine->stock <= 20)
                            <span class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full low-stock-badge">⚠️ Low Stock</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">✅ In Stock</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('medicines.edit', $medicine) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" onsubmit="return confirm('Delete this batch?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">No medicines found in inventory.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    let currentQuickFilter = 'all';

    function setQuickFilter(type) {
        currentQuickFilter = type;
        
        // Update UI buttons
        ['all', 'low', 'priority'].forEach(f => {
            const btn = document.getElementById(`filter-${f}`);
            btn.className = f === type 
                ? "px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-bold shadow-sm"
                : "px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50";
        });

        runFilters();
    }

    function runFilters() {
        const searchInput = document.getElementById("inventorySearch").value.toUpperCase();
        const rows = document.querySelectorAll(".inventory-row");

        rows.forEach(row => {
            const name = row.querySelector(".medicine-name").textContent.toUpperCase();
            const hasLowStock = row.querySelector(".low-stock-badge") !== null;
            const hasPriority = row.querySelector(".priority-label") !== null;
            
            // Logic for combining Search and Quick-Filters
            const matchesSearch = name.includes(searchInput);
            let matchesFilter = true;

            if (currentQuickFilter === 'low') matchesFilter = hasLowStock;
            if (currentQuickFilter === 'priority') matchesFilter = hasPriority;

            row.style.display = (matchesSearch && matchesFilter) ? "" : "none";
        });
    }
</script>
@endsection