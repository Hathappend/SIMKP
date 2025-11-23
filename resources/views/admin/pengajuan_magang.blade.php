@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pengajuan Magang</h1>
                <p class="text-gray-500 mt-1">Daftar mahasiswa baru yang mengajukan permohonan magang.</p>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

                {{-- Card Pending --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-500 uppercase">Menunggu</p>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</h2>
                    </div>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

                {{-- Card Waiting --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-yellow-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-yellow-600 uppercase">Sedang Ditinjau</p>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $stats['waiting'] }}</h2>
                    </div>
                    <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    </div>
                </div>

                {{-- Card Approved --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-green-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase">Diterima</p>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $stats['approved'] }}</h2>
                    </div>
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>

                {{-- Card Rejected --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-red-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-red-600 uppercase">Ditolak</p>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $stats['rejected'] }}</h2>
                    </div>
                    <div class="p-2 bg-red-50 text-red-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                </div>

            </div>

            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">

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


            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-20 border border-gray-100">

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
                            class="{{ $app->application_status == 'pending' ? 'bg-blue-50/50' : 'hover:bg-gray-50' }} transition group"
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
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Disetujui
                                    </span>
                                @elseif($app->application_status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Ditolak
                                    </span>
                                @elseif($app->application_status === 'waiting')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Sedang Ditinjau
                                    </span>
                                @else
                                    {{-- Status Pending / Baru Masuk --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200 animate-pulse">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                        </span>
                                        Baru Masuk
                                    </span>
                                @endif
                            </td>

                            {{-- STATUS SURAT --}}
                            <td class="px-6 py-4 text-center">
                                @if($app->letter_status === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </span>
                                @elseif($app->letter_status === 'in progress')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ongoing
                                    </span>
                                @else
                                    {{-- Status Waiting / Belum Dibuat --}}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        pending
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
        </div>
    </div>
@endsection
