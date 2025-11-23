@extends('layouts.app')

@section('content')

    <div x-data="{
        modalTambah: {{ $errors->store->any() ? 'true' : 'false' }},
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalHapus: false,
        selectedDivisi: {{ $errors->update->any() ? json_encode(['id' => old('id'), 'name' => old('name')]) : '{}' }},
        deleteUrl: '',
        editUrl: '{{ $errors->update->any() ? old('original_url', route('admin.divisi.update', ['division' => old('id')])) : '' }}'
     }"
         class="min-h-screen bg-gray-50/30">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kelola Divisi</h1>
                <p class="text-gray-500 mt-1">Manajemen unit kerja penerima mahasiswa magang.</p>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Total Divisi --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Divisi</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_divisions'] }}</h3>
                    </div>
                </div>

                {{-- Total Mahasiswa Aktif (Di Semua Divisi) --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Peserta Magang</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_active_interns'] }}</h3>
                    </div>
                </div>

                {{-- Divisi Terpadat --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <div class="min-w-0"> {{-- min-w-0 for text truncation --}}
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Divisi Terpadat</p>
                        <h3 class="text-lg font-bold text-gray-900 truncate" title="{{ $stats['most_popular']->name ?? '-' }}">
                            {{ $stats['most_popular']->name ?? '-' }}
                        </h3>
                        @if(isset($stats['most_popular']))
                            <p class="text-xs text-orange-600 font-medium">{{ $stats['most_popular']->active_interns_count }} Mahasiswa</p>
                        @endif
                    </div>
                </div>
            </div>


            {{-- SEARCH & BUTTON --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">

                    {{-- Filter Bar --}}
                    @php
                        $fields = ['name'];
                        $filters = [];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#divisiTable" />

                    <button
                        @click="modalTambah = true" class="h-10 inline-flex items-center justify-center px-4 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        <span>Tambah</span>
                    </button>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <table id="divisiTable" class="w-full min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-20">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Divisi</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa Aktif</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                    @forelse ($divisions as $index => $division)
                        <tr class="hover:bg-gray-50 transition group" data-name="{{ strtolower($division->name) }}">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                {{ $division->name }}
                            </td>

                            {{-- Kolom Jumlah Mahasiswa Aktif --}}
                            <td class="px-6 py-4 text-center">
                                @if($division->active_interns_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ $division->active_interns_count }} Orang
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-sm text-right">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Tombol Edit --}}
                                    <button
                                        @click="
                                        modalEdit = true;
                                        selectedDivisi = {{ json_encode($division) }};
                                        editUrl = '{{ route('admin.divisi.update', $division->id) }}';
                                        " class="p-2 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit Divisi">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button
                                        @click="
                                        modalHapus = true;
                                        selectedDivisi = {{ json_encode($division) }};
                                        deleteUrl = '{{ route('admin.divisi.destroy', $division->id) }}';
                                        " class="p-2 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus Divisi">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                                    <p class="text-lg font-medium">Belum Ada Divisi</p>
                                    <p class="text-sm text-gray-400 mt-1">Tambahkan divisi agar mahasiswa bisa memilih penempatan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div
                x-show="modalTambah"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;"
            >
                <div @click.away="modalTambah = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                    <form action="{{ route('admin.divisi.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="form_action" value="store">

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Tambah Divisi Baru</h3>
                            <p class="text-gray-600 mb-4">Masukkan nama untuk divisi baru yang akan menerima pengajuan magang.</p>
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi</label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name') }}"
                                       class="w-full p-3 border rounded-lg @error('name', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="Contoh: Aplikasi Informatika" required>

                                @error('name', 'store')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                <div @click.away="modalEdit = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                    <form :action="editUrl" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" :value="selectedDivisi.id">
                        <input type="hidden" name="form_action" value="update">
                        <input type="hidden" name="original_url" :value="editUrl">

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Edit Divisi</h3>
                            <div>
                                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi</label>
                                <input type="text" id="edit_name" name="name"
                                       value="{{ old('name') }}"
                                       :value="{{ $errors->update->any() ? old('name') : 'selectedDivisi.name' }}"
                                       class="w-full p-3 border rounded-lg
                                  @error('name', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                       required>

                                @error('name', 'update')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
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
                                    <h3 class="text-xl font-semibold text-gray-900">Hapus Divisi?</h3>
                                    <p class="text-gray-600 mt-2">
                                        Anda yakin ingin menghapus divisi <strong x-text="selectedDivisi.name"></strong>? Tindakan ini tidak dapat dibatalkan.
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
