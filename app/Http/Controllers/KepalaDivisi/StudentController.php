<?php

namespace App\Http\Controllers\KepalaDivisi;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $query = Registration::with(['student', 'mentor'])
            ->where('division_id', $user->division_id)
            ->whereIn('application_status', ['approved', 'completed']);

        // Hitung Statistik
        $stats = [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('application_status', 'approved')->count(),
            'completed' => (clone $query)->where('application_status', 'completed')->count(),
        ];

        $students = $query->latest('start_date')->get();

        return view('kepala_divisi.student.create', compact('students', 'stats'));
    }

    public function show(Registration $registration): View
    {
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
