<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        $todayAttendance = Attendance::where('student_id', $student->id)
            ->where('date', Carbon::today())
            ->first();

        $history = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        $registration = $student->registrations()->latest()->first();

        return view('mahasiswa.attendance.create', compact('todayAttendance', 'history', 'registration'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;

        $exists = Attendance::where('student_id', $student->id)
            ->where('date', Carbon::today())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Anda sudah mengisi kehadiran hari ini.');
        }

        $request->validate([
            'status' => 'required|in:present,permission,sick',
            'notes'  => 'required|string|max:255',
            'proof_file' => 'nullable|image|max:2048',
        ], [
            'notes.required' => 'Harap isi keterangan kegiatan atau alasan.'
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('attendance_proofs', 'public');
        }

        Attendance::create([
            'student_id' => $student->id,
            'date' => Carbon::today(),
            'check_in_time' => Carbon::now()->format('H:i:s'),
            'status' => $request->status,
            'notes' => $request->notes,
            'proof_file' => $proofPath,
            'validation_status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Kehadiran berhasil dicatat!');
    }
}
