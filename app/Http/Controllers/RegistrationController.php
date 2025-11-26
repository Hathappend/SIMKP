<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationConfirmation;
use App\Models\Division;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use App\Notifications\NewRegistrationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create():View
    {
        $divisions = Division::orderBy('name')->get();

        return view('registration.create',  compact('divisions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Data Mahasiswa
            'full_name' => 'required|string|max:100',
            'nim' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
            'university' => 'required|string|max:100',
            'email' => 'required|email|max:100',

            // Data Pengajuan Magang
            'division_id' => 'required|exists:divisions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'internship_letter' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'kesbangpol_letter' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $internshipPath = null;
        if ($request->hasFile('internship_letter')) {
            $internshipPath = $request->file('internship_letter')->store('upload/surat-pengantar', 'public');
        }

        $kesbangpolPath = null;
        if ($request->hasFile('kesbangpol_letter')) {
            $kesbangpolPath = $request->file('kesbangpol_letter')->store('upload/surat-kesbangpol', 'public');
        }

        try {
            $registration = DB::transaction(function () use ($request, $internshipPath, $kesbangpolPath) {

                $student = Student::updateOrCreate(
                    ['nim' => $request->nim],
                    [
                        'name' => $request->full_name,
                        'email' => $request->email,
                        'university' => $request->university,
                        'study_program' => $request->study_program,
                    ]
                );

                // 2. Tambahkan 'return' di sini
                return Registration::create([
                    'student_id' => $student->id,
                    'division_id' => $request->division_id,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'internship_letter' => $internshipPath,
                    'kesbangpol_letter' => $kesbangpolPath,

                    'application_status' => 'pending',
                    'letter_status' => 'waiting',
                ]);
            });


            Mail::to($request->email)->send(new RegistrationConfirmation($request->full_name));

            $admins = User::role('admin')->get();
            Notification::send($admins, new NewRegistrationNotification($registration));

            return redirect()->back()->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk konfirmasi.');

        } catch (\Exception $e) {
             Storage::delete($internshipPath);
            Storage::delete($kesbangpolPath);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage());
        }
    }
}
