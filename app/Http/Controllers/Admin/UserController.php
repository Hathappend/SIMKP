<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewUserWelcome;
use App\Mail\UserProfileUpdated;
use App\Models\Division;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'division', 'mentor', 'student'])->latest()->get();
        $divisions = Division::orderBy('name')->get();
        $roles = Role::whereIn('name', ['admin', 'kepala_divisi'])->get();
        $getAllRoles = Role::all();

        // Hitung Statistik
        $stats = [
            'total' => $users->count(),
            'admin' => $users->filter(fn($u) => $u->hasRole('admin'))->count(),
            'kadiv' => $users->filter(fn($u) => $u->hasRole('kepala_divisi'))->count(),
            'mentor' => $users->filter(fn($u) => $u->hasRole('pembimbing'))->count(),
            'student' => $users->filter(fn($u) => $u->hasRole('mahasiswa'))->count(),
        ];

        return view('admin.user.create', compact('users', 'divisions', 'roles', 'getAllRoles', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role_name' => 'required|string|exists:roles,name', // <-- Validasi nama role
            'division_id' => 'nullable|required_if:role_name,kepala_divisi|exists:divisions,id',
        ], [
            'division_id.required_if' => 'Divisi wajib diisi jika perannya adalah Kepala Divisi.',
            'role_name.required' => 'Peran (Role) wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'store')->withInput();
        }

        $temporaryPassword = $request->password;

        $data = $request->except('password', 'password_confirmation', 'role_name');
        $data['password'] = Hash::make($temporaryPassword);
        $data['must_change_password'] = true;

        if ($request->role_name === 'admin') {
            $data['division_id'] = null;
        }

        $user = User::create($data);
        $user->assignRole($request->role_name);

        if ($request->filled('mentor_id')) {
            $mentor = Mentor::find($request->mentor_id);
            if ($mentor) {
                $mentor->update([
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
        }

        try {
            Mail::to($user->email)->send(new NewUserWelcome($user, $temporaryPassword));
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan, TAPI email notifikasi gagal dikirim.');
        }

        return redirect()->back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|' . Rule::unique('users')->ignore($user->id),
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role_name' => 'required|string|exists:roles,name',
            'division_id' => 'nullable|required_if:role_name,kadiv|exists:divisions,id',
        ], [
            'division_id.required_if' => 'Divisi wajib diisi jika perannya adalah Kepala Divisi.',
            'role_name.required' => 'Peran (Role) wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'update')->withInput();
        }

        $data = $request->except('password', 'password_confirmation', 'role_name');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $data['must_change_password'] = true;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->role_name === 'admin') {
            $data['division_id'] = null;
        }

        $user->update($data);
        $user->syncRoles([$request->role_name]);

        if ($user->mentor) {
            $user->mentor->update([
                'email' => $user->email,
                'name'  => $user->name
            ]);
        }

        try {
            Mail::to($user->email)->send(new UserProfileUpdated($user));
        } catch (\Exception $e) {
            // Abaikan jika email gagal, proses utama sudah berhasil
        }

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {

        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            $user->delete();

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus. Pengguna ini mungkin masih terikat dengan data lain.');
        }

        return redirect()->back()->with('success', 'Pengguna telah diarsipkan.');
    }
}
