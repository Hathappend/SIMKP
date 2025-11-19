@extends('layouts.app')

@section('content')
    <div x-data="{ modalSurat: false, selectedApp: {}, students: [], actionUrl: '', previewUrl: '' }" class="md:mx-12 mt-10 mb-20">

        {{-- HEADER & FILTER SECTION --}}
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">

            {{-- Judul --}}
            <div class="w-full md:w-auto">
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Kelola Surat Balasan</h1>
                <p class="mt-1 text-sm text-gray-500">Generate surat untuk mahasiswa yang telah diterima.</p>
            </div>

            {{-- SEARCH + FILTER BAR --}}
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @php
                    // Field yang bisa dicari
                    $fields = ['student_name', 'university', 'division_name', 'letter_number'];

                    // onfigurasi Filter
                    $filters = [
                        // Filter Status Surat
                        [
                            'key' => 'letter_status',
                            'label' => 'Status Surat',
                            'type' => 'checkbox-list',
                            'open' => true,
                            'options' => [
                                ['value' => 'waiting', 'label' => 'Belum Dibuat'],
                                ['value' => 'completed', 'label' => 'Selesai'],
                            ]
                        ],
                        // Filter Tanggal
                        ['key' => 'date', 'label' => 'Tanggal', 'type' => 'date-range']
                    ];
                @endphp

                {{-- Component Filter --}}
                <x-filter-bar :fields="$fields" :filters="$filters" table="#lettersTable" />
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100">

            <table id="lettersTable" class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nomor Surat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($applications as $app)
                    <tr class="hover:bg-gray-50 transition group"
                        data-student_name="{{ strtolower($app->student->name) }}"
                        data-university="{{ strtolower($app->student->university) }}"
                        data-division_name="{{ strtolower($app->division->name) }}"
                        data-letter_number="{{ strtolower($app->letter_number ?? '') }}"
                        data-letter_status="{{ $app->letter_status == 'completed' ? 'completed' : 'waiting' }}"
                        data-date="{{ \Carbon\Carbon::parse($app->letter_date)->format('Y-m-d') }}"
                    >
                        {{-- NAMA MAHASISWA --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $app->student->name }}</div>
                            <div class="text-xs text-gray-500">{{ $app->student->university }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $app->division->name }}</td>

                        {{-- NOMOR SURAT --}}
                        <td class="px-6 py-4 text-sm font-mono text-gray-700">
                            {{ $app->letter_number ?? '-' }}
                        </td>

                        {{-- TANGGAL --}}
                        <td class="px-6 py-4">
                            @if($app->letter_status == 'completed' && $app->letter_date)
                                {{-- Jika Selesai: Tampilkan Tanggal Surat --}}
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($app->letter_date)->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 uppercase tracking-wide">Tgl Surat</span>
                                </div>
                            @else
                                {{-- Jika Belum: Tampilkan Tanggal Daftar --}}
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500">
                                        {{ $app->created_at->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="text-[10px] text-orange-400 uppercase tracking-wide">Tgl Masuk</span>
                                </div>
                            @endif
                        </td>

                        {{-- STATUS SURAT --}}
                        <td class="px-6 py-4 text-center">
                            @if($app->letter_status == 'completed')
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Belum Dibuat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">

                            {{-- TOMBOL BUAT SURAT --}}
                            <button @click="
                                    modalSurat = true;
                                    selectedApp = {{ json_encode($app) }};
                                    actionUrl = '{{ route('admin.surat.update', $app->id) }}';
                                    previewUrl = '{{ Storage::url($app->internship_letter) }}';

                                    let existingMembers = {{ json_encode($app->members) }};
                                    students = [];

                                    if (existingMembers.length > 0) {
                                        existingMembers.forEach(m => {
                                            students.push({
                                                name: m.name,
                                                nim: m.nim,
                                                study_program: m.study_program
                                            });
                                        });
                                    } else {
                                        // Load default
                                        students.push({
                                            name: '{{ $app->student->name }}',
                                            nim: '{{ $app->student->nim }}',
                                            study_program: '{{ $app->student->study_program }}'
                                        });
                                    }
                                " class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100" title="Buat Surat">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>

                            {{-- TOMBOL LIHAT DOKUMEN --}}
                            @if($app->reply_letter_path)
                                <a href="{{ route('admin.surat.show', $app->id) }}" target="_blank" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100" title="Lihat File PDF">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @endif

                        </td>
                    </tr>

                    {{-- Pesan Data Tidak Ditemukan (Wajib Ada untuk Filter) --}}
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
                    {{-- Pesan Data Kosong Database --}}
                    <tr>
                        <td colspan="100%" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="text-lg font-medium">Belum ada yang disetujui.</p>
                                <p class="text-sm text-gray-400">Saat ini belum ada pengajuan magang yang disetujui.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL INPUT SURAT --}}
        <div x-show="modalSurat" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-90" x-transition>

            <div @click.away="modalSurat = false" class="w-full max-w-6xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col md:flex-row h-[90vh]">

                {{-- PDF PREVIEW --}}
                <div class="w-full md:w-1/2 bg-gray-100 border-r border-gray-200 flex flex-col">
                    <div class="p-4 bg-white border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Surat Pengantar Kampus</h3>
                        <a :href="previewUrl" target="_blank" class="text-blue-600 text-xs hover:underline">Buka di Tab Baru</a>
                    </div>
                    <div class="flex-1 p-2">
                        <iframe :src="previewUrl" class="w-full h-full rounded border border-gray-300" frameborder="0"></iframe>
                    </div>
                </div>

                {{-- FORM --}}
                <div class="w-full md:w-1/2 flex flex-col">
                    <div class="p-6 border-b">
                        <h3 class="text-xl font-bold text-gray-800">Buat Surat Balasan</h3>
                        <p class="text-sm text-gray-500">Isi data mahasiswa sesuai surat di samping.</p>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6">
                        <form :action="actionUrl" method="POST" id="suratForm">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Balasan (Kita)</label>
                                <input type="text" name="letter_number" :value="selectedApp.letter_number" class="w-full border rounded-lg p-2.5" required placeholder="Nomor surat keluar...">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Pengantar (Kampus)</label>
                                <input type="text" name="campus_letter_number" class="w-full border rounded-lg p-2.5 bg-yellow-50" required placeholder="Lihat di surat sebelah...">
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                                <input type="date" name="letter_date" :value="selectedApp.letter_date" class="w-full border rounded-lg p-2.5" required>
                            </div>

                            <hr class="my-4 border-dashed">

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-bold text-gray-800">Daftar Mahasiswa</label>

                                    <button type="button"
                                            @click="students.push({name: '', nim: '', study_program: students[0].study_program})"
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 font-semibold">
                                        + Tambah Baris
                                    </button>
                                </div>

                                <template x-for="(student, index) in students" :key="index">
                                    <div class="flex gap-2 mb-2 items-center">
                                        <span x-text="index + 1" class="text-gray-400 text-xs w-4 text-center"></span>

                                        <div class="w-1/3">
                                            <input type="text" :name="'students[' + index + '][name]'" x-model="student.name"
                                                   class="w-full p-2 border rounded text-sm" placeholder="Nama" required>
                                        </div>

                                        <div class="w-1/4">
                                            <input type="text" :name="'students[' + index + '][nim]'" x-model="student.nim"
                                                   class="w-full p-2 border rounded text-sm" placeholder="NIM" required>
                                        </div>

                                        <div class="flex-1">
                                            <input type="text" :name="'students[' + index + '][study_program]'" x-model="student.study_program"
                                                   class="w-full p-2 border rounded text-sm" placeholder="Prodi" required>
                                        </div>

                                        <button type="button" @click="if(index > 0) students.splice(index, 1)"
                                                class="p-1 text-red-400 hover:text-red-600 w-6"
                                                :class="index === 0 ? 'opacity-0 cursor-default' : ''">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </form>
                    </div>

                    <div class="p-4 bg-gray-50 border-t flex justify-end gap-2">
                        <button type="button" @click="modalSurat = false" class="px-4 py-2 border rounded-lg hover:bg-white">Batal</button>
                        <button type="submit" form="suratForm" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan & Generate PDF</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
