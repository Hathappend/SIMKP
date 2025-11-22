@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                        Dashboard Magang
                    </h1>
                    <p class="text-gray-500 mt-1">
                        Selamat datang kembali, <span class="font-semibold text-[#1B2A52]">{{ $student->name }}</span> 👋
                    </p>
                </div>

                {{-- Tombol Download Surat --}}
                @if($registration->reply_letter_path)
                    <a href="{{ route('mahasiswa.download-surat') }}" target="_blank"
                       class="group inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:border-red-200 hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                        <div class="p-1.5 bg-red-100 rounded-lg text-red-600 mr-3 group-hover:bg-red-200 transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <span>Download Surat Balasan</span>
                    </a>
                @endif
            </div>

            {{-- HASIL PENILAIAN TIM  --}}
            @if($teamAssessments->count() > 0)
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Hasil Penilaian Akhir</h2>
                            <p class="text-sm text-gray-500">Selamat! Berikut adalah hasil penilaian untuk tim Anda.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                        @foreach($teamAssessments as $result)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden relative group hover:shadow-md transition-all duration-300">

                                <div class="h-2 bg-gradient-to-r from-[#1B2A52] to-blue-600"></div>

                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        {{-- Info Mahasiswa --}}
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-lg border border-gray-200">
                                                {{ substr($result->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h4 class="text-base font-bold text-gray-900 line-clamp-1" title="{{ $result->name }}">{{ $result->name }}</h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $result->nim }}</span>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded
                                                    {{ $result->role == 'Ketua Tim' ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-500' }}">
                                                    {{ $result->role }}
                                                </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Grade Besar --}}
                                        <div class="text-center">
                                            <span class="block text-3xl font-extrabold text-[#1B2A52]">{{ $result->score->grade }}</span>
                                            <span class="block text-[10px] text-gray-400 font-bold uppercase">Grade</span>
                                        </div>
                                    </div>

                                    <hr class="my-4 border-gray-100 border-dashed">

                                    {{-- Detail Nilai Ringkas --}}
                                    <div class="grid grid-cols-3 gap-2 text-center mb-5">
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <span class="block text-[10px] text-gray-400 uppercase">Teknis</span>
                                            <span class="font-bold text-gray-700">{{ $result->score->score_technical }}</span>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <span class="block text-[10px] text-gray-400 uppercase">Disiplin</span>
                                            <span class="font-bold text-gray-700">{{ $result->score->score_discipline }}</span>
                                        </div>
                                        <div class="bg-indigo-50 rounded-lg p-2 border border-indigo-100">
                                            <span class="block text-[10px] text-indigo-400 uppercase">Total</span>
                                            <span class="font-bold text-indigo-700">{{ $result->score->final_score }}</span>
                                        </div>
                                    </div>

                                    {{-- Tombol Download --}}
                                    @if($result->score->certificate_path)
                                        <a href="{{ Storage::url($result->score->certificate_path) }}" target="_blank"
                                           class="flex items-center justify-center w-full py-2.5 bg-white border border-gray-300 hover:border-indigo-300 hover:text-indigo-600 text-gray-700 font-bold text-sm rounded-xl transition-colors gap-2 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                            Download Sertifikat
                                        </a>
                                    @else
                                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 text-sm font-bold rounded-xl cursor-not-allowed">
                                            Sertifikat Belum Terbit
                                        </button>
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- INFORMASI PENEMPATAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-60 -mr-10 -mt-10"></div>

                    <h3 class="text-base font-semibold text-gray-800 mb-5 relative z-10">Informasi Penempatan</h3>

                    <div class="space-y-5 relative z-10">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Divisi</p>
                                <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $registration->division->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Pembimbing</p>
                                <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $registration->mentor->name ?? 'Belum ditentukan' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Periode</p>
                                <p class="text-sm font-bold text-gray-900 mt-0.5">
                                    {{ \Carbon\Carbon::parse($registration->start_date)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($registration->end_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PERFORMA KEHADIRAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-6">Performa Kehadiran</h3>

                        <div class="flex items-end justify-between mb-2">
                            <div class="flex items-baseline gap-1">
                                {{-- Total Hadir Real --}}
                                <span class="text-5xl font-bold text-[#1B2A52]">{{ $totalAttendance }}</span>
                                <span class="text-gray-500 font-medium">Hari</span>
                            </div>
                            {{-- Target Hari Kerja s/d Hari Ini --}}
                            <span class="text-sm text-gray-400 mb-1.5">
                            Target: {{ $workingDaysPassed }} Hari Kerja
                        </span>
                        </div>

                        {{-- Progress Bar Dinamis --}}
                        <div class="w-full bg-gray-100 rounded-full h-4 mb-4 overflow-hidden">
                            @php
                                $barColor = 'bg-red-500';
                                if($attendancePercentage >= 80) $barColor = 'bg-[#1B2A52]';
                                elseif($attendancePercentage >= 50) $barColor = 'bg-yellow-500';
                            @endphp

                            <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out relative"
                                 style="width: {{ $attendancePercentage }}%">
                                <div class="absolute inset-0 bg-white/20 w-full h-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-4 mt-2">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-700">{{ $attendancePercentage }}%</span>
                            <span class="text-xs text-gray-400">Tingkat Kehadiran</span>
                        </div>

                        <div class="text-right flex flex-col">
                            <span class="font-medium text-gray-700">{{ round($daysRemaining) }} Hari</span>
                            <span class="text-xs text-gray-400">Sisa Masa Magang</span>
                        </div>
                    </div>
                </div>

                {{-- STATUS LAPORAN AKHIR --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-base font-semibold text-gray-800">Laporan Akhir</h3>
                        <a href="{{ route('mahasiswa.laporan.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                            Detail
                        </a>
                    </div>

                    <div class="flex-1 flex flex-col justify-center items-center text-center">
                        @if(!$registration->report_status)
                            {{-- Belum Upload --}}
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            </div>
                            <h4 class="font-bold text-gray-800">Belum Diupload</h4>
                            <p class="text-xs text-gray-500 mt-1 px-4">Segera upload laporan akhir Anda untuk divalidasi.</p>
                            <a href="{{ route('mahasiswa.laporan.index') }}" class="mt-4 px-4 py-2 bg-gray-900 text-white text-xs font-medium rounded-lg hover:bg-gray-800">Upload Sekarang</a>

                        @elseif($registration->report_status == 'submitted')
                            {{-- Menunggu Review --}}
                            <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center mb-3 relative">
                                <div class="absolute inset-0 rounded-full border-2 border-blue-200 animate-ping opacity-75"></div>
                                <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="font-bold text-blue-700">Menunggu Review</h4>
                            <p class="text-xs text-blue-600/80 mt-1 px-4">Laporan sedang diperiksa oleh Pembimbing Lapangan.</p>

                        @elseif($registration->report_status == 'revision')
                            {{-- Revisi --}}
                            <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h4 class="font-bold text-red-700">Perlu Revisi</h4>
                            <p class="text-xs text-red-600/80 mt-1 px-4">Ada catatan perbaikan dari pembimbing. Cek detail.</p>
                            <a href="{{ route('mahasiswa.laporan.index') }}" class="mt-4 px-4 py-2 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">Lihat Revisi</a>

                        @elseif($registration->report_status == 'approved')
                            {{-- Approved --}}
                            <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="font-bold text-green-700">Selesai & Disetujui</h4>
                            <p class="text-xs text-green-600/80 mt-1 px-4">Laporan akhir Anda valid dan telah diterima.</p>
                        @endif
                    </div>
                </div>
            </div>


            {{-- LOGBOOK SECTION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Logbook Hari Ini
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    <a href="{{ route('mahasiswa.logbook.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Isi Logbook
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($todayLogbooks as $log)
                        <div class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all">
                            <div class="flex items-start gap-4 w-full">
                                {{-- Jam --}}
                                <div class="bg-white text-gray-800 font-bold px-3 py-2 rounded-lg shadow-sm border border-gray-100 text-sm min-w-[80px] text-center">
                                    {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }}
                                </div>

                                {{-- Detail Kegiatan --}}
                                <div class="flex-1">
                                    <h4 class="text-gray-900 font-semibold text-sm group-hover:text-indigo-700 transition-colors">
                                        {{ $log->activity }}
                                    </h4>
                                    <p class="text-gray-500 text-xs mt-1 line-clamp-1">
                                        {{ $log->description ?? 'Tidak ada deskripsi detail.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 sm:mt-0 flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                                {{-- Durasi --}}
                                @php
                                    $start = \Carbon\Carbon::parse($log->start_time);
                                    $end = \Carbon\Carbon::parse($log->end_time);
                                    $diff = $start->diffInHours($end);
                                    if ($diff == 0) $diff = $start->diffInMinutes($end) . 'm';
                                    else $diff .= ' jam';
                                @endphp
                                <div class="text-xs text-gray-400 hidden sm:block whitespace-nowrap">{{ $diff }}</div>

                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-800 border-{{ $log->status_color }}-200">
                                {{ $log->status_label }}
                            </span>
                            </div>
                        </div>
                    @empty
                        {{-- EMPTY STATE --}}
                        <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl bg-white">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">Belum ada kegiatan yang dicatat hari ini.</p>
                            <p class="text-xs text-gray-400 mt-1">Yuk, catat apa yang kamu kerjakan sekarang!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
