@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Pembimbing</h1>
                <p class="text-gray-500 mt-1">Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Anda memiliki tugas yang perlu diselesaikan.</p>
            </div>

            {{-- 1. STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

                {{-- Total Mahasiswa --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4 group hover:border-indigo-300 transition-all">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa Bimbingan</p>
                    </div>
                </div>

                {{-- Logbook Pending (Action Needed) --}}
                <a href="{{ route('pembimbing.mahasiswa.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4 group hover:border-yellow-400 transition-all cursor-pointer relative overflow-hidden">
                    @if($pendingLogbooksCount > 0)
                        <div class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full m-4 animate-ping"></div>
                    @endif
                    <div class="p-3 {{ $pendingLogbooksCount > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-50 text-gray-400' }} rounded-xl group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold {{ $pendingLogbooksCount > 0 ? 'text-yellow-700' : 'text-gray-900' }}">{{ $pendingLogbooksCount }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Logbook Menunggu</p>
                    </div>
                </a>

                {{-- Laporan Pending --}}
                <a href="{{ route('pembimbing.laporan.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-4 group hover:border-blue-300 transition-all cursor-pointer">
                    <div class="p-3 {{ $pendingReportsCount > 0 ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-400' }} rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold {{ $pendingReportsCount > 0 ? 'text-blue-700' : 'text-gray-900' }}">{{ $pendingReportsCount }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Review Laporan</p>
                    </div>
                </a>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- 2. SECTION: INBOX LOGBOOK (Priority) --}}
                <div class="lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Menunggu Persetujuan (Logbook)</h3>
                        <a href="{{ route('pembimbing.mahasiswa.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        @forelse($recentPendingLogbooks as $log)
                            <div class="p-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4 items-start sm:items-center">

                                {{-- Info Mahasiswa --}}
                                <div class="flex items-center gap-3 w-full sm:w-1/3">
                                    <div class="w-10 h-10 rounded-full bg-[#1B2A52] text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ substr($log->student->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $log->student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->date }}</p>
                                    </div>
                                </div>

                                {{-- Isi Logbook Ringkas --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $log->activity }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $log->description }}</p>
                                </div>

                                {{-- Tombol Aksi --}}
                                <a href="{{ route('pembimbing.mahasiswa.show', $log->student->registrations->last()->id) }}"
                                   class="whitespace-nowrap px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-[#1B2A52] hover:text-white hover:border-[#1B2A52] transition-colors shadow-sm">
                                    Review Detail
                                </a>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <div class="inline-flex p-3 bg-green-50 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Semua logbook sudah diperiksa!</p>
                                <p class="text-xs text-gray-500">Kerja bagus, tidak ada tugas tertunda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 3. SECTION: PROGRESS MAHASISWA (Sidebar) --}}
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Status Mahasiswa</h3>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-5">
                        @forelse($myStudents as $reg)
                            @php
                                $start = \Carbon\Carbon::parse($reg->start_date);
                                $end = \Carbon\Carbon::parse($reg->end_date);
                                $now = now();

                                $total = $start->diffInDays($end) + 1;
                                $passed = $now->greaterThan($start) ? $start->diffInDays($now) + 1 : 0;
                                $percent = min(100, round(($passed / $total) * 100));
                            @endphp

                            <div>
                                <div class="flex justify-between text-xs mb-1.5">
                                    <span class="font-bold text-gray-700">{{ $reg->student->name }}</span>
                                    <span class="font-medium {{ $percent >= 100 ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $percent >= 100 ? 'Selesai' : $percent.'%' }}
                                </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="{{ $percent >= 100 ? 'bg-green-500' : 'bg-[#1B2A52]' }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 text-center italic">Belum ada mahasiswa.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
