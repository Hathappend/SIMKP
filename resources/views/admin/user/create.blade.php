@extends('layouts.app')

@section('content')

    <div x-data="{
        modalTambah: {{ $errors->store->any() ? 'true' : 'false' }},
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalHapus: false,
        selectedUser: {{ $errors->update->any() ? json_encode(array_merge(old(), ['id' => old('id')])) : '{}' }},
        deleteUrl: '',
        editUrl: '{{ $errors->update->any() ? old('original_url', route('admin.user.update', ['user' => old('id')])) : '' }}',
        role_name: ''
     }"
         class="min-h-screen">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kelola Pengguna</h1>
                <p class="mt-1 text-sm text-gray-500">Manajemen akun sistem (Admin, Kadiv, Pembimbing, Mahasiswa).</p>
            </div>

            {{-- [BARU] STATISTIK USER --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                {{-- Admin --}}
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase">Admin</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['admin'] }}</h3>
                    </div>
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                {{-- Kadiv --}}
                <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-400 uppercase">Ka. Divisi</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['kadiv'] }}</h3>
                    </div>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                </div>

                {{-- Pembimbing --}}
                <div class="bg-white p-4 rounded-xl border border-purple-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-purple-400 uppercase">Pembimbing</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['mentor'] }}</h3>
                    </div>
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                </div>

                {{-- Mahasiswa --}}
                <div class="bg-white p-4 rounded-xl border border-green-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-400 uppercase">Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $stats['student'] }}</h3>
                    </div>
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                </div>
            </div>

            {{-- SEARCH + FILTER + BUTTON --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['name', 'email', 'role_name', 'division_name'];
                        $filters = [
                            [
                                'key' => 'role_name',
                                'label' => 'Role',
                                'type' => 'checkbox-list',
                                'options' => [
                                    ['value' => 'admin', 'label' => 'Admin'],
                                    ['value' => 'kepala_divisi', 'label' => 'Kepala Divisi'],
                                    ['value' => 'pembimbing', 'label' => 'Pembimbing'],
                                    ['value' => 'mahasiswa', 'label' => 'Mahasiswa'],
                                ]
                            ],
                            [
                                'key' => 'division_name',
                                'label' => 'Divisi',
                                'type' => 'checkbox-list',
                                'options' => $divisions->map(fn($d) => ['value' => strtolower($d->name), 'label' => $d->name])->values()->toArray()
                            ],
                        ];
                    @endphp

                    <x-filter-bar :fields="$fields" :filters="$filters" table="#usersTable" />

                    <button
                        @click="modalTambah = true"
                        class="h-10 inline-flex items-center justify-center px-4 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="usersTable" class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-16">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Divisi / Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition group"
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role_name="{{ strtolower($user->getRoleNames()->first() ?? '') }}"
                            data-division_name="{{ strtolower($user->division->name ?? '-') }}">

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>

                            {{-- Kolom User (Nama + Email) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-xs mr-3 border border-gray-200">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Role (Color Coded) --}}
                            <td class="px-6 py-4">
                                @php $role = $user->getRoleNames()->first(); @endphp
                                @if($role == 'admin')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-800 text-white border border-gray-600">Admin</span>
                                @elseif($role == 'kepala_divisi')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">Kepala Divisi</span>
                                @elseif($role == 'pembimbing')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">Pembimbing</span>
                                @elseif($role == 'mahasiswa')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">Mahasiswa</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">-</span>
                                @endif
                            </td>

                            {{-- Kolom Divisi --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $user->division->name ?? "-" }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">

                                    {{-- Tombol Edit --}}
                                    <button @click="
                                        modalEdit = true;
                                        selectedUser = {{ $user->load('roles') }};
                                        editUrl = '{{ route('admin.user.update', $user->id) }}';
                                        role_name = (selectedUser.roles && selectedUser.roles.length > 0) ? selectedUser.roles[0].name : '';
                                        " class="p-2 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button @click="
                                        modalHapus = true;
                                        selectedUser = {{ $user->load('roles') }};
                                        deleteUrl = '{{ route('admin.user.destroy', $user->id) }}';
                                        " class="p-2 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="100%" class="py-12 text-center text-gray-500">Belum ada data user.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div
                x-show="modalTambah"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;">

                <div @click.away="modalTambah = false"
                     x-data="{
                         role_name: '{{ old('role_name', $roles->first()->name ?? '') }}',
                         generatedPassword: '',
                         showGenerated: false,
                         passwordField: '{{ old('password') }}',
                         confirmPasswordField: '{{ old('password_confirmation') }}',

                         generate() {
                            // 1. Panggil fungsi global yang sudah kita buat
                            const pass = window.generateRandomPassword();

                            // 2. Set state di dalam Alpine
                            this.generatedPassword = pass;
                            this.passwordField = pass;
                            this.confirmPasswordField = pass;
                            this.showGenerated = true;
                        }
                      }"

                     class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">

                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf

                        {{-- Penanda untuk kantung error 'store' --}}
                        <input type="hidden" name="form_action" value="store">

                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">Tambah User Baru</h3>
                            <p class="text-gray-600 -mt-3">Buat akun baru untuk Admin & Kepala Divisi.</p>

                            {{-- Field Nama Lengkap --}}
                            <div>
                                <label for="name_store" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name_store" name="name"
                                       value="{{ old('name') }}"
                                       class="w-full p-3 border rounded-lg @error('name', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                       required>
                                @error('name', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Field Email --}}
                            <div>
                                <label for="email_store" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email_store" name="email"
                                       value="{{ old('email') }}"
                                       class="w-full p-3 border rounded-lg @error('email', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                       required>
                                @error('email', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Field Password (Grid) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="password_store" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" id="password_store" name="password" x-model="passwordField"
                                           class="w-full p-3 border rounded-lg @error('password', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                           required>
                                    @error('password', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" x-model="confirmPasswordField"
                                           class="w-full p-3 border rounded-lg @error('password', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" required>
                                </div>
                            </div>

                            {{-- Tombol Generator & Tampilan Password --}}
                            <div class="space-y-2">
                                <button @click.prevent="generate()" type="button" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Generate Password Acak
                                </button>

                                {{-- Area ini akan muncul saat password di-generate --}}
                                <div x-show="showGenerated" x-transition
                                     class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-center">
                                    <span class="text-sm text-blue-700">Password Baru (sudah disalin ke email):</span>
                                    <strong class="block text-lg font-mono text-blue-900 tracking-wider" x-text="generatedPassword"></strong>
                                    <p class="text-xs text-gray-500 mt-1">Password ini juga akan dikirim ke email user.</p>
                                </div>
                            </div>

                            {{-- Field Role --}}
                            <div>
                                <label for="role_store" class="block text-sm font-medium text-gray-700 mb-2">Peran (Role)</label>
                                <select id="role_store" name="role_name" x-model="role_name"
                                        class="w-full p-3 border rounded-lg @error('role_name', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role_name') == $role->name ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_name', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Field Divisi --}}
                            <div x-show="role_name === 'kepala_divisi'" x-transition>
                                <label for="division_id_store" class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                                <select id="division_id_store" name="division_id"
                                        class="w-full p-3 border rounded-lg @error('division_id', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                                    <option value="">Pilih Divisi...</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $division->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division_id', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        {{-- Footer Modal --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button @click="modalTambah = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div
                x-show="modalEdit"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;"
            >

                <div @click.away="modalEdit = false"
                     class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">

                    <form :action="editUrl" method="POST">
                        @csrf
                        @method('PUT')

                        {{--
                          Input tersembunyi (hidden) ini penting agar:
                          1. 'old('id')' berfungsi saat validasi gagal.
                          2. Controller Spatie tahu role mana yang harus di-cek.
                        --}}
                        <input type="hidden" name="id" :value="selectedUser.id">

                        <div class="p-6 space-y-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-1">Edit Pengguna</h3>
                            <p class="text-gray-600 -mt-3">Perbarui data user.</p>

                            {{-- Field Nama Lengkap --}}
                            <div>
                                <label for="name_edit" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name_edit" name="name"
                                       value="{{ old('name') }}"
                                       :value="{{ $errors->update->any() ? old('name') : 'selectedUser.name' }}"
                                       class="w-full p-3 border rounded-lg @error('name', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                       required>
                                @error('name', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Field Email --}}
                            <div>
                                <label for="email_edit" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email_edit" name="email"
                                       value="{{ old('email') }}"
                                       :value="{{ $errors->update->any() ? old('email') : 'selectedUser.email' }}"
                                       class="w-full p-3 border rounded-lg @error('email', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                       required>
                                @error('email', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <p class="text-sm text-gray-500 -mb-2">Kosongkan password jika tidak ingin mengubahnya.</p>

                            {{-- Field Password --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="password_edit" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" id="password_edit" name="password"
                                           class="w-full p-3 border rounded-lg @error('password', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                                    @error('password', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation_edit" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation_edit" name="password_confirmation"
                                           class="w-full p-3 border rounded-lg border-gray-300">
                                </div>
                            </div>

                            {{-- Field Role --}}
                            <div>
                                <label for="role_edit" class="block text-sm font-medium text-gray-700 mb-2">Peran (Role)</label>
                                <select id="role_edit" name="role_name" x-model="role_name"
                                        class="w-full p-3 border rounded-lg @error('role_name', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{  ucwords(str_replace('_', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                                @error('role_name', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Field Divisi --}}
                            <div x-show="role_name === 'kepala_divisi'" x-transition>
                                <label for="division_id_edit" class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                                <select id="division_id_edit" name="division_id"
                                        class="w-full p-3 border rounded-lg @error('division_id', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror">
                                    <option value="">Pilih Divisi...</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}"
                                                :selected="selectedUser.division_id == {{ $division->id }} && '{{ old('division_id') }}' == ''"
                                            {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                            {{  ucwords(str_replace('_', ' ', $division->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division_id', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Footer Modal --}}
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button @click="modalEdit = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div
                x-show="modalHapus"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;"
            >
                <div @click.away="modalHapus = false" class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="ml-4 text-left">
                                    <h3 class="text-xl font-semibold text-gray-900">Hapus User?</h3>
                                    <p class="text-gray-600 mt-2">
                                        Anda yakin ingin menghapus user <strong x-text="selectedDivisi.name"></strong>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit" class="inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                                Ya, Hapus
                            </button>
                            <button @click="modalHapus = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
