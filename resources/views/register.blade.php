@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-gray-200 p-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Create Account</h2>
            <p class="mt-2 text-sm text-gray-500">Join the Clinic OS management system</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="staff@clinic.com"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="Re-type password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 outline-none transition">
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md shadow-blue-200 transition active:transform active:scale-95">
                Register Account
            </button>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">Already registered? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Sign in instead</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection