<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create():View
    {
        return view('registration.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'nim' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
            'email' => 'required|email',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'internship_letter' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'kesbangpol_letter' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->only([
            'full_name', 'nim', 'study_program', 'email',
            'start_date', 'end_date'
        ]);

        if ($request->hasFile('internship_letter')) {
            $data['internship_letter'] = $request->file('internship_letter')->store('upload/surat-pengantar', 'public');
        }

        if ($request->hasFile('kesbangpol_letter')) {
            $data['kesbangpol_letter'] = $request->file('kesbangpol_letter')->store('upload/surat-kesbangpol', 'public');
        }

        Registration::create($data);

        // Kirim email konfirmasi pendaftaran
        Mail::to($request->email)->send(new RegistrationConfirmation($data['full_name']));

        return redirect()->back()->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk konfirmasi.');
    }
}
