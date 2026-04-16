@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-gray-200 p-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Clinic OS</h2>
            <p class="mt-2 text-sm text-gray-500">Please sign in to your account</p>
        </div>

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@clinic.com"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md shadow-blue-200 transition active:transform active:scale-95">
                Sign In
            </button>
            
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Register here</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection