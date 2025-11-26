@extends('layouts.app')
@section('title', "Pembuatan Surat")
@section('content')
    <div class="min-h-screen bg-gray-50/30"
         x-data="{
        modalSurat: false,
        selectedApp: {},
        students: [],
        actionUrl: '',
        previewUrl: ''
     }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-20">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Kelola Surat Balasan</h1>
                <p class="text-gray-500 mt-1">Buat dan terbitkan surat balasan untuk mahasiswa yang telah diterima.</p>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Perlu Dibuat --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-yellow-100 flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-yellow-50 rounded-bl-full -mr-2 -mt-2 transition-transform group-hover:scale-110"></div>
                    <div>
                        <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide">Perlu Dibuat</p>
                        <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['waiting'] }}</h2>
                    </div>
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-xl relative z-10">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                {{-- Selesai --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-green-100 flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-green-50 rounded-bl-full -mr-2 -mt-2 transition-transform group-hover:scale-110"></div>
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Selesai Dicetak</p>
                        <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['completed'] }}</h2>
                    </div>
                    <div class="p-3 bg-green-100 text-green-600 rounded-xl relative z-10">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                {{-- total --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-blue-100 flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-2 -mt-2 transition-transform group-hover:scale-110"></div>
                    <div>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Mahasiswa</p>
                        <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h2>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-xl relative z-10">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
            </div>

            {{-- FILTER BAR --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['student_name', 'university', 'division_name', 'letter_number'];
                        $filters = [
                            ['key' => 'letter_status', 'label' => 'Status Surat', 'type' => 'checkbox-list', 'open' => true,
                                'options' => [
                                    ['value' => 'waiting', 'label' => 'Belum Dibuat'],
                                    ['value' => 'completed', 'label' => 'Selesai'],
                                ]
                            ],
                            ['key' => 'date', 'label' => 'Tanggal', 'type' => 'date-range']
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#lettersTable" />
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="lettersTable" class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/4">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Divisi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nomor Surat</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($applications as $app)
                        <tr class="hover:bg-gray-50 transition group
                        {{-- Highlight jika belum dibuat suratnya --}}
                        {{ $app->letter_status == 'waiting' ? 'bg-yellow-50/30' : '' }}"

                            {{-- Data Attributes untuk Filter --}}
                            data-student_name="{{ strtolower($app->student->name) }}"
                            data-university="{{ strtolower($app->student->university) }}"
                            data-division_name="{{ strtolower($app->division->name) }}"
                            data-letter_number="{{ strtolower($app->letter_number ?? '') }}"
                            data-letter_status="{{ $app->letter_status == 'completed' ? 'completed' : 'waiting' }}"
                            data-date="{{ \Carbon\Carbon::parse($app->letter_date ?? $app->created_at)->format('Y-m-d') }}"
                        >
                            {{-- Mahasiswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-bold mr-3 border border-gray-200">
                                        {{ substr($app->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $app->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->student->university }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Divisi --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $app->division->name }}
                            </td>

                            {{-- Nomor Surat (Jika ada) --}}
                            <td class="px-6 py-4">
                                @if($app->letter_number)
                                    <span class="text-sm font-mono font-medium text-gray-800 bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                    {{ $app->letter_number }}
                                </span>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($app->letter_date)->translatedFormat('d M Y') }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">- Belum diinput -</span>
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 text-center">
                                @if($app->letter_status == 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    Selesai
                                </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Belum Dibuat
                                </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">

                                    {{-- BUAT SURAT (Jika belum) atau EDIT (Jika sudah) --}}
                                    <button @click="
                                            modalSurat = true;
                                            selectedApp = {{ json_encode($app) }};
                                            actionUrl = '{{ route('admin.surat.update', $app->id) }}';
                                            previewUrl = '{{ Storage::url($app->internship_letter) }}';

                                            students = [];
                                            let existingMembers = {{ json_encode($app->members) }};
                                            $nextTick(() => {
                                                if (existingMembers.length > 0) {
                                                    existingMembers.forEach(m => {
                                                        students.push({ name: m.name, nim: m.nim, study_program: m.study_program });
                                                    });
                                                } else {
                                                    students.push({ name: '{{ $app->student->name }}', nim: '{{ $app->student->nim }}', study_program: '{{ $app->student->study_program }}' });
                                                }
                                            });
    "

                                            class="inline-flex items-center px-3 py-1.5 border border-gray-100 text-xs font-bold rounded-lg shadow-sm transition-all
                                            {{ $app->letter_status == 'waiting'
                                                ? 'bg-[#1B2A52] hover:bg-blue-900 text-white'
                                                : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">

                                        @if($app->letter_status == 'waiting')
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            Buat Surat
                                        @else
                                            <svg class="w-4 h-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            Edit
                                        @endif
                                    </button>

                                    {{-- TOMBOL DOWNLOAD (Hanya jika sudah selesai) --}}
                                    @if($app->reply_letter_path)
                                        <a href="{{ route('admin.surat.show', $app->id) }}" target="_blank"
                                           class="p-1.5 text-red-500 hover:bg-red-100 rounded-lg border border-red-100 transition-colors" title="Lihat PDF">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                        </a>
                                    @endif

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
                            <td colspan="100%" class="py-16 text-center text-gray-500">
                                <svg class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="text-lg font-medium">Belum ada data pengajuan yang disetujui.</p>
                                <p class="text-sm text-gray-400">Surat hanya bisa dibuat untuk mahasiswa yang statusnya "Disetujui".</p>
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
    </div>
@endsection
