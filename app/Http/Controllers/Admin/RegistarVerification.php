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
    public function create():View
    {
        $applications =  Registration::orderBy('created_at', 'desc')->get();

        return view('admin.pengajuan_magang', compact('applications'));
    }

    public function show($id): View
    {
        $application = Registration::find($id);

        return view('admin.detail_pengajuan_magang', compact('application'));
    }

    public function forward(Registration $registration): RedirectResponse
    {
        if ($registration->application_status === 'pending') {

            $registration->application_status = 'waiting';

            $registration->save();

            // 4. (Opsional) Kirim notifikasi ke Kepala Divisi
            // ... logika notifikasi Anda di sini ...

        } else {

            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan.');
        }

        return redirect()->back()->with('success', 'Pengajuan telah disetujui dan diteruskan ke Kepala Divisi.');
    }

    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate([
            'rejection_note' => 'required|string|min:10',
        ]);

        if ($registration->application_status !== 'pending') {
            return redirect()->back()->with('error', 'Tindakan tidak dapat dilakukan.');
        }

        // 1. Update status ke 'rejected'
        $registration->application_status = 'rejected';
        $registration->rejection_note = $request->input('rejection_note');
        $registration->save();

        // 2. Kirim email 'Ditolak' ke mahasiswa
        try {
            Mail::to($registration->email)->send(new RejectionMail($registration));
        } catch (\Exception $e) {
            // Jika email gagal, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Gagal mengirim email. Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pengajuan telah ditolak.');
    }

    public function archive(Registration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()->route('admin.registration.create')
            ->with('success', 'Pengajuan telah diarsipkan.');
    }
}
