@extends('layouts.guest')

@section('title', 'Login Page')

@section('content')
<div class="h-screen overflow-hidden flex items-center justify-center bg-[#E0E9FF] p-4">
    <div class="w-full max-w-[980px] h-[560px] bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 flex">
        <div class="w-1/2 p-10 flex items-center justify-center bg-[#E6F3F4] overflow-hidden relative">
            <div class="relative z-10 w-full flex items-center justify-center">
                <div class="w-[302px] h-[302px] rounded-full overflow-hidden flex items-center justify-center">
                    <img
                        src="{{ asset('images/login-clinic-logo.png') }}?v=login-ui-6"
                        alt="Barangay Banilad Dumaguete City Logo"
                        class="w-full h-full object-cover object-center"
                    />
                </div>
            </div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 border-8 border-[#2D8A80]/10 rounded-full"></div>
        </div>

        <div class="w-1/2 p-10 flex flex-col justify-center bg-white">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl text-left">
                    <ul class="space-y-1 font-medium list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="text-center text-[34px] sm:text-[38px] lg:text-[40px] text-gray-900 font-medium leading-tight mb-2">
                Barangay Banilad<br>Health Center!
            </h1>

            <form action="{{ route('login') }}" method="POST" class="space-y-6 w-full">
                @csrf
                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Username or E-mail</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="admin@clinic.local"
                        class="w-full px-6 py-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition bg-gray-50/50 text-left"
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full px-6 py-4 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none text-base font-medium transition bg-gray-50/50 text-left"
                    >
                </div>

                <div class="pt-6">
                    <button
                        type="submit"
                        class="w-full py-4 bg-blue-600 text-white text-sm font-black rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-wider text-center"
                    >
                        Sign In
                    </button>
                </div>

                <p class="text-center text-sm text-gray-500 font-medium mt-10">
                    Contact administrator for account access.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection