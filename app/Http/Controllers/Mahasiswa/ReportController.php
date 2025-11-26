<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Notifications\NewReportNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $student = Auth::user()->student;
        $registration = $student->registrations()
            ->whereIn('application_status', ['approved', 'completed'])
            ->latest()
            ->first();

        if (!$registration) {
            return redirect()->route('student.dashboard');
        }

        return view('mahasiswa.report.create', compact('registration'));
    }

    public function update(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;
        $registration = $student->registrations()
            ->where('application_status', 'approved')
            ->latest()
            ->first();

        $request->validate([
            'file' => 'required|mimes:pdf|max:10240',
        ], [
            'file.required' => 'Silakan pilih file laporan.',
            'file.mimes' => 'Format laporan wajib PDF.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        if ($registration->report_file && Storage::disk('public')->exists($registration->report_file)) {
            Storage::disk('public')->delete($registration->report_file);
        }

        $fileName = 'LaporanAkhir_' . $student->nim . '_' . time() . '.pdf';
        $path = $request->file('file')->storeAs('laporan', $fileName, 'public');

        $registration->update([
            'report_file' => $path,
            'report_status' => 'submitted',
            'report_feedback' => null,
        ]);

        if ($registration->mentor && $registration->mentor->user) {
            $registration->mentor->user->notify(new NewReportNotification($registration));
        }

        return redirect()->back()->with('success', 'Laporan berhasil diupload. Menunggu review pembimbing.');
    }
}
