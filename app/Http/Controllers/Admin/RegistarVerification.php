<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RejectionMail;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegistarVerification extends Controller
{
    public function create(): View
    {
        $applications = Registration::with(['student', 'division'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pengajuan_magang', compact('applications'));
    }

    public function show($id): View
    {
        $application = Registration::with(['student', 'division'])->findOrFail($id);

        return view('admin.detail_pengajuan_magang', compact('application'));
    }

    public function forward(Registration $registration): RedirectResponse
    {
        if ($registration->application_status !== 'pending') {
            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan. Status pengajuan bukan Pending.');
        }

        $registration->update([
            'application_status' => 'waiting'
        ]);

        return redirect()->back()->with('success', 'Pengajuan telah disetujui dan diteruskan ke Kepala Divisi.');
    }

    /**
     * Menolak pengajuan (Admin).
     */
    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate([
            'rejection_note' => 'required|string|min:10',
        ]);

        if ($registration->application_status !== 'pending') {
            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan.');
        }

        $registration->update([
            'application_status' => 'rejected',
            'rejection_note' => $request->input('rejection_note')
        ]);

        try {

            $studentEmail = $registration->student->email;

            Mail::to($studentEmail)->send(new RejectionMail($registration));

        } catch (\Exception $e) {
            // Jika email gagal, kembalikan dengan pesan error tapi data sudah tersimpan
            return redirect()->back()->with('warning', 'Pengajuan ditolak, tetapi gagal mengirim email notifikasi. Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan telah ditolak dan notifikasi dikirim.');
    }

    public function archive(Registration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()->route('admin.registration.create')
            ->with('success', 'Pengajuan telah diarsipkan.');
    }
}
