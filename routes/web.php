<?php

use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\LetterController;
use App\Http\Controllers\admin\MentorController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboard;
use App\Http\Controllers\KepalaDivisi\DashboardController as KepalaDivisiDashboard;
use App\Http\Controllers\Admin\RegistarVerification;
use App\Http\Controllers\KepalaDivisi\RegisterController;


Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

// KP Registration
Route::get('/registrasi', [RegistrationController::class, 'create'])->name('registration.create');
Route::post('/registrasi', [RegistrationController::class, 'store'])->name('registration.store');

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])
            ->name('dashboard');

        // Download Surat Balasan (Fitur di Dashboard)
        Route::get('/surat-balasan/download', [MahasiswaDashboard::class, 'downloadLetter'])
            ->name('download-surat');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:pembimbing'])
    ->prefix('pembimbing')
    ->name('pembimbing.')
    ->group(function () {

        Route::get('/dashboard', [PembimbingDashboard::class, 'index'])->name('dashboard');

});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::get('/pengajuan-kp', [RegistarVerification::class, 'create'])
            ->name('registration.create');
        Route::get('/pengajuan/detail/{id}', [RegistarVerification::class, 'show'])
            ->name('pengajuan.show');
        Route::get('/pengajuan/{registration}/forward', [RegistarVerification::class, 'forward'])
            ->name('pengajuan.forward');
        Route::post('/pengajuan/{registration}/reject', [RegistarVerification::class, 'reject'])
            ->name('pengajuan.reject');
        Route::delete('/pengajuan/{registration}/archive', [RegistarVerification::class, 'archive'])
            ->name('pengajuan.archive');

        Route::resource('divisi', DivisionController::class)->parameters(['divisi' => 'division'])
            ->except(['show', 'create', 'edit']);
        Route::resource('user', UserController::class)
            ->except(['show', 'create', 'edit']);
        Route::resource('pembimbing', MentorController::class)->parameters(['pembimbing' => 'mentor'])
            ->except(['show', 'create', 'edit']);

        Route::get('/surat', [LetterController::class, 'index'])
            ->name('surat.index');
        Route::put('/surat/{registration}', [LetterController::class, 'update'])
            ->name('surat.update');
        Route::get('/surat/{registration}/show', [LetterController::class, 'show'])
            ->name('surat.show');

});

Route::middleware(['auth', 'role:kepala_divisi'])
    ->prefix('kadiv')
    ->name('kadiv.')
    ->group(function () {

        Route::get('/dashboard', [KepalaDivisiDashboard::class, 'index'])
            ->name('dashboard');
        Route::get('/pengajuan', [RegisterController::class, 'index'])
            ->name('pengajuan.index');
        Route::get('/pengajuan/detail/{registration}', [RegisterController::class, 'show'])
            ->name('pengajuan.show');
        Route::post('/pengajuan/{registration}/approve', [RegisterController::class, 'approve'])
            ->name('pengajuan.approve');
        Route::post('/pengajuan/{registration}/reject', [RegisterController::class, 'reject'])
            ->name('pengajuan.reject');
});

require __DIR__.'/auth.php';
