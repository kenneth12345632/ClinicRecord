<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Clinic OS') }}</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Smooth fade-in effect for the login box */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-900">
    <div class="min-h-screen flex flex-col justify-center items-center p-6">
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                <span class="text-blue-600">✚</span> CLINIC OS
            </h1>
            <p class="text-slate-500 mt-2">Health Management System</p>
        </div>

        <div class="w-full max-w-md fade-in">
            @yield('content')
        </div>

        <footer class="mt-8 text-center text-slate-400 text-xs">
            &copy; {{ date('Y') }} Clinic Operating System. All rights reserved.
        </footer>
    </div>
</body>
</html>