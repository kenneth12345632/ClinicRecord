@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Barangay Banilad Health Care Center</h1>
        <p class="text-gray-600 mt-2">Manage your patient records and daily consultations.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-lg text-slate-700">Patient Management</h3>
            <p class="text-sm text-gray-500 mt-1">Access all clinical history and records.</p>
            
            <a href="{{ route('record.index') }}" class="inline-block mt-4 text-sm font-semibold text-blue-600 hover:underline">
                View all records →
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-lg text-slate-700">Inventory Medicine</h3>
            <p class="text-sm text-gray-500 mt-1">Check stock levels and medicine availability.</p>
            
            <a href="{{ route('medicines.index') }}" class="inline-block mt-4 text-sm font-semibold text-blue-600 hover:underline">
                Manage Inventory →
            </a>
        </div>

    </div>
@endsection