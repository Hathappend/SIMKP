<?php

namespace App\Providers;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $pendingReportCount = 0;
            $pendingGradingCount = 0;
            $pendingRegistrationCount = 0;
            $pendingLetterCount = 0;
            $pendingVerificationCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                // ==========================================
                // PEMBIMBING
                // ==========================================
                if ($user->hasRole('pembimbing') && $user->mentor) {
                    $mentorId = $user->mentor->id;

                    // Hitung Laporan Pending
                    $pendingReportCount = Registration::where('mentor_id', $mentorId)
                        ->where('report_status', 'submitted')
                        ->count();

                    // Hitung Penilaian Pending
                    $pendingGradingCount = Registration::where('mentor_id', $mentorId)
                        ->whereIn('application_status', ['approved', 'completed'])
                        ->whereDate('end_date', '<=', Carbon::now()->addDays(7))
                        ->doesntHave('assessment')
                        ->count();
                }

                // ==========================================
                // ADMIN
                // ==========================================
                if ($user->hasRole('admin')) {
                    // Hitung pendaftaran baru yang belum diverifikasi
                    $pendingRegistrationCount = Registration::where('application_status', 'pending')->count();

                    // Surat Belum Dibuat (Approved tapi Waiting)
                    $pendingLetterCount = Registration::where('application_status', 'approved')
                        ->where('letter_status', 'waiting')
                        ->count();
                }

                // ==========================================
                // KEPALA DIVISI
                // ==========================================
                if ($user->hasRole('kepala_divisi') || $user->hasRole('kadiv')) {
                    if ($user->division_id) {
                        $pendingVerificationCount = Registration::where('division_id', $user->division_id)
                            ->where('application_status', 'waiting')
                            ->count();
                    }
                }
            }

            $view->with('pendingReportCount', $pendingReportCount);
            $view->with('pendingGradingCount', $pendingGradingCount);
            $view->with('pendingRegistrationCount', $pendingRegistrationCount);
            $view->with('pendingLetterCount', $pendingLetterCount);
            $view->with('pendingVerificationCount', $pendingVerificationCount);
        });
    }
}
