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
         class="md:mx-12 mt-10 mb-16">

        {{-- HEADER & FILTER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">

            {{-- Judul --}}
            <div class="w-full md:w-auto">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Pengguna</h1>
                <p class="mt-1 text-sm text-gray-500">Manajemen akun untuk seluruh pengguna.</p>
            </div>

            {{-- SEARCH + FILTER (Alpine Component) --}}
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @php
                    $fields = ['name', 'email', 'role_name', 'division_name'];

                    // Konfigurasi Filter
                    $filters = [
                        [
                            'key' => 'role_name',
                            'label' => 'Peran (Role)',
                            'type' => 'checkbox-list',
                            'open' => true,
                            'options' => [
                                ['value' => 'admin', 'label' => 'Admin'],
                                ['value' => 'kepala_divisi', 'label' => 'Kepala Divisi'],
                                ['value' => 'pembimbing', 'label' => 'Pembimbing'],
                                ['value' => 'mahasiswa', 'label' => 'Mahasiswa'],
                            ]
                        ],

                        // Filter Divisi
                        [
                            'key' => 'division_name',
                            'label' => 'Divisi',
                            'type' => 'checkbox-list',
                            'options' => $divisions->map(function($div) {
                                return [
                                    'value' => strtolower($div->name),
                                    'label' => $div->name
                                ];
                            })->values()->toArray()
                        ],
                    ];
                @endphp

                {{-- Panggil Component Filter Bar --}}
                <x-filter-bar :fields="$fields" :filters="$filters" table="#usersTable" />

                {{-- Tombol Tambah --}}
                <button
                    @click="modalTambah = true"
                    class="h-10 inline-flex items-center justify-center px-4 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <table id="usersTable" class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                @forelse ($users as $index => $user)
                    <tr
                        class="hover:bg-gray-50 transition group"
                        data-name="{{ strtolower($user->name) }}"
                        data-email="{{ strtolower($user->email) }}"
                        data-role_name="{{ strtolower($user->getRoleNames()->first() ?? '') }}"
                        data-division_name="{{ strtolower($user->division->name ?? '-') }}"
                    >
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->getRoleNames()->isNotEmpty())
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full capitalize">
                                    {{ ucwords(str_replace('_', ' ', $user->getRoleNames()->first()))  }}
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->division->name ?? "-" }}</td>

                        <td class="px-6 py-4 text-sm text-right flex items-center justify-end gap-3">

                            <button
                                @click="
                                    modalEdit = true;
                                    selectedUser = {{ $user->load('roles') }};
                                    editUrl = '{{ route('admin.user.update', $user->id) }}';
                                    role_name = (selectedUser.roles && selectedUser.roles.length > 0) ? selectedUser.roles[0].name : '';
                                "
                                class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all duration-150"
                                title="Edit User">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-gray-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12.572l-4.903 4.903" />
                                </svg>
                            </button>

                            <button
                                @click="
                                    modalHapus = true;
                                    selectedUser = {{ $user->load('roles') }};
                                    deleteUrl = '{{ route('admin.user.destroy', $user->id) }}';
                                "
                                class="p-2 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-150"
                                title="Hapus User">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.042-.53-.082-.79-.118l.79-.118A48.108 48.108 0 0112 4.5c4.756 0 9.242 1.256 12.834 3.39m-12.834 3.39L10.5 3.19c.09-.3.028-.617-.18-.842a1.12 1.12 0 00-1.424.062L7.25 4.5l-2.036-1.554a1.125 1.125 0 00-1.424-.062 1.125 1.125 0 00-.18.842l.79 4.39z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Baris Pesan "Data Tidak Ditemukan"  --}}
                    <tr id="noDataMessage" class="hidden">
                        <td colspan="100%" class="py-10">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                {{-- Icon Empty --}}
                                <img src="{{ asset('images/empty.jpg') }}" class="h-24 opacity-70 mb-3" alt="">
                                <p class="text-lg font-semibold">User tidak ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Klik tombol "Tambah User" untuk membuat data baru..</p>
                            </div>
                        </td>
                    </tr>

                @empty

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
                        <p class="text-gray-600 -mt-3">Buat akun baru untuk pengguna.</p>

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
@endsection
