@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Arsip Dokumen</h1>
                    <p class="text-gray-500 mt-1">Kumpulan dokumen mahasiswa yang telah menyelesaikan proses magang.</p>
                </div>

                {{-- Filter Pencarian --}}
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['student_name', 'university', 'division_name', 'type'];
                        $filters = [
                            ['key' => 'status', 'label' => 'Status Akhir', 'type' => 'checkbox-list', 'options' => [
                                ['value' => 'completed', 'label' => 'Selesai Magang'],
                                ['value' => 'rejected', 'label' => 'Ditolak'],
                            ]],
                            ['key' => 'date', 'label' => 'Tanggal Arsip', 'type' => 'date-range']
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#archiveTable" />
                </div>
            </div>

            {{-- TABEL ARSIP --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="archiveTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-16">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Dokumen & Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Divisi</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal Arsip</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status Akhir</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">File Tersimpan</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($archives as $index => $data)
                        <tr class="hover:bg-gray-50 transition-colors group"
                            data-student_name="{{ strtolower($data->student->name) }}"
                            data-university="{{ strtolower($data->student->university) }}"
                            data-division_name="{{ strtolower($data->division->name ?? '-') }}"
                            data-status="{{ $data->application_status }}"
                            data-date="{{ $data->updated_at->format('Y-m-d') }}"
                        >
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                            {{-- Kolom Informasi Utama --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 border border-indigo-100">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $data->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $data->student->university }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $data->division->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $data->updated_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($data->application_status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        Selesai Magang
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        Ditolak / Batal
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom File (Dropdown / List) --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    {{-- Tombol Download Surat Balasan (Jika Ada) --}}
                                    @if($data->reply_letter_path)
                                        <a href="{{ Storage::url($data->reply_letter_path) }}" target="_blank" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Surat Balasan">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </a>
                                    @endif

                                    {{-- Tombol Download Laporan Akhir (Jika Ada) --}}
                                    @if($data->report_file)
                                        <a href="{{ Storage::url($data->report_file) }}" target="_blank" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Laporan Akhir">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </a>
                                    @endif

                                    {{-- Tombol Download Sertifikat (Jika Ada) --}}
                                    @if($data->assessment && $data->assessment->certificate_path)
                                        <a href="{{ Storage::url($data->assessment->certificate_path) }}" target="_blank" class="p-2 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Sertifikat">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
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
                        {{-- Pesan Data Kosong Database --}}
                        <tr>
                            <td colspan="100%" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-lg font-medium">Belum ada dokumen arsip.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang menyelesaikan magang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
