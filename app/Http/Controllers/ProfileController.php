<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\ConfirmPasswordChange;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'photo' => 'nullable|image|max:2048',
        ];

        if ($user->hasRole('mahasiswa')) {
            $rules['phone_number'] = 'nullable|string|max:20';
            $rules['address'] = 'nullable|string|max:500';
        }

        if ($user->hasRole('pembimbing')) {
            $rules['position'] = 'required|string|max:100';
            $rules['nip'] = 'nullable|string|max:50';
            $rules['phone_number'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Upload Foto Profil (Jika ada)
        if ($request->hasFile('photo')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('photo')->store('upload/avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        //  MAHASISWA
        if ($user->hasRole('mahasiswa') && $user->student) {
            $user->student->update([
                'name' => $validated['name'],
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);
        }

        // PEMBIMBING
        if ($user->hasRole('pembimbing') && $user->mentor) {
            $user->mentor->update([
                'name' => $validated['name'],
                'position' => $validated['position'],
                'nip' => $request->nip,
                'phone_number' => $request->phone_number,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Generate token unik
        $user->update([
            'temp_password' => Hash::make($request->password),
            'password_change_token' => Str::random(60),
        ]);

        try {
            Mail::to($user->email)->send(new ConfirmPasswordChange($user));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi.');
        }

        return back()->with('success', 'Link konfirmasi telah dikirim ke email Anda. Silakan cek inbox/spam.');
    }

    /**
     * 2. Verifikasi Link dari Email (Eksekusi Perubahan)
     */
    public function verifyPassword($token): RedirectResponse
    {
        $user = \App\Models\User::where('password_change_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $user->update([
            'password' => $user->temp_password,
            'temp_password' => null,
            'password_change_token' => null
        ]);

        Auth::logout();

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login kembali dengan password baru.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
