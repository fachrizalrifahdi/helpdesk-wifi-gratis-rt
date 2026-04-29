<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicTiketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardTiketController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PetugasController;

// Public Routes (Warga)
Route::get('/', [PublicTiketController::class, 'landing'])->name('landing');
Route::get('/tiket/create', [PublicTiketController::class, 'index'])->name('tiket.create');
Route::post('/tiket/store', [PublicTiketController::class, 'store'])->name('tiket.store');

// Auth Routes (Petugas)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes (Authenticated Petugas)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ticket Management
    Route::get('/dashboard/tiket', [DashboardTiketController::class, 'index'])->name('dashboard.tiket.index');
    Route::post('/dashboard/tiket/{id}/assign', [DashboardTiketController::class, 'assign'])->name('dashboard.tiket.assign');
    Route::post('/dashboard/tiket/{id}/status', [DashboardTiketController::class, 'updateStatus'])->name('dashboard.tiket.update-status');
    Route::post('/dashboard/tiket/{id}/mark-read', [DashboardTiketController::class, 'markRead'])->name('dashboard.tiket.mark-read');
    Route::post('/dashboard/tiket/{id}/claim', [DashboardTiketController::class, 'claim'])->name('dashboard.tiket.claim');
    Route::delete('/dashboard/tiket/{id}', [DashboardTiketController::class, 'destroy'])->name('dashboard.tiket.destroy');

    // Settings
    Route::get('/dashboard/settings', [SettingsController::class, 'index'])->name('dashboard.settings');
    Route::post('/dashboard/settings/profile', [SettingsController::class, 'updateProfile'])->name('dashboard.settings.profile');
    Route::post('/dashboard/settings/password', [SettingsController::class, 'updatePassword'])->name('dashboard.settings.password');

    // Petugas Management (Admin Only)
    Route::get('/dashboard/petugas', [PetugasController::class, 'index'])->name('dashboard.petugas.index');
    Route::post('/dashboard/petugas', [PetugasController::class, 'store'])->name('dashboard.petugas.store');
    Route::post('/dashboard/petugas/{id}/reset', [PetugasController::class, 'resetPassword'])->name('dashboard.petugas.reset');
    Route::delete('/dashboard/petugas/{id}', [PetugasController::class, 'destroy'])->name('dashboard.petugas.destroy');
});
