<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Registration;
use App\Notifications\LogbookApprovedNotification;
use App\Notifications\LogbookRejectedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $mentor = Auth::user()->mentor;

        if (!$mentor) {
            abort(403, 'Anda belum terdaftar sebagai Mentor.');
        }

        // Ambil Mahasiswa KHUSUS Bimbingan Mentor Ini
        $students = Registration::with(['student', 'division', 'attendances', 'logbooks'])
            ->where('mentor_id', $mentor->id)
            ->whereIn('application_status', ['approved', 'completed'])
            ->withCount(['logbooks as pending_logbooks_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->withCount(['logbooks as pending_logbooks_count' => function ($query) {
                $query->where('status', 'pending');
            }])
            ->latest('start_date')
            ->get();

        // Hitung Statistik
        $stats = [
            'total' => $students->count(),
            'active' => $students->where('application_status', 'approved')->count(),
            'total_pending_logbooks' => $students->sum('pending_logbooks_count'),
            'pending_reports' => $students->where('report_status', 'submitted')->count(),
        ];

        return view('pembimbing.student.create', compact('students', 'stats'));
    }

    public function show(Registration $registration)
    {
        if ($registration->mentor_id !== \Illuminate\Support\Facades\Auth::user()->mentor->id) {
            abort(403, 'Anda tidak memiliki akses ke data mahasiswa ini.');
        }

        $registration->load([
            'student',
            'division',
            'members',
            'logbooks' => function($q) {
                $q->orderBy('date', 'desc')->orderBy('start_time', 'desc');
            },
            'attendances' => function($q) {
                $q->orderBy('date', 'desc');
            }
        ]);

        // ==========================================
        // STATISTIK UNTUK HEADER DASHBOARD
        // ==========================================

        $totalLogbook = $registration->logbooks->count();
        $pendingLogbook = $registration->logbooks->where('status', 'pending')->count();

        $totalAttendance = $registration->attendances
            ->whereIn('status', ['present', 'sick', 'permission'])
            ->count();

        // Sisa Hari Magang
        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($registration->end_date), false);
        if ($daysLeft < 0) $daysLeft = 0;

        return view('pembimbing.student.show', compact(
            'registration',
            'totalLogbook',
            'pendingLogbook',
            'totalAttendance',
            'daysLeft'
        ));
    }

    public function approveLogbook(Logbook $logbook): RedirectResponse
    {
        if ($logbook->student->registrations->last()->mentor_id !== Auth::user()->mentor->id) {
            abort(403);
        }

        $logbook->update(['status' => 'approved', 'feedback' => null]);

        if ($logbook->student->user) {
            $logbook->student->user->notify(new LogbookApprovedNotification($logbook));
        }
        return back()->with('success', 'Logbook disetujui.');
    }

    public function rejectLogbook(Request $request, Logbook $logbook): RedirectResponse
    {
        $request->validate(['feedback' => 'required|string']);

        $logbook->update([
            'status' => 'rejected',
            'feedback' => $request->feedback
        ]);

        if ($logbook->student->user) {
            $logbook->student->user->notify(new LogbookRejectedNotification($logbook));
        }

        return back()->with('success', 'Logbook ditolak dan catatan terkirim.');
    }
}
