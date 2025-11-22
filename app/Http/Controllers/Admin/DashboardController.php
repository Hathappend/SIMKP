<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pending' => Registration::where('application_status', 'pending')->count(),
            'active'  => Registration::where('application_status', 'approved')->count(),
            'letters' => Registration::where('application_status', 'approved')
                ->where('letter_status', 'waiting')->count(),
            'mentors' => Mentor::count(),
        ];

        // Grafik Pendaftaran (6 Bulan Terakhir)
        $months = [];
        $counts = [];

        // Ambil Data 6 Bulan Terakhir
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M');
            $monthNum = $date->month;
            $yearNum = $date->year;

            $count = Registration::whereYear('created_at', $yearNum)
                ->whereMonth('created_at', $monthNum)
                ->count();

            $months[] = $monthName;
            $counts[] = $count;
        }

        $maxCount = max($counts);
        $scale = $maxCount > 0 ? $maxCount : 1;

        // Hitung Persentase Berdasarkan Max Value
        $percentages = [];
        foreach ($counts as $count) {
            if ($count === 0) {
                $percentages[] = 2;
            } else {
                $percentages[] = round(($count / $scale) * 100);
            }
        }

        $chart = [
            'labels' => $months,
            'counts' => $counts,
            'percentages' => $percentages
        ];

        // Aktivitas Terbaru
        $recentActivities = Registration::with('student')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chart', 'recentActivities'));
    }
}
