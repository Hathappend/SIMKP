<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $user = Auth::user(); // Ini adalah Kadiv

        // Ambil pengajuan yang:
        // 1. Statusnya 'waiting' (sudah diteruskan admin)
        // 2. Divisi tujuannya SAMA dengan divisi Kadiv
        $applications = Registration::where('application_status', 'waiting')
            // Asumsi di tabel applications ada kolom division_id (relasi ke divisi tujuan)
            // Jika belum ada, Anda harus menambahkannya atau relasi via user
            ->where('division_id', $user->division_id)
            ->latest()
            ->get();

        return view('kepala_divisi.registration.create', compact('applications'));
    }

    public function show(Registration $registration): View
    {
        // Pastikan Kadiv hanya bisa lihat pengajuan divisinya sendiri (Security)
        if ($registration->division_id !== Auth::user()->division_id) {
            abort(403, 'Akses ditolak.');
        }
        // Ambil daftar mentor DI DIVISI INI SAJA untuk dropdown
        $mentors = Mentor::where('division_id', Auth::user()->division_id)->get();

        return view('kepala_divisi.registration.show', compact('registration', 'mentors'));
    }

    public function approve(Request $request, Registration $registration): RedirectResponse
    {
        // Validasi: Mentor harus dipilih
        $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
        ], [
            'mentor_id.required' => 'Anda wajib menunjuk pembimbing.',
        ]);

        // Update Data
        $registration->update([
            'application_status' => 'approved', // Resmi Diterima
            'mentor_id' => $request->mentor_id, // Simpan Pembimbing
            // Opsional: 'approved_at' => now(),
        ]);

        // TODO: Kirim Email "Selamat Anda Diterima" ke Mahasiswa
        // TODO: Kirim Email Notifikasi ke Mentor yang dipilih

        return redirect()->route('kadiv.pengajuan.index')
            ->with('success', 'Pengajuan diterima dan pembimbing telah ditugaskan.');
    }

    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate(['rejection_note' => 'required']);

        $registration->update([
            'application_status' => 'rejected',
            'rejection_note' => $request->rejection_note
        ]);

        return redirect()->route('kadiv.pengajuan.index')
            ->with('success', 'Pengajuan telah ditolak.');
    }
}
