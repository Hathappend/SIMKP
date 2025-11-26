<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Notifications\ReportApprovedNotification;
use App\Notifications\ReportRevisionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $mentorId = Auth::user()->mentor->id;

        // Ambil mahasiswa bimbingan yang status magangnya aktif/selesai
        $reports = Registration::with(['student'])
            ->where('mentor_id', $mentorId)
            ->whereIn('application_status', ['approved', 'completed'])
            // Urutkan Yang 'submitted' paling atas
            ->orderByRaw("FIELD(report_status, 'submitted', 'revision', 'approved') DESC")
            ->latest('updated_at')
            ->get();

        $pendingReports = $reports->where('report_status', 'submitted');
        $historyReports = $reports->where('report_status', '!=', 'submitted');

        return view('pembimbing.report.create', compact('reports', 'pendingReports', 'historyReports'));
    }

    public function show(Registration $registration): View
    {
        if ($registration->mentor_id !== Auth::user()->mentor->id) {
            abort(403);
        }

        return view('pembimbing.report.show', compact('registration'));
    }

    public function approve(Registration $registration): RedirectResponse
    {
        $registration->update([
            'report_status' => 'approved',
            'report_feedback' => null
        ]);

        if ($registration->student->user) {
            $registration->student->user->notify(new ReportApprovedNotification());
        }

        return back()->with('success', 'Laporan disetujui.');
    }

    public function revise(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate(['feedback' => 'required|string|min:5']);

        $registration->update([
            'report_status' => 'revision',
            'report_feedback' => $request->feedback
        ]);

        if ($registration->student->user) {
            $registration->student->user->notify(new ReportRevisionNotification($registration));
        }

        return back()->with('success', 'Laporan dikembalikan untuk revisi.');
    }
}
