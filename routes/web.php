<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboard;
use App\Http\Controllers\KepalaDivisi\DashboardController as KepalaDivisiDashboard;
use App\Http\Controllers\Admin\RegistarVerification;


Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('/pengajuan-kp', [RegistarVerification::class, 'create'])->name('admin.registration.create');
    Route::get('/pengajuan/detail/{id}', [RegistarVerification::class, 'show'])->name('admin.pengajuan.show');
    Route::get('/pengajuan/{registration}/forward', [RegistarVerification::class, 'forward'])->name('admin.pengajuan.forward');
    Route::post('/pengajuan/{registration}/reject', [RegistarVerification::class, 'reject'])->name('admin.pengajuan.reject');
    Route::delete('/pengajuan/{registration}/archive', [RegistarVerification::class, 'archive'])->name('admin.pengajuan.archive');
});

Route::middleware(['auth', 'role:kepala_divisi'])->prefix('kepala-divisi')->group(function () {
    Route::get('/dashboard', [KepalaDivisiDashboard::class, 'index'])->name('kepala.dashboard');
});

require __DIR__.'/auth.php';
