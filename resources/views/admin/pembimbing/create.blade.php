@extends('layouts.app')
@section('title', "Kelola Data Pembimbing")
@section('content')

    <div x-data="{
        modalTambah: {{ $errors->store->any() && old('form_origin') == 'mentor_add' ? 'true' : 'false' }},
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalHapus: false,

        {{-- Modal Akun --}}
        modalAkun: {{ ($errors->store->any() && old('form_origin') == 'account_create') || ($errors->update->any() && old('form_origin') == 'account_edit') ? 'true' : 'false' }},
        isEditAccount: {{ $errors->update->any() && old('form_origin') == 'account_edit' ? 'true' : 'false' }},

        {{-- Data Selection --}}
        selectedMentor: {{
            ($errors->store->any() && old('form_origin') == 'account_create') || ($errors->update->any() && old('form_origin') == 'account_edit')
            ? json_encode(['id' => old('mentor_id'), 'name' => old('mentor_name_backup'), 'division_id' => old('division_id'), 'email' => old('email'), 'user_id' => old('user_id')])
            : '{}'
        }},

        {{-- URLs --}}
        deleteUrl: '',
        editUrl: '{{ $errors->update->any() ? route('admin.pembimbing.update', ['mentor' => old('id')]) : '' }}',
        accountUrl: '{{
            old('form_origin') == 'account_edit' && old('user_id')
            ? route('admin.user.update', ['user' => old('user_id')])
            : (old('form_origin') == 'account_create' ? route('admin.user.store') : '')
        }}',
     }"
         class="min-h-screen bg-gray-50/30">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kelola Pembimbing</h1>
                <p class="text-gray-500 mt-1">Manajemen data staf pembimbing dan akun login mereka.</p>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Total Mentor --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pembimbing</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_mentors'] }}</h3>
                    </div>
                </div>

                {{-- Total Bimbingan Aktif --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa Dibimbing</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</h3>
                    </div>
                </div>

                {{-- Akun Missing --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4 {{ $stats['missing_account'] > 0 ? 'border-red-200 bg-red-50/30' : '' }}">
                    <div class="p-3 {{ $stats['missing_account'] > 0 ? 'bg-red-100 text-red-600' : 'bg-green-50 text-green-600' }} rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Punya Akun</p>
                        <h3 class="text-2xl font-bold {{ $stats['missing_account'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $stats['missing_account'] }}</h3>
                    </div>
                </div>
            </div>


            {{-- SEARCH & FILTER & BUTTON --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <table id="mentorsTable" class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jabatan & Divisi</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jml. Bimbingan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Akun Login</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($mentors as $index => $mentor)
                        <tr class="hover:bg-gray-50 transition group"
                            data-name="{{ strtolower($mentor->name) }}"
                            data-position="{{ strtolower($mentor->position) }}"
                            data-division_name="{{ strtolower($mentor->division->name ?? '-') }}">

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>

                            {{-- Kolom Pembimbing (Avatar + Nama) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-sm font-bold mr-3 border border-purple-100">
                                        {{ substr($mentor->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $mentor->name }}</div>
                                        <div class="text-xs font-mono text-gray-500 mt-0.5">{{ $mentor->nip ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Jabatan & Divisi (Digabung) --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $mentor->position }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    {{ $mentor->division->name ?? 'Tidak ada divisi' }}
                                </div>
                            </td>

                            {{-- Kolom Jumlah Mahasiswa bimbingan --}}
                            <td class="px-6 py-4 text-center">
                                @if($mentor->active_students_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $mentor->active_students_count > 5 ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-green-100 text-green-800 border-green-200' }}">
                                        {{ $mentor->active_students_count }} Mahasiswa
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            {{-- Kolom Status Akun --}}
                            <td class="px-6 py-4 text-center">
                                @if($mentor->user_id)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100 text-[10px] font-bold uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200 text-[10px] font-bold uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Belum Ada
                                    </div>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">

                                    <button @click="
                                        modalAkun = true;
                                        isEditAccount = {{ $mentor->user_id ? 'true' : 'false' }};
                                        selectedMentor = {{ json_encode($mentor) }};
                                        accountUrl = '{{ $mentor->user_id ? route('admin.user.update', $mentor->user_id) : route('admin.user.store') }}';
                                        " class="p-2 rounded-lg border transition-colors
                                        {{ $mentor->user_id ? 'bg-white border-green-400 text-gray-600 hover:bg-gray-50' : 'bg-indigo-50 border-indigo-200 text-indigo-600 hover:bg-indigo-100' }}"
                                        title="{{ $mentor->user_id ? 'Reset Password' : 'Buat Akun Login' }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                        </svg>
                                    </button>

                                    {{-- Edit Data --}}
                                    <button @click="
                                        modalEdit = true;
                                        selectedMentor = {{ json_encode($mentor) }};
                                        editUrl = '{{ route('admin.pembimbing.update', $mentor->id) }}';
                                        " class="p-2 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit Profil">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Hapus --}}
                                    <button @click="
                                        modalHapus = true;
                                        selectedMentor = {{ json_encode($mentor) }};
                                        deleteUrl = '{{ route('admin.pembimbing.destroy', $mentor->id) }}';
                                        " class="p-2 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                </div>
                            </td>

                        </tr>

                        <tr id="noDataMessage" class="hidden">
                            <td colspan="100%" class="py-10">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <img src="{{ asset('images/empty.jpg') }}" class="h-24 opacity-70 mb-3" alt="">
                                    <p class="text-lg font-semibold">Data tidak ditemukan</p>
                                    <p class="text-sm text-gray-400 mt-1">Coba periksa kata kunci atau filter yang digunakan.</p>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    <p class="text-lg font-medium">Belum Data Pembimbing</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan pembimbing agar mahasiswa bisa mendapatkan pembimbing.</p>
                                </div>
                            </td>
                        </tr>
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
    </div>
@endsection
