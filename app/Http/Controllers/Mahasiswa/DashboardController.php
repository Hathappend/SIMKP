<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ReportProgress;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        // ==========================================
        // HITUNG DURASI (Time Progress)
        // ==========================================
        $start = Carbon::parse($registration->start_date);
        $end = Carbon::parse($registration->end_date);
        $now = Carbon::now();

        $totalDurationDays = $start->diffInDays($end) + 1;

        if ($now->lessThan($start)) {
            $daysPassed = 0;
        } else {
            $daysPassed = $now->greaterThan($end) ? $totalDurationDays : $start->diffInDays($now) + 1;
        }

        $daysRemaining = max(0, $now->diffInDays($end, false));
        $progressPercentage = $totalDurationDays > 0 ? round(($daysPassed / $totalDurationDays) * 100) : 0;


        // ==========================================
        // HITUNG KEHADIRAN (Attendance Stats)
        // ==========================================
        $workingDaysPassed = 0;
        $calcLimit = $now->lessThan($end) ? $now : $end; // Hitung sampai hari ini atau sampai selesai
        $checkDate = $start->copy();

        if ($now->greaterThanOrEqualTo($start)) {
            while ($checkDate->lte($calcLimit)) {
                if (!$checkDate->isWeekend()) {
                    $workingDaysPassed++;
                }
                $checkDate->addDay();
            }
        }

        $totalAttendance = \App\Models\Attendance::where('student_id', $student->id)
            ->whereIn('status', ['present', 'sick', 'permission'])
            ->count();

        $attendancePercentage = 0;
        if ($workingDaysPassed > 0) {
            $attendancePercentage = round(($totalAttendance / $workingDaysPassed) * 100);
        }
        $attendancePercentage = min(100, $attendancePercentage);


        // ==========================================
        // AMBIL LOGBOOK HARI INI
        // ==========================================
        $todayLogbooks = \App\Models\Logbook::where('student_id', $student->id)
            ->where('date', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();


        return view('mahasiswa.dashboard', compact(
            'student',
            'registration',
            'progressPercentage',
            'daysRemaining',
            'daysPassed',
            'workingDaysPassed',
            'totalAttendance',
            'attendancePercentage',
            'todayLogbooks'
        ));
    }

    public function downloadLetter(): StreamedResponse|RedirectResponse
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
