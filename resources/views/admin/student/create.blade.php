@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30"
         x-data="{
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalDetail: false,
        modalHapus: false,

        {{-- DATA --}}
        selectedStudent: {{ $errors->update->any() ? json_encode(session()->getOldInput()) : '{}' }},
        updateUrl: '',
        deleteUrl: '',
     }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER & FILTER --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
                <div class="w-full md:w-auto">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Master Data Mahasiswa</h1>
                    <p class="text-gray-500 mt-1">Database akun mahasiswa yang terdaftar sebagai Ketua/Perwakilan.</p>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['name', 'nim', 'email', 'university', 'study_program'];
                        $filters = [
                            ['key' => 'university', 'label' => 'Universitas', 'type' => 'text'],
                            ['key' => 'has_account', 'label' => 'Status Akun', 'type' => 'checkbox-list', 'options' => [['value' => 'yes', 'label' => 'Punya Akun'], ['value' => 'no', 'label' => 'Tanpa Akun']]]
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#studentsTable" />
                </div>
            </div>

            {{-- TABEL UTAMA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="studentsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-16">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Ketua / Perwakilan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Akademik</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status Akun</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($students as $index => $student)
                        <tr class="hover:bg-gray-50 transition-colors group"
                            data-name="{{ strtolower($student->name) }}"
                            data-nim="{{ strtolower($student->nim) }}"
                            data-email="{{ strtolower($student->email) }}"
                            data-university="{{ strtolower($student->university) }}"
                            data-study_program="{{ strtolower($student->study_program) }}"
                            data-has_account="{{ $student->user_id ? 'yes' : 'no' }}">

                            <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>

                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold mr-3 border border-indigo-100">
                                        {{ substr($student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                                        <div class="text-xs font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded inline-block mt-0.5">
                                            {{ $student->nim }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    {{ $student->email }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    {{ $student->phone_number ?? '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $student->university }}</div>
                                <div class="text-xs text-gray-500">{{ $student->study_program }}</div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($student->user_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200">No Account</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.mahasiswa.show', $student->id) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    {{-- EDIT --}}
                                    <button @click="modalEdit = true; selectedStudent = {{ json_encode($student) }}; updateUrl = '{{ route('admin.mahasiswa.update', $student->id) }}';"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Biodata">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>

                                    {{-- HAPUS --}}
                                    <button @click="modalHapus = true; selectedStudent = {{ json_encode($student) }}; deleteUrl = '{{ route('admin.mahasiswa.destroy', $student->id) }}';"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Pesan Data Kosong untuk Filter --}}
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
                            <td colspan="100%" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-lg font-medium">Data Mahasiswa Belum Ada.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada data mahasiswa karena belum ada pengajuan magang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL Edit --}}
        <div x-show="modalEdit" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm" x-transition>
            <div @click.away="modalEdit = false" class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
                <form :action="updateUrl" method="POST">
                    @csrf @method('PUT')

                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Edit Mahasiswa</h3>
                        <button type="button" @click="modalEdit = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" :value="selectedStudent.name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                                <input type="text" name="nim" :value="selectedStudent.nim" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                <input type="text" name="phone_number" :value="selectedStudent.phone_number" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" :value="selectedStudent.email" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                            <input type="text" name="university" :value="selectedStudent.university" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <input type="text" name="study_program" :value="selectedStudent.study_program" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" @click="modalEdit = false" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-white">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#1B2A52] text-white rounded-lg text-sm font-medium hover:bg-blue-900 shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div x-show="modalHapus" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm" x-transition>
            <div @click.away="modalHapus = false" class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden">
                <form :action="deleteUrl" method="POST">
                    @csrf @method('DELETE')
                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Hapus Mahasiswa?</h3>
                        <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus data <strong x-text="selectedStudent.name"></strong>?</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-center gap-3">
                        <button type="button" @click="modalHapus = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 shadow-sm">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection




