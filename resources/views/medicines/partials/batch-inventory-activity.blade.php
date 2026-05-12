<div class="mb-8">
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-green-400">Activity for this batch</h2>
        <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Stock movements linked to medicine ID #{{ $medicine->id }}.</p>
    </div>
    <div class="bg-white dark:bg-[#0f1d1a] rounded-xl shadow-sm border border-gray-200 dark:border-[#22543d] p-8">
        <div class="space-y-4">
            @forelse($inventoryLogs as $log)
                @php
                    $actionLabel = match($log->transaction_type) {
                        'stock_in' => 'Stock-In',
                        'stock_out' => 'Dispense',
                        default => 'Adjustment',
                    };
                    $actionClass = match($log->transaction_type) {
                        'stock_in' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
                        'stock_out' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
                        default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                    };
                    $consultationId = null;
                    if ($log->transaction_type === 'stock_out' && preg_match('/Dispensed for consultation #(\d+)/i', (string) $log->reference, $matches)) {
                        $consultationId = (int) $matches[1];
                    }
                    $patientName = $consultationId ? ($consultationNames[$consultationId] ?? null) : null;
                @endphp
                <div class="rounded-xl border border-gray-100 dark:border-[#1e3a2a] p-4 bg-gray-50/40 dark:bg-[#111827]">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase">User</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $log->user?->full_name ?? 'System' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase">Medicine Name</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $log->medicine?->name ?? $medicine->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase">Quantity</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ abs((int) $log->quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase">Date/Time</p>
                            <p class="font-semibold text-slate-800 dark:text-slate-200 inventory-log-time"
                               data-timestamp="{{ $log->created_at->toIso8601String() }}">
                                {{ $log->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase">Action Type</p>
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $actionClass }}">{{ $actionLabel }}</span>
                            @if($patientName)
                                <p class="text-[10px] font-bold text-gray-400 dark:text-green-600 uppercase mt-2">Patient</p>
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $patientName }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center border border-dashed border-gray-200 dark:border-[#22543d] rounded-xl">
                    <p class="text-gray-400 dark:text-slate-500 font-semibold">No inventory logs for this batch yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
