<?php

use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\LetterController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Mahasiswa\AttendanceController;
use App\Http\Controllers\Mahasiswa\LogbookController;
use App\Http\Controllers\Mahasiswa\ReportController as MahasiswaReport;
use App\Http\Controllers\Mentor\ReportController as MentorReport;
use App\Http\Controllers\Mentor\StudentController as MentorStudent;
use App\Http\Controllers\Admin\StudentController as AdminStudent;
use App\Http\Controllers\KepalaDivisi\StudentController as KepalaDivisiStudent;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pembimbing\AssessmentController;
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
Route::get('/registrasi', [RegistrationController::class, 'create'])
    ->name('registration.create');
Route::post('/registrasi', [RegistrationController::class, 'store'])
    ->name('registration.store');
Route::get('/profile/verify-password/{token}', [App\Http\Controllers\ProfileController::class, 'verifyPassword'])
    ->name('profile.password.verify');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    Route::get('/notification/{id}', [NotificationController::class, 'open'])
        ->name('notifications.open');
    Route::get('/notifications/read-all', [NotificationController::class, 'markAllRead'])
        ->name('notifications.readAll');

    Route::get('/api/notifications/check', function () {
        $user = auth()->user();

        $notifications = $user->unreadNotifications;

        $html = view('layouts.partials.notification_list', compact('notifications'))->render();

        return response()->json([
            'count' => $notifications->count(),
            'html'  => $html
        ]);
    })->name('api.notifications.check');
});

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/surat-balasan/download', [MahasiswaDashboard::class, 'downloadLetter'])
            ->name('download-surat');
        Route::get('/sertifikat/download', [MahasiswaDashboard::class, 'downloadCertificate'])
            ->name('download-sertifikat');
        Route::get('/transkrip/download', [MahasiswaDashboard::class, 'downloadTranscript'])
            ->name('download-transkrip');

        Route::get('/kehadiran', [AttendanceController::class, 'index'])
            ->name('attendance.index');
        Route::post('/kehadiran', [AttendanceController::class, 'store'])
            ->name('attendance.store');

        Route::resource('logbook', LogbookController::class);

        Route::get('/laporan', [MahasiswaReport::class, 'index'])
            ->name('laporan.index');
        Route::post('/laporan', [MahasiswaReport::class, 'update'])
            ->name('laporan.update');

});

Route::middleware(['auth', 'role:pembimbing'])
    ->prefix('pembimbing')
    ->name('pembimbing.')
    ->group(function () {

        Route::get('/dashboard', [PembimbingDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/mahasiswa', [MentorStudent::class, 'index'])
            ->name('mahasiswa.index');

        Route::get('/mahasiswa/{registration}', [MentorStudent::class, 'show'])
            ->name('mahasiswa.show');

        Route::put('/logbook/{logbook}/approve', [MentorStudent::class, 'approveLogbook'])
            ->name('logbook.approve');
        Route::put('/logbook/{logbook}/reject', [MentorStudent::class, 'rejectLogbook'])
            ->name('logbook.reject');

        Route::get('/laporan', [MentorReport::class, 'index'])
            ->name('laporan.index');
        Route::get('/laporan/{registration}', [MentorReport::class, 'show'])
            ->name('laporan.show');
        Route::put('/laporan/{registration}/approve', [MentorReport::class, 'approve'])
            ->name('laporan.approve');
        Route::put('/laporan/{registration}/revise', [MentorReport::class, 'revise'])
            ->name('laporan.revise');

        Route::get('/penilaian', [AssessmentController::class, 'index'])
            ->name('penilaian.index');
        Route::get('/penilaian/{registration}/create', [AssessmentController::class, 'create'])
            ->name('penilaian.create');
        Route::post('/penilaian/{registration}', [AssessmentController::class, 'store'])
            ->name('penilaian.store');
        Route::get('/penilaian/{registration}/detail', [AssessmentController::class, 'show'])
            ->name('penilaian.show');
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

        Route::resource('divisi', DivisionController::class)
            ->parameters(['divisi' => 'division'])
            ->except(['show', 'create', 'edit']);
        Route::resource('user', UserController::class)
            ->except(['show', 'create', 'edit']);
        Route::resource('pembimbing', MentorController::class)
            ->parameters(['pembimbing' => 'mentor'])
            ->except(['show', 'create', 'edit']);
        Route::resource('mahasiswa', AdminStudent::class)
            ->parameters(['mahasiswa' => 'student'])
            ->except(['create', 'store']);

        Route::put('/members/{member}', [App\Http\Controllers\Admin\MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('members.destroy');

        Route::get('/surat', [LetterController::class, 'index'])
            ->name('surat.index');
        Route::put('/surat/{registration}', [LetterController::class, 'update'])
            ->name('surat.update');
        Route::get('/surat/{registration}/show', [LetterController::class, 'show'])
            ->name('surat.show');

        Route::get('/arsip-dokumen', [ArchiveController::class, 'index'])
            ->name('arsip.index');


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

        Route::get('/mahasiswa', [KepalaDivisiStudent::class, 'index'])
            ->name('mahasiswa.index');
        Route::get('/mahasiswa/{registration}', [KepalaDivisiStudent::class, 'show'])
            ->name('mahasiswa.show');
});

require __DIR__.'/auth.php';
