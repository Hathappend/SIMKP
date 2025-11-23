<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationApproved;
use App\Mail\RejectionMail;
use App\Models\Mentor;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $applications = Registration::with('student')
            ->where('application_status', 'waiting')
            ->where('division_id', $user->division_id)
            ->latest()
            ->get();

        return view('kepala_divisi.registration.create', compact('applications'));
    }

    public function show(Registration $registration): View
    {
        if ($registration->division_id !== Auth::user()->division_id) {
            abort(403, 'Akses ditolak. Ini bukan pengajuan divisi Anda.');
        }

        $registration->load('student');

        $mentors = Mentor::where('division_id', Auth::user()->division_id)->orderBy('name')->get();

        return view('kepala_divisi.registration.show', compact('registration', 'mentors'));
    }

    public function approve(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate([
            'mentor_id' => 'required|exists:mentors,id',
        ], [
            'mentor_id.required' => 'Anda wajib menunjuk pembimbing untuk menerima mahasiswa ini.',
        ]);

        $registration->update([
            'application_status' => 'approved',
            'mentor_id' => $request->mentor_id,
            // 'approved_at' => now(),
        ]);

        $student = $registration->student;
        $rawPassword = null;

        if (!$student->user_id) {

            $rawPassword = Str::random(8);

            // Buat User Baru
            try {
                $user = User::create([
                    'name' => $student->name,
                    'email' => $student->email,
                    'password' => Hash::make($rawPassword),
                    'must_change_password' => true,
                ]);

                $user->assignRole('mahasiswa');

                $student->update(['user_id' => $user->id]);

            } catch (\Exception $e) {
               // error
            }
        }

        // Kirim Email
        try {
            $studentEmail = $registration->student->email;
            Mail::to($studentEmail)->send(new RegistrationApproved($registration, $rawPassword));

        } catch (\Exception $e) {
            return redirect()->route('kadiv.pengajuan.index')
                ->with('success', 'Pengajuan diterima, namun email gagal terkirim.');
        }

        return redirect()->route('kadiv.pengajuan.index')
            ->with('success', 'Pengajuan diterima dan pembimbing telah ditugaskan.');
    }

    public function reject(Request $request, Registration $registration): RedirectResponse
    {
        $request->validate(['rejection_note' => 'required|string|min:10']);

        $registration->update([
            'application_status' => 'rejected',
            'rejection_note' => $request->rejection_note
        ]);

        try {
            $studentEmail = $registration->student->email;
            Mail::to($studentEmail)->send(new RejectionMail($registration));
        } catch (\Exception $e) {
            // Abaikan error email, yang penting data tersimpan
        }

        return redirect()->route('kadiv.pengajuan.index')
            ->with('success', 'Pengajuan telah ditolak.');
    }
}
