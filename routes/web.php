<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;

// ===== PUBLIC =====
Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');
Route::get('/formulir', [VisitorController::class, 'index']);
Route::post('/formulir', [VisitorController::class, 'store']);

// ===== AUTH =====
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===== ADMIN =====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',  [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/guests',         [GuestController::class, 'index'])->name('admin.guests');
    Route::delete('/guests/{id}', [GuestController::class, 'destroy'])->name('admin.guests.destroy');

    Route::get('/users',          [UserController::class, 'index'])->name('admin.users');
    Route::post('/users',         [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{id}',     [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}',  [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
