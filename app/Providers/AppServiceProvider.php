<?php

namespace App\Providers;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        View::composer('*', function ($view) {
            $pendingReportCount = 0;
            $pendingGradingCount = 0;

            if (Auth::check() && Auth::user()->hasRole('pembimbing') && Auth::user()->mentor) {
                $mentorId = Auth::user()->mentor->id;

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

            // Kirim kedua variabel ke View
            $view->with('pendingReportCount', $pendingReportCount);
            $view->with('pendingGradingCount', $pendingGradingCount);
        });
    }
}
