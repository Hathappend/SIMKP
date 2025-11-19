<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LetterController extends Controller
{
    public function index(): View
    {
        $applications = Registration::with(['student', 'division', 'mentor', 'members'])
            ->where('application_status', 'approved')
            ->orderBy('letter_status', 'asc')
            ->latest()
            ->get();

        return view('admin.surat.create', compact('applications'));
    }

    /**
     * Simpan Nomor Surat & Ubah Status jadi Completed
     */
    public function update(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate([
            'letter_number' => 'required|string|max:100',
            'letter_date'   => 'required|date',
            'campus_letter_number' => 'required|string',
            'students'      => 'required|array|min:1',
            'students.*.name' => 'required|string',
            'students.*.nim'  => 'required|string',
            'students.*.study_program' => 'required|string',
        ]);

        $registration->fill([
            'letter_number' => $request->letter_number,
            'letter_date'   => $request->letter_date,
        ]);

        // Hapus anggota lama dulu agar tidak duplikat saat diedit
        $registration->members()->delete();

        foreach ($request->students as $studentData) {
            $registration->members()->create([
                'name' => $studentData['name'],
                'nim'  => $studentData['nim'],
                'study_program' => $studentData['study_program'],
            ]);
        }

        $data = [
            'registration' => $registration,
            'campusLetterNumber' => $request->campus_letter_number
        ];

        // Refresh relasi 'members' agar data terbaru terambil.
        $registration->load('members');

        $pdf = Pdf::loadView('pdf.surat_balasan', $data);
        $pdf->setPaper('legal', 'portrait');

        $fileName = 'Surat_Balasan_' . $registration->student->nim . '_' . $registration->id . '.pdf';
        $path = 'surat-balasan/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        $registration->update([
            'letter_number' => $request->letter_number,
            'letter_date'   => $request->letter_date,
            'reply_letter_path' => $path,
            'letter_status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Nomor surat berhasil disimpan.');
    }

    /**
     * Cetak PDF / Preview
     */
    public function show(Registration $registration): RedirectResponse
    {
        if (!$registration->reply_letter_path) {
            return redirect()->back()->with('error', 'Surat belum dibuat.');
        }

        if (!Storage::disk('public')->exists($registration->reply_letter_path)) {
            return redirect()->back()->with('error', 'File surat fisik tidak ditemukan.');
        }

        return redirect(Storage::url($registration->reply_letter_path));
    }
}
