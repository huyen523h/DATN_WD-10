<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-auth', function () {
    if (Auth::check()) {
        return 'User is logged in: ' . Auth::user()->name;
    } else {
        return 'User is not logged in';
    }
});

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

Route::get('/clear-all', function () {
    // Xóa tất cả session
    session()->flush();
    session()->regenerate();
    
    // Logout nếu có user
    if (Auth::check()) {
        Auth::logout();
    }
    
    return 'All sessions cleared! <a href="/">Go to homepage</a>';
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tours', [AdminController::class, 'tours'])->name('tours');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');