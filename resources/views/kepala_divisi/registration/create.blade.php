@extends('layouts.app')

@section('content')

    <div class="md:mx-12 mt-12 mb-10">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Pengajuan Magang Masuk</h1>
        <p class="mt-1 text-gray-600">Daftar mahasiswa yang mengajukan magang di divisi Anda.</p>
    </div>

    {{-- SEARCH + FILTER --}}
    <div class="flex flex-col md:flex-row justify-end items-center mb-6 md:mx-12 gap-3">

        @php
            // Field yang bisa dicari
            $fields = ['full_name', 'study_program', 'university', 'status_text', 'mentor_name'];

            // Filter yang relevan untuk Kadiv
            $filters = [
                [
                    'key' => 'status_text',
                    'label' => 'Status',
                    'type' => 'checkbox-list',
                    'open' => true,
                    'options' => [
                        ['value' => 'menunggu', 'label' => 'Menunggu Persetujuan'], // status: waiting
                        ['value' => 'diterima', 'label' => 'Diterima'], // status: approved
                        ['value' => 'ditolak', 'label' => 'Ditolak'], // status: rejected
                    ]
                ],
                [
                    'key' => 'created_at',
                    'label' => 'Tanggal Pengajuan',
                    'type' => 'date-range'
                ]
            ];
        @endphp

        <x-filter-bar :fields="$fields" :filters="$filters" table="#kadivTable" />

    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden md:mx-12 mb-20 border border-gray-100">
        <table id="kadivTable" class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">No</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mahasiswa</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asal Kampus</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pembimbing</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($applications as $i => $app)
                <tr class="hover:bg-gray-50 transition group"
                    data-full_name="{{ strtolower($app->student->name) }}"
                    data-study_program="{{ strtolower($app->student->study_program) }}"
                    data-university="{{ strtolower($app->student->university) }}"
                    data-created_at="{{ \Carbon\Carbon::parse($app->created_at)->format('Y-m-d') }}"
                    data-mentor_name="{{ strtolower($app->mentor->name ?? '-') }}"
                    {{-- Mapping status untuk filter text --}}
                    data-status_text="{{
                        $app->application_status == 'waiting' ? 'menunggu' :
                        ($app->application_status == 'approved' ? 'diterima' :
                        ($app->application_status == 'rejected' ? 'ditolak' : ''))
                    }}"
                >

                    {{-- NO --}}
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $i + 1 }}</td>

                    {{-- MAHASISWA --}}
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $app->student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $app->student->study_program }}</div>
                    </td>

                    {{-- KAMPUS --}}
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $app->student->university }}</td>

                    {{-- TANGGAL --}}
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}
                    </td>

                    {{-- PEMBIMBING (Penting untuk Kadiv) --}}
                    <td class="px-6 py-4 text-sm">
                        @if($app->mentor)
                            <span class="text-gray-900 font-medium">{{ $app->mentor->name }}</span>
                        @else
                            <span class="text-gray-400 italic text-xs">Belum ditunjuk</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                        @if($app->application_status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Diterima
                            </span>
                        @elseif($app->application_status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @elseif($app->application_status === 'waiting')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 animate-pulse">
                                Perlu Tindakan
                            </span>
                        @else
                            {{-- Seharusnya tidak muncul di sini karena sudah difilter controller --}}
                            <span class="text-gray-400 text-xs">{{ $app->application_status }}</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <a href="{{ route('kadiv.pengajuan.show', $app->id) }}"
                           class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                            Detail
                        </a>
                    </td>
                </tr>

                {{-- Empty State Filter --}}
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
                {{-- Empty State Database --}}
                <tr>
                    <td colspan="100%" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-lg font-medium">Tidak ada pengajuan masuk.</p>
                            <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang diteruskan oleh Admin.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
