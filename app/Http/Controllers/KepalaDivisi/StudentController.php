<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Kadiv

        // Ambil mahasiswa yang ada di DIVISI Kadiv ini
        // Status: Approved (Sedang Magang) & Completed (Selesai/Alumni)
        $students = Registration::with(['student', 'mentor'])
            ->where('division_id', $user->division_id)
            ->whereIn('application_status', ['approved', 'completed'])
            ->latest('start_date')
            ->get();

        return view('kepala_divisi.student.create', compact('students'));
    }

    public function show(Registration $registration)
    {
        // Security Check: Pastikan satu divisi
        if ($registration->division_id !== Auth::user()->division_id) {
            abort(403);
        }

        $registration->load(['student', 'mentor', 'logbooks', 'attendances']);

        // Statistik Ringkas
        $totalLogbook = $registration->logbooks->count();
        $totalAttendance = $registration->attendances->whereIn('status', ['present', 'sick', 'permission'])->count();

        return view('kepala_divisi.student.show', compact('registration', 'totalLogbook', 'totalAttendance'));
    }
}
