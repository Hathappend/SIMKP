@extends('layouts.app')

@section('content')

    {{-- Alpine Data --}}
    <div x-data="{
        modalTambah: {{ $errors->store->any() && old('form_origin') == 'mentor_add' ? 'true' : 'false' }},
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalHapus: false,
        modalAkun: {{ ($errors->store->any() && old('form_origin') == 'account_create') || ($errors->update->any() && old('form_origin') == 'account_edit') ? 'true' : 'false' }},
        isEditAccount: {{ $errors->update->any() && old('form_origin') == 'account_edit' ? 'true' : 'false' }},
        selectedMentor: {{
                        ($errors->store->any() && old('form_origin') == 'account_create') || ($errors->update->any() && old('form_origin') == 'account_edit')
                        ? json_encode(['id' => old('mentor_id'), 'name' => old('mentor_name_backup'), 'division_id' => old('division_id'), 'email' => old('email'), 'user_id' => old('user_id')])
                        : '{}'
                    }},
        deleteUrl: '',
        editUrl: '{{ $errors->update->any() ? route('admin.pembimbing.update', ['mentor' => old('id')]) : '' }}',
        accountUrl: '{{
    old('form_origin') == 'account_edit' && old('user_id')
    ? route('admin.user.update', ['user' => old('user_id')])
    : (old('form_origin') == 'account_create' ? route('admin.user.store') : '')
}}',
     }"
         class="md:mx-12 mt-10 mb-16">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
            <div class="w-full md:w-auto">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Pembimbing</h1>
                <p class="mt-1 text-sm text-gray-500">Daftar staf yang dapat membimbing mahasiswa.</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                {{-- Filter Bar --}}
                @php
                    $fields = ['name', 'position', 'division_name'];
                    $filters = [
                        ['key' => 'division_name', 'label' => 'Divisi', 'type' => 'checkbox-list', 'open' => true,
                         'options' => $divisions->map(fn($d) => ['value' => strtolower($d->name), 'label' => $d->name])->values()->toArray()]
                    ];
                @endphp
                <x-filter-bar :fields="$fields" :filters="$filters" table="#mentorsTable" />

                {{-- Tombol Tambah --}}
                <button @click="modalTambah = true" class="h-10 inline-flex items-center justify-center px-4 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>Tambah</span>
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <table id="mentorsTable" class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pembimbing</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Jml. Bimbingan</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($mentors as $index => $mentor)
                    <tr class="hover:bg-gray-50 transition group"
                        data-name="{{ strtolower($mentor->name) }}"
                        data-position="{{ strtolower($mentor->position) }}"
                        data-division_name="{{ strtolower($mentor->division->name ?? '-') }}">

                        <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $mentor->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $mentor->position }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $mentor->division->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($mentor->students_count > 0)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    {{ $mentor->students_count }} Mahasiswa
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-right flex items-center justify-end gap-3">
                            @if($mentor->user_id)
                                {{-- TOMBOL EDIT AKUN --}}
                                <button @click="
                                    modalAkun = true;
                                    isEditAccount = true; // Mode Edit
                                    selectedMentor = {{ json_encode($mentor) }};
                                    // Arahkan ke route UPDATE user
                                    accountUrl = '{{ route('admin.user.update', $mentor->user_id) }}';
                                " class="p-2 rounded-xl bg-yellow-50 border border-yellow-200 hover:bg-yellow-100 text-yellow-600" title="Edit Akun Login (Reset Password)">
                                    {{-- Ikon Kunci dengan Pensil/Edit --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                    </svg>
                                </button>
                            @else
                                {{-- TOMBOL BUAT AKUN --}}
                                <button @click="
                                    modalAkun = true;
                                    isEditAccount = false; // Mode Create
                                    selectedMentor = {{ json_encode($mentor) }};
                                    // Arahkan ke route STORE user
                                    accountUrl = '{{ route('admin.user.store') }}';
                                " class="p-2 rounded-xl bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-600" title="Buatkan Akun">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                </button>
                            @endif

                            {{-- Edit Button --}}
                            <button @click="
                                modalEdit = true;
                                selectedMentor = {{ json_encode($mentor) }};
                                editUrl = '{{ route('admin.pembimbing.update', $mentor->id) }}';
                            " class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-gray-700"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12.572l-4.903 4.903" /></svg>
                            </button>

                            {{-- Hapus Button --}}
                            <button @click="
                                modalHapus = true;
                                selectedMentor = {{ json_encode($mentor) }};
                                deleteUrl = '{{ route('admin.pembimbing.destroy', $mentor->id) }}';
                            " class="p-2 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.042-.53-.082-.79-.118l.79-.118A48.108 48.108 0 0112 4.5c4.756 0 9.242 1.256 12.834 3.39m-12.834 3.39L10.5 3.19c.09-.3.028-.617-.18-.842a1.12 1.12 0 00-1.424.062L7.25 4.5l-2.036-1.554a1.125 1.125 0 00-1.424-.062 1.125 1.125 0 00-.18.842l.79 4.39z" /></svg>
                            </button>
                        </td>
                    </tr>

                    {{-- "Data Tidak Ditemukan"  --}}
                    <tr id="noDataMessage" class="hidden">
                        <td colspan="100%" class="py-10">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                {{-- Icon Empty --}}
                                <img src="{{ asset('images/empty.jpg') }}" class="h-24 opacity-70 mb-3" alt="">
                                <p class="text-lg font-semibold">User tidak ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba periksa kata kunci atau filter yang digunakan.</p>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="100%" class="py-10 text-center text-gray-500">Belum ada data pembimbing.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="modalTambah" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalTambah = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                <form action="{{ route('admin.pembimbing.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_origin" value="mentor_add">

                    <div class="p-6 space-y-4">
                        <h3 class="text-xl font-semibold text-gray-900">Tambah Pembimbing</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full p-2.5 border rounded-lg @error('name', 'store') border-red-500 @else border-gray-300 @enderror" required>
                            @error('name', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="position" value="{{ old('position') }}" class="w-full p-2.5 border rounded-lg @error('position', 'store') border-red-500 @else border-gray-300 @enderror" required>
                            @error('position', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select name="division_id" class="w-full p-2.5 border rounded-lg @error('division_id', 'store') border-red-500 @else border-gray-300 @enderror">
                                <option value="">Pilih Divisi...</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                                @endforeach
                            </select>
                            @error('division_id', 'store') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalTambah = false" type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalEdit = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                <form :action="editUrl" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="selectedMentor.id">

                    <div class="p-6 space-y-4">
                        <h3 class="text-xl font-semibold text-gray-900">Edit Pembimbing</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   :value="{{ $errors->update->any() ? old('name') : 'selectedMentor.name' }}"
                                   class="w-full p-2.5 border rounded-lg @error('name', 'update') border-red-500 @else border-gray-300 @enderror" required>
                            @error('name', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" name="position"
                                   value="{{ old('name') }}"
                                   :value="{{ $errors->update->any() ? old('name') : 'selectedMentor.position' }}"
                                   class="w-full p-2.5 border rounded-lg @error('position', 'update') border-red-500 @else border-gray-300 @enderror" required>
                            @error('position', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Divisi</label>
                            <select name="division_id" class="w-full p-2.5 border rounded-lg @error('division_id', 'update') border-red-500 @else border-gray-300 @enderror">
                                <option value="">Pilih Divisi...</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}"
                                            :selected="selectedMentor.division_id == {{ $div->id }} && '{{ old('division_id') }}' == ''"
                                        {{ old('division_id') == $div->id ? 'selected' : '' }}>
                                        {{ $div->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division_id', 'update') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalEdit = false" type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="modalHapus" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalHapus = false" class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-red-100 rounded-full"><svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Hapus Data?</h3>
                                <p class="mt-2 text-sm text-gray-500">Yakin ingin menghapus pembimbing <strong x-text="selectedMentor.name"></strong>?</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalHapus = false" type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="modalAkun" style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalAkun = false"
                 x-data="{
                    passwordField: '',
                    generatedPassword: '',
                    showGenerated: false,
                    generate() {
                        const pass = window.generateRandomPassword();
                        this.passwordField = pass;
                        this.generatedPassword = pass;
                        this.showGenerated = true;
                    }
                 }"
                 class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">

                <form :action="accountUrl" method="POST">
                    @csrf

                    {{-- TAMBAHKAN METHOD PUT --}}
                    <template x-if="isEditAccount">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Penanda Form berubah --}}
                    <input type="hidden" name="form_origin" :value="isEditAccount ? 'account_edit' : 'account_create'">

                    <input type="hidden" name="id" :value="selectedMentor.user_id">
                    <input type="hidden" name="mentor_id" :value="selectedMentor.id">

                    {{-- Data Role & Profile --}}
                    <input type="hidden" name="role_name" value="pembimbing">
                    <input type="hidden" name="name" :value="selectedMentor.name">
                    <input type="hidden" name="mentor_name_backup" :value="selectedMentor.name">
                    <input type="hidden" name="division_id" :value="selectedMentor.division_id">

                    <input type="hidden" name="password_confirmation" :value="passwordField">

                    <div class="p-6 space-y-4">
                        {{-- Header Dinamis --}}
                        <div class="flex items-center gap-3 mb-2 p-3 rounded-lg border"
                             :class="isEditAccount ? 'bg-yellow-50 border-yellow-100' : 'bg-indigo-50 border-indigo-100'">
                            <div class="p-2 bg-white rounded-full shadow-sm"
                                 :class="isEditAccount ? 'text-yellow-600' : 'text-indigo-600'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900" x-text="isEditAccount ? 'Edit Akun Login' : 'Aktivasi Akun Login'"></h3>
                                <p class="text-xs text-gray-500">Untuk: <strong x-text="selectedMentor.name"></strong></p>
                            </div>
                        </div>

                        {{-- Input Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Login</label>
                            <input type="email" name="email"
                                   value="{{ old('email') }}"
                                   :value="selectedMentor.email || '{{ old('email') }}'"
                                   class="w-full p-2.5 border rounded-lg" required>

                            {{-- Tampilkan Error dari bag yang sesuai --}}
                            <template x-if="!isEditAccount">
                                @error('email', 'store') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </template>
                            <template x-if="isEditAccount">
                                @error('email', 'update') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </template>
                        </div>

                        {{-- Password Generator --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span x-text="isEditAccount ? 'Password Baru (Opsional)' : 'Password Sementara'"></span>
                            </label>
                            <div class="flex gap-2">
                                <input type="text" name="password" x-model="passwordField"
                                       class="w-full p-2.5 border rounded-lg bg-gray-50 font-mono"
                                       :required="!isEditAccount"
                                       placeholder="Klik generate...">

                                <button type="button" @click="generate()" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-sm">
                                    Generate
                                </button>
                            </div>
                            <p x-show="isEditAccount" class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti password.</p>

                            {{-- Error Password --}}
                            <template x-if="!isEditAccount">
                                @error('password', 'store') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </template>
                            <template x-if="isEditAccount">
                                @error('password', 'update') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </template>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalAkun = false" type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50 text-sm">Batal</button>
                        <button type="submit"
                                class="px-4 py-2 text-white rounded-lg text-sm shadow-sm"
                                :class="isEditAccount ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-indigo-600 hover:bg-indigo-700'"
                                x-text="isEditAccount ? 'Simpan Perubahan' : 'Buat Akun'">
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
