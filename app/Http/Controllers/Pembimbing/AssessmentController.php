<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    public function index()
    {
        $mentorId = Auth::user()->mentor->id;
        $today = Carbon::today();

        // Ambil data
        $students = Registration::with(['student', 'assessment'])
            ->where('mentor_id', $mentorId)
            ->whereIn('application_status', ['approved', 'completed'])
            ->get();

        // Filter Prioritas (Sisa < 7 hari & Belum dinilai)
        $priorityStudents = $students->filter(function ($reg) use ($today) {
            $endDate = Carbon::parse($reg->end_date);
            $isTime = $today->diffInDays($endDate, false) <= 7;
            $notGraded = !$reg->assessment;
            return $isTime && $notGraded;
        });

        $gradedStudents = $students->filter(fn($reg) => $reg->assessment);
        $ongoingStudents = $students->diff($priorityStudents)->diff($gradedStudents);

        return view('pembimbing.assessment.create', compact('priorityStudents', 'gradedStudents', 'ongoingStudents'));
    }

    public function create(Registration $registration)
    {
        if ($registration->mentor_id !== Auth::user()->mentor->id) {
            abort(403);
        }

        $registration->load(['student', 'members', 'logbooks', 'attendances']);

        $membersData = collect([]);

        $leaderNim = $registration->student->nim;

        $membersData->push((object)[
            'id' => null,
            'name' => $registration->student->name,
            'nim' => $leaderNim,
            'role' => 'Ketua',
            'logbook_count' => $registration->logbooks->where('status', 'approved')->count(),
            'attendance_count' => $registration->attendances->where('status', 'present')->count(),
            'existing_score' => \App\Models\Assessment::where('registration_id', $registration->id)
                ->whereNull('registration_member_id')
                ->first()
        ]);

        // --- DATA ANGGOTA (Dengan Pengecekan Duplikat) ---
        foreach($registration->members as $member) {

            // Jika NIM anggota SAMA dengan NIM Ketua, lewati (jangan ditampilkan lagi)
            if (trim($member->nim) == trim($leaderNim)) {
                continue;
            }

            // Ambil nilai anggota
            $existingScore = \App\Models\Assessment::where('registration_id', $registration->id)
                ->where('registration_member_id', $member->id)
                ->first();

            $membersData->push((object)[
                'id' => $member->id,
                'name' => $member->name,
                'nim' => $member->nim,
                'role' => 'Anggota',
                'logbook_count' => $registration->logbooks->where('status', 'approved')->count(),
                'attendance_count' => $registration->attendances->where('status', 'present')->count(),
                'existing_score' => $existingScore
            ]);
        }

        return view('pembimbing.assessment.add', compact('registration', 'membersData'));
    }

    public function store(Request $request, Registration $registration)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*.discipline' => 'required|integer|min:0|max:100',
            'scores.*.technical' => 'required|integer|min:0|max:100',
            'scores.*.performance' => 'required|integer|min:0|max:100',
            'scores.*.initiative' => 'required|integer|min:0|max:100',
            'scores.*.personality' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->scores as $key => $val) {

            $memberId = ($key === 'ketua') ? null : $key;

            if ($memberId) {
                $member = \App\Models\RegistrationMember::find($memberId);
                $studentName = $member->name;
                $nim = $member->nim;
            } else {
                $studentName = $registration->student->name;
                $nim = $registration->student->nim;
            }

            // Hitung Nilai & Grade (Tetap sama)
            $avg = ($val['discipline'] + $val['technical'] + $val['performance'] + $val['initiative'] + $val['personality']) / 5;

            $grade = match(true) {
                $avg >= 85 => 'A',
                $avg >= 75 => 'B',
                $avg >= 60 => 'C',
                $avg >= 50 => 'D',
                default => 'E',
            };

            $assessment = Assessment::updateOrCreate(
                [
                    'registration_id' => $registration->id,
                    'registration_member_id' => $memberId
                ],
                [
                    'score_discipline' => $val['discipline'],
                    'score_technical' => $val['technical'],
                    'score_performance' => $val['performance'],
                    'score_initiative' => $val['initiative'],
                    'score_personality' => $val['personality'],
                    'final_score' => $avg,
                    'grade' => $grade,
                    'notes' => $val['notes'] ?? null,

                    'certificate_number' => 'SERT/' . date('Y') . '/' . $nim . '/' . Str::upper(Str::random(4))
                ]
            );

            $data = [
                'assessment' => $assessment,
                'studentName' => $studentName,
                'divisionName' => $registration->division->name,
                'startDate' => $registration->start_date,
                'endDate' => $registration->end_date
            ];

            $pdf = Pdf::loadView('pdf.certificate', $data);
            $pdf->setPaper('a4', 'landscape');

            $path = 'sertifikat/' . $nim . '_Sertifikat.pdf';
            Storage::disk('public')->put($path, $pdf->output());

            $assessment->update(['certificate_path' => $path]);
        }

        $registration->update(['application_status' => 'completed']);

        return redirect()->route('pembimbing.penilaian.index')->with('success', 'Nilai berhasil disimpan dan Sertifikat telah diterbitkan.');
    }

    public function show(Registration $registration)
    {
        if ($registration->mentor_id !== Auth::user()->mentor->id) {
            abort(403);
        }

        $registration->load(['student', 'assessment', 'members.assessment']);

        return view('pembimbing.assessment.show', compact('registration'));
    }
}
