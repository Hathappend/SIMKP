<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Filter berdasarkan DIVISI user yang login
        $divisionId = $user->division_id;

        // 1. Statistik
        $stats = [
            'active' => Registration::where('division_id', $divisionId)
                ->where('application_status', 'approved')->count(),
            'pending' => Registration::where('division_id', $divisionId)
                ->where('application_status', 'waiting')->count(), // Waiting = sudah lolos admin
            'completed' => Registration::where('division_id', $divisionId)
                ->where('application_status', 'completed')->count(),
        ];

        // 2. Pengajuan yang butuh persetujuan (Prioritas)
        $pendingApplications = Registration::with('student')
            ->where('division_id', $divisionId)
            ->where('application_status', 'waiting')
            ->oldest() // Yang lama didahulukan
            ->take(3)
            ->get();

        // 3. Mahasiswa yang sedang magang (Active)
        $activeInterns = Registration::with(['student', 'mentor'])
            ->where('division_id', $divisionId)
            ->where('application_status', 'approved')
            ->latest('start_date')
            ->take(5)
            ->get();

        return view('kepala_divisi.dashboard', compact('stats', 'pendingApplications', 'activeInterns'));
    }
}
