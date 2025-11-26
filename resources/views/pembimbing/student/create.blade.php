@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Mahasiswa Bimbingan</h1>
                <p class="text-gray-500 mt-1">Pantau progres dan aktivitas mahasiswa bimbingan Anda.</p>
            </div>

            {{-- 1. STATISTIK (ACTION CENTER) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Card 1: Total Mahasiswa --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Mahasiswa</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }} <span class="text-sm font-normal text-gray-500">Orang</span></h3>
                    </div>
                </div>

                {{-- Card 2: Logbook Perlu Review (Kuning jika ada tugas) --}}
                <div class="p-5 rounded-xl shadow-sm border flex items-center gap-4 transition-colors
                 {{ $stats['total_pending_logbooks'] > 0 ? 'bg-yellow-50 border-yellow-200' : 'bg-white border-gray-200' }}">
                    <div class="p-3 rounded-xl {{ $stats['total_pending_logbooks'] > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-400' }}">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider {{ $stats['total_pending_logbooks'] > 0 ? 'text-yellow-700' : 'text-gray-400' }}">
                            Logbook Pending
                        </p>
                        <h3 class="text-2xl font-bold {{ $stats['total_pending_logbooks'] > 0 ? 'text-yellow-800' : 'text-gray-900' }}">
                            {{ $stats['total_pending_logbooks'] }} <span class="text-sm font-normal opacity-70">Item</span>
                        </h3>
                    </div>
                </div>

                {{-- Card 3: Laporan Akhir (Biru jika ada tugas) --}}
                <div class="p-5 rounded-xl shadow-sm border flex items-center gap-4 transition-colors
                 {{ $stats['pending_reports'] > 0 ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-200' }}">
                    <div class="p-3 rounded-xl {{ $stats['pending_reports'] > 0 ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-400' }}">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider {{ $stats['pending_reports'] > 0 ? 'text-blue-700' : 'text-gray-400' }}">
                            Review Laporan
                        </p>
                        <h3 class="text-2xl font-bold {{ $stats['pending_reports'] > 0 ? 'text-blue-800' : 'text-gray-900' }}">
                            {{ $stats['pending_reports'] }} <span class="text-sm font-normal opacity-70">Dokumen</span>
                        </h3>
                    </div>
                </div>
            </div>

            {{-- HEADER  --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">

                {{-- FILTER BAR --}}
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        // Field Search Text
                        $fields = ['name', 'university', 'study_program'];

                        // Filter Dropdown
                        $filters = [
                            [
                            'key' => 'period_status',
                            'label' => 'Status Periode',
                            'type' => 'checkbox-list',
                            'open' => true,
                            'options' => [
                                ['value' => 'not_started', 'label' => 'Belum Mulai'],
                                ['value' => 'ongoing', 'label' => 'Sedang Berjalan'],
                                ['value' => 'finished', 'label' => 'Selesai Magang'],
                            ]
                        ],
                            // Filter Laporan (Untuk memantau siapa yang belum setor)
                            [
                                'key' => 'report_status',
                                'label' => 'Status Laporan',
                                'type' => 'checkbox-list',
                                'options' => [
                                    ['value' => 'submitted', 'label' => 'Menunggu Review'],
                                    ['value' => 'revision', 'label' => 'Revisi'],
                                    ['value' => 'approved', 'label' => 'Sudah ACC'],
                                    ['value' => 'none', 'label' => 'Belum Upload'],
                                ]
                            ],
                            // Filter Logbook
                            [
                                'key' => 'logbook_status',
                                'label' => 'Status Logbook',
                                'type' => 'checkbox-list',
                                'open' => true,
                                'options' => [
                                    ['value' => 'has_pending', 'label' => 'Perlu Review'],
                                    ['value' => 'clean', 'label' => 'Sudah Bersih'],
                                ]
                            ],
                        ];
                    @endphp

                    {{-- Component Filter --}}
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#myStudentsTable" />
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="myStudentsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Mahasiswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/5">Kehadiran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Logbook</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Laporan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $reg)

                        @php
                            $start = \Carbon\Carbon::parse($reg->start_date);
                            $end = \Carbon\Carbon::parse($reg->end_date);
                            $now = now();
                            $logbookStatusKey = ($reg->pending_logbooks_count > 0) ? 'has_pending' : 'clean';

                            if ($now->lessThan($start)) {
                                $periodStatusKey = 'not_started';
                            } elseif ($now->greaterThan($end)) {
                                $periodStatusKey = 'finished';
                            } else {
                                $periodStatusKey = 'ongoing';
                            }
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors group"
                            data-name="{{ strtolower($reg->student->name) }}"
                            data-university="{{ strtolower($reg->student->university) }}"
                            data-study_program="{{ strtolower($reg->student->study_program) }}"
                            data-period_status="{{ $periodStatusKey }}"
                            data-report_status="{{ $reg->report_status ? strtolower($reg->report_status) : 'none' }}"
                            data-logbook_status="{{ $logbookStatusKey }}">

                            {{-- INFO MAHASISWA --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ substr($reg->student->name, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $reg->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reg->student->university }}</div>
                                        <div class="text-xs text-gray-400">{{ $reg->student->study_program }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- PERIODE --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($reg->start_date)->format('d M') }} -
                                    {{ \Carbon\Carbon::parse($reg->end_date)->format('d M Y') }}
                                </div>

                                @if($periodStatusKey == 'not_started')
                                    <span class="px-2 inline-flex text-[10px] font-semibold rounded-full bg-yellow-100 text-yellow-800 mt-1">
                                        Belum Mulai ({{ round($now->diffInDays($start)) }} hari lagi)
                                    </span>
                                @elseif($periodStatusKey == 'finished')
                                    <span class="px-2 inline-flex text-[10px] font-semibold rounded-full bg-green-100 text-green-800 mt-1">
                                        Selesai Magang
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-[10px] font-semibold rounded-full bg-blue-100 text-blue-800 mt-1">
                                        Sisa {{ round($now->diffInDays($end)) }} hari
                                    </span>
                                @endif
                            </td>

                            {{-- KEHADIRAN  --}}
                            <td class="px-6 py-4 align-middle">
                                @php
                                    $totalPresent = $reg->attendances->whereIn('status', ['present', 'sick', 'permission'])->count();
                                    $totalDays = \Carbon\Carbon::parse($reg->start_date)->diffInDays(\Carbon\Carbon::parse($reg->end_date)) + 1;
                                    $percent = $totalDays > 0 ? round(($totalPresent / $totalDays) * 100) : 0;
                                    $percent = min(100, $percent);
                                @endphp
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 flex justify-between">
                                    <span>{{ $percent }}%</span>
                                    <span>{{ $totalPresent }}/{{ $totalDays }} Hari</span>
                                </div>
                            </td>

                            {{-- 4. LOGBOOK --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $pendingLogbooks = $reg->logbooks->where('status', 'pending')->count();
                                @endphp
                                @if($reg->pending_logbooks_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        {{ $reg->pending_logbooks_count }} Perlu Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        Up to date
                                    </span>
                                @endif
                                <div class="text-xs text-gray-400 mt-1">Total: {{ $reg->pending_logbooks_count }} Kegiatan</div>
                            </td>

                            {{-- LAPORAN --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($reg->report_status == 'submitted')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 animate-pulse">
                                        Menunggu Review
                                    </span>
                                @elseif($reg->report_status == 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @elseif($reg->report_status == 'revision')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Revisi
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-sm flex items-right gap-3">

                                {{-- DETAIL --}}
                                <a href="{{ route("pembimbing.mahasiswa.show", $reg->id) }}" class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all duration-150" title="Lihat Detail">
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
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <p class="text-sm font-medium">Anda belum memiliki mahasiswa bimbingan.</p>
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
