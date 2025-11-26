@extends('layouts.app')
@section('title', "Lihat Data Mahasiswa")
@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Mahasiswa Divisi</h1>
                <p class="text-gray-500 mt-1">
                    Monitoring peserta magang di divisi <strong>{{ Auth::user()->division->name ?? 'Anda' }}</strong>.
                </p>
            </div>

            {{-- 1. STATISTIK MONITORING --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Card Aktif (Paling Penting) --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-green-100 flex items-center gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Magang</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active'] }} <span class="text-sm font-normal text-gray-500">Orang</span></h3>
                    </div>
                </div>

                {{-- Card Selesai --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-blue-100 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alumni / Selesai</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }} <span class="text-sm font-normal text-gray-500">Orang</span></h3>
                    </div>
                </div>

                {{-- Card Total --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-gray-100 text-gray-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Data</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>

            {{-- 2. FILTER & SEARCH --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['student_name', 'university', 'mentor_name'];
                        $filters = [
                            ['key' => 'status', 'label' => 'Status', 'type' => 'checkbox-list', 'options' => [
                                ['value' => 'approved', 'label' => 'Sedang Magang'],
                                ['value' => 'completed', 'label' => 'Selesai'],
                            ]],
                             ['key' => 'mentor_name', 'label' => 'Pembimbing', 'type' => 'text'] // Bisa diganti dropdown jika data mentor dikirim dari controller
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#kadivTable" />
                </div>
            </div>

            {{-- 3. TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="kadivTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/4">Durasi & Progress</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($students as $reg)
                        <tr class="hover:bg-gray-50 transition-colors group"
                            data-student_name="{{ strtolower($reg->student->name) }}"
                            data-university="{{ strtolower($reg->student->university) }}"
                            data-mentor_name="{{ strtolower($reg->mentor->name ?? '') }}"
                            data-status="{{ $reg->application_status }}"
                        >
                            {{-- Info Mahasiswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold mr-3 border border-indigo-100">
                                        {{ substr($reg->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $reg->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reg->student->university }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Pembimbing --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-800">{{ $reg->mentor->name ?? 'Belum ditentukan' }}</div>
                                <div class="text-xs text-gray-500">{{ $reg->mentor->position ?? '-' }}</div>
                            </td>

                            {{-- Progress Waktu --}}
                            <td class="px-6 py-4">
                                @php
                                    $start = \Carbon\Carbon::parse($reg->start_date);
                                    $end = \Carbon\Carbon::parse($reg->end_date);
                                    $now = now();

                                    // Hitung Total Durasi (Inklusif)
                                    $totalDays = $start->diffInDays($end) + 1;

                                    // Hitung Hari Berjalan
                                    if ($now->lessThan($start)) {
                                        $daysPassed = 0;
                                    } elseif ($now->greaterThan($end)) {
                                        $daysPassed = $totalDays;
                                    } else {
                                        $daysPassed = $start->diffInDays($now) + 1;
                                    }

                                    // itung Persentase
                                    $percent = $totalDays > 0 ? round(($daysPassed / $totalDays) * 100) : 0;
                                @endphp

                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1.5">
                                    {{-- Progress Bar --}}
                                    <div class="bg-[#1B2A52] h-1.5 rounded-full transition-all duration-500"
                                         style="width: {{ $percent }}%"></div>
                                </div>

                                <div class="flex justify-between items-center text-[10px] text-gray-500">
                                    {{-- Periode --}}
                                    <span>{{ $start->format('d M') }} - {{ $end->format('d M') }}</span>

                                    {{-- Label Status --}}
                                    @if($reg->application_status == 'completed' || $percent >= 100)
                                        <span class="font-bold text-green-600">Selesai</span>
                                    @elseif($percent == 0)
                                        <span class="text-yellow-600 font-medium">Belum Mulai</span>
                                    @else
                                        <span class="font-medium text-indigo-600">{{ $percent }}%</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($reg->application_status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kadiv.mahasiswa.show', $reg->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition-colors">
                                    Detail
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
                            <td colspan="100%" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-lg font-medium">Belum ada pengajuan magang.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang magang di divisi anda.</p>
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
