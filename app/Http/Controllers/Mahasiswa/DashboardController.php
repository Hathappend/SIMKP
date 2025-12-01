<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ReportProgress;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->whereIn('application_status', ['approved', 'completed'])
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
            $attendancePercentage = round(($totalAttendance / $totalDurationDays) * 100);
        }
        $attendancePercentage = min(100, $attendancePercentage);


        // ==========================================
        // AMBIL LOGBOOK HARI INI
        // ==========================================
        $todayLogbooks = \App\Models\Logbook::where('student_id', $student->id)
            ->where('date', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        // ==========================================
        // [BARU] SIAPKAN DATA NILAI TIM (GROUPING)
        // ==========================================
        $teamAssessments = collect([]);

        // 1. Masukkan Nilai KETUA (Saya)
        if ($registration->assessment) {
            $teamAssessments->push((object)[
                'name' => $student->name,
                'nim' => $student->nim,
                'role' => 'Ketua Tim',
                'score' => $registration->assessment // Objek Assessment
            ]);
        }

        // 2. Masukkan Nilai ANGGOTA (Looping)
        foreach ($registration->members as $member) {
            if ($member->assessment) {
                $teamAssessments->push((object)[
                    'name' => $member->name,
                    'nim' => $member->nim,
                    'role' => 'Anggota',
                    'score' => $member->assessment // Objek Assessment
                ]);
            }
        }


        return view('mahasiswa.dashboard', compact(
            'student',
            'registration',
            'progressPercentage',
            'daysRemaining',
            'daysPassed',
            'workingDaysPassed',
            'totalAttendance',
            'attendancePercentage',
            'todayLogbooks',
            'teamAssessments',
            'totalDurationDays'
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

    public function downloadCertificate()
    {
        $student = Auth::user()->student;
        $registration = $student->registrations()->where('application_status', 'completed')->latest()->first();

        if (!$registration || !$registration->assessment || !$registration->assessment->certificate_path) {
            return back()->with('error', 'Sertifikat belum tersedia.');
        }

        return Storage::disk('public')->download(
            $registration->assessment->certificate_path,
            'Sertifikat_Magang_' . $student->name . '.pdf'
        );
    }

}
