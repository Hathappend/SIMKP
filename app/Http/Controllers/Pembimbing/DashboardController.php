<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $mentorId = Auth::user()->mentor->id;

        // 1. Statistik Utama
        $totalStudents = Registration::where('mentor_id', $mentorId)
            ->whereIn('application_status', ['approved', 'completed'])->count();

        $pendingLogbooksCount = Logbook::whereHas('student.registrations', function($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId)->where('application_status', 'approved');
        })->where('status', 'pending')->count();

        $pendingReportsCount = Registration::where('mentor_id', $mentorId)
            ->where('report_status', 'submitted')->count();

        // 2. "Inbox" Tugas (Logbook Terbaru yang butuh review)
        $recentPendingLogbooks = Logbook::with('student')
            ->whereHas('student.registrations', function($q) use ($mentorId) {
                $q->where('mentor_id', $mentorId);
            })
            ->where('status', 'pending')
            ->latest('date')
            ->take(5)
            ->get();

        // 3. Daftar Mahasiswa Ringkas (Progress Bar)
        $myStudents = Registration::with('student')
            ->where('mentor_id', $mentorId)
            ->where('application_status', 'approved')
            ->latest('start_date')
            ->take(5)
            ->get();

        return view('pembimbing.dashboard', compact(
            'totalStudents',
            'pendingLogbooksCount',
            'pendingReportsCount',
            'recentPendingLogbooks',
            'myStudents'
        ));
    }
}
