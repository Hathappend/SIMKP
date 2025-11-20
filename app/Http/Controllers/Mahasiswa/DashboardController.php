<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ReportProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $student = $user->student;

        $registration = $student->registrations()
            ->with(['division', 'mentor'])
            ->where('application_status', 'approved')
            ->latest()
            ->first();

        if (!$registration) {
            return view('mahasiswa.dashboard_empty');
        }

        // Hitung Durasi Magang
        $start = Carbon::parse($registration->start_date);
        $end = Carbon::parse($registration->end_date);
        $now = Carbon::now();

        $totalDays = $start->diffInDays($end) + 1;
        $daysPassed = $now->greaterThan($start) ? $start->diffInDays($now) : 0;

        // Cek agar tidak melebihi 100%
        $progressPercentage = min(100, round(($daysPassed / $totalDays) * 100));
        $daysRemaining = max(0, $now->diffInDays($end, false));

        $reportStages = ReportProgress::where('student_id', $student->id)
            ->orderBy('id', 'asc')
            ->get();

        return view('mahasiswa.dashboard', compact(
            'student',
            'registration',
            'progressPercentage',
            'daysRemaining',
            'daysPassed',
            'reportStages',
        ));
    }

    public function downloadLetter()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $registration = $student->registrations()
            ->where('application_status', 'approved')
            ->whereNotNull('reply_letter_path')
            ->latest()
            ->first();

        if (!$registration || !Storage::disk('public')->exists($registration->reply_letter_path)) {
            return redirect()->back()->with('error', 'Surat balasan belum diterbitkan oleh Admin.');
        }
        
        $downloadName = 'Surat_Balasan_Magang_' . $student->nim . '.pdf';

        return Storage::disk('public')->download($registration->reply_letter_path, $downloadName);
    }
}
