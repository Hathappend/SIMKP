@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER & FILTER --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
                <div class="w-full md:w-auto">
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Penilaian Akhir</h1>
                    <p class="text-gray-500 mt-1">Berikan nilai kepada mahasiswa yang telah menyelesaikan masa magang.</p>
                </div>

                {{-- FILTER BAR --}}
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['name', 'university', 'nim'];

                        $filters = [
                            // Filter Status Magang
                            [
                                'key' => 'status',
                                'label' => 'Status Magang',
                                'type' => 'checkbox-list',
                                'options' => [
                                    ['value' => 'approved', 'label' => 'Sedang Berjalan'],
                                    ['value' => 'completed', 'label' => 'Selesai'],
                                ]
                            ],
                            // Filter Status Nilai
                            [
                                'key' => 'grading_status',
                                'label' => 'Status Nilai',
                                'type' => 'checkbox-list',
                                'open' => true,
                                'options' => [
                                    ['value' => 'graded', 'label' => 'Sudah Dinilai'],
                                    ['value' => 'ungraded', 'label' => 'Belum Dinilai'],
                                ]
                            ],
                            // Filter Tanggal Selesai
                            ['key' => 'end_date', 'label' => 'Tgl Selesai', 'type' => 'date-range']
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#gradingTable" />
                </div>
            </div>

            {{-- PRIORITY --}}
            @if($priorityStudents->count() > 0)
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                        Segera Berikan Nilai
                        <span class="text-sm font-normal text-gray-500 ml-2">(Masa magang hampir/sudah selesai)</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($priorityStudents as $reg)
                            <div class="bg-white rounded-xl border border-red-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>

                                <div class="p-5 pl-7">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center text-sm font-bold">
                                                {{ substr($reg->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $reg->student->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $reg->student->nim }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4 bg-red-50 rounded-lg p-2 text-xs text-red-700 font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @php
                                            $end = \Carbon\Carbon::parse($reg->end_date);
                                            $diff = now()->diffInDays($end, false);
                                        @endphp
                                        @if($diff < 0)
                                            Selesai {{ abs(round($diff)) }} hari lalu
                                        @else
                                            Berakhir dalam {{ round($diff) }} hari
                                        @endif
                                    </div>

                                    <a href="{{ route('pembimbing.penilaian.create', $reg->id) }}" class="block w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-center text-sm font-bold rounded-lg transition-colors shadow-sm">
                                        Input Nilai Sekarang
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- TABEL UTAMA (HISTORY & ONGOING) --}}
            @php
                $allStudents = $gradedStudents->merge($ongoingStudents)->sortByDesc('end_date');
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-8">
                <table id="gradingTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Selesai Magang</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Nilai Akhir</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($allStudents as $reg)
                        <tr class="hover:bg-gray-50 transition-colors group"
                            {{-- DATA ATTRIBUTES UNTUK FILTER --}}
                            data-name="{{ strtolower($reg->student->name) }}"
                            data-university="{{ strtolower($reg->student->university) }}"
                            data-nim="{{ strtolower($reg->student->nim) }}"
                            data-status="{{ strtolower($reg->application_status) }}"
                            data-grading_status="{{ $reg->assessment ? 'graded' : 'ungraded' }}"
                            data-end_date="{{ $reg->end_date }}" {{-- Format Y-m-d --}}
                        >
                            {{-- Mahasiswa --}}
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

                            {{-- Tanggal Selesai --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($reg->end_date)->translatedFormat('d M Y') }}
                                <br>
                                @if($reg->application_status == 'completed')
                                    <span class="text-[10px] text-green-600 bg-green-50 px-1.5 py-0.5 rounded font-bold">Selesai</span>
                                @else
                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded font-bold">Berjalan</span>
                                @endif
                            </td>

                            {{-- Nilai --}}
                            <td class="px-6 py-4 text-center">
                                @if($reg->assessment)
                                    <span class="font-mono font-bold text-gray-800 text-lg">{{ $reg->assessment->final_score }}</span>
                                @else
                                    <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </td>

                            {{-- Grade --}}
                            <td class="px-6 py-4 text-center">
                                @if($reg->assessment)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm
                                        {{ $reg->assessment->grade == 'A' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                        {{ $reg->assessment->grade }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Belum Dinilai
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                @if($reg->assessment)
                                    <a href="{{ route('pembimbing.penilaian.show', $reg->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase tracking-wide hover:underline">
                                        Lihat Detail
                                    </a>
                                @else
                                    <a href="{{ route('pembimbing.penilaian.add', $reg->id) }}" class="inline-flex items-center px-3 py-1.5 bg-[#1B2A52] text-white text-xs font-bold rounded hover:bg-blue-900 transition-colors shadow-sm">
                                        Input Nilai
                                    </a>
                                @endif
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
                                    <p class="text-lg font-medium">Belum ada mahasiswa untuk dinilai.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang bisa dinilai.</p>
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
