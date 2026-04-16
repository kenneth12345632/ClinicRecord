@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('medicines.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4 font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Inventory
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Add New Medicine</h1>
        <p class="text-gray-500 mt-1">Register a new item into the clinic inventory.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('medicines.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Medicine Name - Full Width --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Medicine Name</label>
                    <input type="text" name="name" placeholder="e.g. Paracetamol" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
                </div>

                {{-- Stock and Expiry side-by-side --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Stock Quantity</label>
                    <input type="number" name="stock" placeholder="0" min="0" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Expiration Date</label>
                    <input type="date" name="expiration_date" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center gap-4">
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition">
                    Save to Inventory
                </button>
                <a href="{{ route('medicines.index') }}" class="px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection