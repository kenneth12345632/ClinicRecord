<?php

use App\Http\Controllers\ClinicRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. Root Route - Smart Redirect
// This prevents the "POST /" error if you're already logged in
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// 2. GUEST ROUTES (Accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// 3. AUTH ROUTES (Accessible ONLY when logged in)
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ClinicRecordController::class, 'dashboard'])->name('dashboard');
    
    // Resources
    Route::resource('record', ClinicRecordController::class);
    Route::resource('medicines', MedicineController::class);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});