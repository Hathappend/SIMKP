<?php

namespace App\Providers;

use App\Models\Registration;
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

            // Cek jika user login dan dia adalah Pembimbing
            if (Auth::check() && Auth::user()->hasRole('pembimbing') && Auth::user()->mentor) {
                $pendingReportCount = Registration::where('mentor_id', Auth::user()->mentor->id)
                    ->where('report_status', 'submitted') // Status Menunggu Review
                    ->count();
            }

            $view->with('pendingReportCount', $pendingReportCount);
        });
    }
}
