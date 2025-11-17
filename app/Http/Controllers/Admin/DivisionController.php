<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DivisionController extends Controller
{
    public function index(): View
    {
        $divisions = Division::latest()->get();

        return view('admin.division.create', compact('divisions'));
    }

    public function store(Request $request): RedirectResponse
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:divisions,name'
        ], [
            'name.required' => 'Nama divisi tidak boleh kosong.',
            'name.unique' => 'Nama divisi tersebut sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'store')
                ->withInput();
        }

        Division::create($request->all());

        return redirect()->back()->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    public function update(Request $request, Division $division): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:divisions,name',
            ]
        ], [
            'name.required' => 'Nama divisi tidak boleh kosong.',
            'name.unique' => 'Nama divisi tersebut sudah ada.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'update')
                ->withInput();
        }

        if ($request->name === $division->name) {
            return redirect()->back();
        }

        $division->update($request->all());
        return redirect()->back()->with('success', 'Nama divisi berhasil diperbarui.');
    }

    public function destroy(Division $division): RedirectResponse
    {

        try {
            $division->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus. Pastikan tidak ada pengguna yang terikat dengan divisi ini.');
        }

        return redirect()->back()->with('success', 'Divisi telah dihapus.');
    }
}
