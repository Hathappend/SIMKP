<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {

        $students = Student::with(['user', 'registrations.division'])
            ->latest()
            ->get();

        // Statistik untuk Card
        $stats = [
            'total' => $students->count(),
            'active_magang' => $students->filter(function($s) {
                return $s->registrations->first()?->application_status === 'approved';
            })->count(),
            'completed_magang' => $students->filter(function($s) {
                return $s->registrations->first()?->application_status === 'completed';
            })->count(),
            'universities' => $students->unique('university')->count(),
        ];

        return view('admin.student.create', compact('students', 'stats'));
    }

    public function store(Request $request):RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:students,nim',
            'email' => 'required|email|max:255|unique:students,email',
            'university' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        Student::create($request->all());

        return redirect()->back()->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'university' => 'required|string|max:255',
            'study_program' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $student->update($request->all());

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        try {
            $student->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus. Data mungkin terikat dengan pengajuan magang.');
        }

        return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    public function show(Student $student): View
    {
        $student->load([
            'user',
            'registrations.division',
            'registrations.members',
            'registrations.assessment',
            'registrations.members.assessment'
        ]);

        return view('admin.student.show', compact('student'));
    }
}
