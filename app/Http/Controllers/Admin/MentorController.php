<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class MentorController extends Controller
{
    public function index(): View
    {
        $mentors = Mentor::with('division')
            ->withCount(['students' => function ($query) {
                $query->where('application_status', 'approved');
            }])
            ->latest()
            ->get();

        $divisions = Division::orderBy('name')->get();

        return view('admin.pembimbing.create', compact('mentors', 'divisions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ], [
            'division_id.required' => 'Divisi wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'store')->withInput();
        }

        Mentor::create($request->all());

        return redirect()->back()->with('success', 'Pembimbing baru berhasil ditambahkan.');
    }

    public function update(Request $request, Mentor $mentor): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput();
        }

        $mentor->update($request->all());

        return redirect()->back()->with('success', 'Data pembimbing berhasil diperbarui.');
    }

    public function destroy(Mentor $mentor): RedirectResponse
    {
        try {
            $mentor->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pembimbing.');
        }

        return redirect()->back()->with('success', 'Pembimbing telah dihapus.');
    }
}
