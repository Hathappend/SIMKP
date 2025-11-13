<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboard;
use App\Http\Controllers\KepalaDivisi\DashboardController as KepalaDivisiDashboard;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// KP Registration
Route::get('/registrasi', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/registrasi', [RegistrationController::class, 'store'])->name('registration.store');

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('mahasiswa.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:pembimbing'])->prefix('pembimbing')->group(function () {
    Route::get('/dashboard', [PembimbingDashboard::class, 'index'])->name('pembimbing.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth', 'role:kepala_divisi'])->prefix('kepala-divisi')->group(function () {
    Route::get('/dashboard', [KepalaDivisiDashboard::class, 'index'])->name('kepala.dashboard');
});

require __DIR__.'/auth.php';
