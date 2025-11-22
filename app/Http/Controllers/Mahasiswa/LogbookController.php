<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        $logbooks = Logbook::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        // Kelompokkan berdasarkan tanggal
        $groupedLogbooks = $logbooks->groupBy(function($item) {
            return Carbon::parse($item->date)->translatedFormat('l, d F Y');
        });

        $registration = $student->registrations()->latest()->first();

        return view('mahasiswa.logbook.create', compact('groupedLogbooks', 'registration'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;

        // Validasi Input
        $validator = Validator::make($request->all(), [
            'date'       => ['required', 'date', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'activity'   => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'end_time.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
            'date.before_or_equal' => 'Tidak boleh mengisi logbook untuk masa depan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Logbook::create([
            'student_id'  => $student->id,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'activity'    => $request->activity,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return redirect()->route('mahasiswa.logbook.index')->with('success', 'Logbook kegiatan berhasil ditambahkan!');
    }

    // Update Logbook
    public function update(Request $request, Logbook $logbook): RedirectResponse
    {
        if ($logbook->student_id !== Auth::user()->student->id) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }

        if ($logbook->status == 'approved') {
            return redirect()->back()->with('error', 'Logbook yang sudah disetujui tidak bisa diubah.');
        }

        $validator = Validator::make($request->all(), [
            'date'       => ['required', 'date', 'before_or_equal:' . Carbon::now()->format('Y-m-d')],
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'activity'   => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('edit_mode', true);
        }

        $logbook->update([
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'activity'    => $request->activity,
            'description' => $request->description,
            'status'      => 'pending',
            'feedback'    => null,
        ]);

        return redirect()->back()->with('success', 'Logbook berhasil diperbarui!');
    }

    public function destroy(Logbook $logbook): RedirectResponse
    {
        if ($logbook->student_id !== Auth::user()->student->id) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }

        if ($logbook->status == 'approved') {
            return redirect()->back()->with('error', 'Logbook yang sudah disetujui tidak bisa dihapus.');
        }

        $logbook->delete();

        return redirect()->back()->with('success', 'Logbook berhasil dihapus!');
    }
}
