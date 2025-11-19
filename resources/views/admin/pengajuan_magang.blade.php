@extends('layouts.app')

@section('content')

    <div class="w-full md:w-auto md:mx-12 mt:12 md:mt-12">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Pengajuan Magang</h1>
        <p class="mt-1 text-gray-600">Daftar semua mahasiswa yang mendaftar magang.</p>
    </div>

    <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 mt-10 md:mt-12 md:mx-12 gap-4">

        {{-- SEARCH + FILTER --}}
        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
            @php
                $fields = ['student_name', 'study_program', 'university', 'division_name', 'created_at'];

                $filters = [
                    ['key'=>'application_status','label'=>'Status Pengajuan','type'=>'checkbox-list','open'=>true,
                        'options'=>[
                            ['value'=>'pending','label'=>'Menunggu'],
                            ['value'=>'waiting','label'=>'Sedang Ditinjau'],
                            ['value'=>'approved','label'=>'Diterima'],
                            ['value'=>'rejected','label'=>'Ditolak'],
                        ]
                    ],
                    ['key'=>'letter_status','label'=>'Status Surat','type'=>'checkbox-list','options'=>[
                            ['value'=>'waiting','label'=>'Belum Dibuat'],
                            ['value'=>'in progress','label'=>'Sedang Dibuat'],
                            ['value'=>'completed','label'=>'Selesai'],
                        ]
                    ],

                    ['key'=>'created_at','label'=>'Tanggal Pengajuan','type'=>'date-range']
                ];
            @endphp

            <x-filter-bar :fields="$fields" :filters="$filters" table="#applicationsTable" />
        </div>
    </div>


    <div class="bg-white rounded-lg shadow-lg overflow-hidden md:mx-12 mb-20 border border-gray-100">

        <table id="applicationsTable" class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">No</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mahasiswa</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asal Kampus</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi Tujuan</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Pengajuan</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status Surat</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

            @forelse ($applications as $i => $app)
                <tr
                    class="hover:bg-gray-50 transition group"
                    data-student_name="{{ strtolower($app->student->name) }}"
                    data-study_program="{{ strtolower($app->student->study_program) }}"
                    data-university="{{ strtolower($app->student->university) }}"
                    data-division_name="{{ strtolower($app->division->name ?? '-') }}"
                    data-created_at="{{ \Carbon\Carbon::parse($app->created_at)->format('Y-m-d') }}"
                    data-application_status="{{ strtolower($app->application_status) }}"
                    data-letter_status="{{ strtolower($app->letter_status) }}"
                >

                    {{-- NOMOR --}}
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $i + 1 }}</td>

                    {{-- MAHASISWA --}}
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $app->student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $app->student->study_program }}</div>
                    </td>

                    {{-- UNIVERSITAS --}}
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $app->student->university }}
                    </td>

                    {{-- DIVISI --}}
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $app->division->name ?? '-' }}
                    </td>

                    {{-- TANGGAL PENGAJUAN --}}
                    <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}
                    </td>

                    {{-- STATUS PENGAJUAN --}}
                    <td class="px-6 py-4 text-center">
                        @if($app->application_status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Disetujui
                        </span>
                        @elseif($app->application_status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Ditolak
                        </span>
                        @elseif($app->application_status === 'waiting')
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.33-.266 2.98-1.742 2.98H4.42c-1.476 0-2.492-1.65-1.742-2.98l5.58-9.92zM10 13a1 1 0 100-2 1 1 0 000 2zm-1-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                            Sedang ditinjau
                        </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zM10 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                            Menunggu
                        </span>
                        @endif
                    </td>

                    {{-- STATUS SURAT --}}
                    <td class="px-6 py-4 text-center">
                        @if($app->letter_status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Disetujui
                        </span>
                        @elseif($app->letter_status === 'in progress')
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Ditolak
                        </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zM10 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                            Belum Dibuat
                        </span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-4 text-sm flex items-center gap-3">

                        {{-- DETAIL --}}
                        <a href="{{ route("admin.pengajuan.show", $app->id) }}" class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all duration-150" title="Lihat Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.7"
                                 stroke="currentColor" class="w-5 h-5 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>

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
                            <p class="text-lg font-medium">Belum ada pengajuan magang.</p>
                            <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang mengajukan magang.</p>
                        </div>
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>

    </div>
@endsection
