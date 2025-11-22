<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function update(Request $request, RegistrationMember $member): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'nim' => 'required|string',
            'study_program' => 'required|string',
        ]);

        $member->update($request->all());

        return back()->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    public function destroy(RegistrationMember $member): RedirectResponse
    {
        $member->delete();
        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }
}
