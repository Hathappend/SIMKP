@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Kepala Divisi</h1>
                <p class="text-gray-500 mt-1">Monitoring aktivitas magang di divisi <strong>{{ Auth::user()->division->name ?? 'Anda' }}</strong>.</p>
            </div>

            {{-- 1. STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

                {{-- Active --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between group hover:border-green-300 transition-all">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Magang</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>

                {{-- Pending (Action Needed) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between group hover:border-blue-300 transition-all">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perlu Persetujuan</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
                            @if($stats['pending'] > 0)
                                <span class="flex h-2 w-2 relative">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between group hover:border-gray-300 transition-all">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai / Alumni</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 text-gray-600 rounded-xl group-hover:bg-gray-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>

            </div>

            {{-- GRID UTAMA --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- 2. NEED ACTION (Pengajuan Masuk) --}}
                <div class="lg:col-span-2 space-y-6">

                    @if($pendingApplications->count() > 0)
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-6 relative overflow-hidden">
                            {{-- Dekorasi --}}
                            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-100 rounded-full blur-2xl -mr-10 -mt-10 opacity-50"></div>

                            <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                Permintaan Persetujuan Baru
                            </h3>

                            <div class="space-y-3 relative z-10">
                                @foreach($pendingApplications as $app)
                                    <div class="bg-white p-4 rounded-xl border border-blue-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:shadow-md transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                                {{ substr($app->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $app->student->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $app->student->university }} • {{ $app->student->study_program }}</p>
                                                <p class="text-[10px] text-gray-400 mt-0.5">Diajukan {{ $app->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('kadiv.pengajuan.show', $app->id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                            Review
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 text-center">
                                <a href="{{ route('kadiv.pengajuan.index') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua Permintaan &rarr;</a>
                            </div>
                        </div>
                    @else
                        {{-- Empty State untuk Task --}}
                        <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center">
                            <div class="inline-flex p-3 bg-green-50 text-green-600 rounded-full mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Semua Beres!</h3>
                            <p class="text-gray-500 text-sm">Tidak ada pengajuan baru yang perlu persetujuan Anda saat ini.</p>
                        </div>
                    @endif
                </div>

                {{-- 3. MAHASISWA AKTIF (Sidebar Kanan) --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900">Sedang Magang</h3>
                        <a href="{{ route('kadiv.mahasiswa.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($activeInterns as $intern)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($intern->student->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $intern->student->name }}</p>
                                        <p class="text-[10px] text-gray-500 truncate">{{ $intern->student->university }}</p>
                                    </div>
                                </div>

                                {{-- Progress Bar Sederhana (Sisa Hari) --}}
                                @php
                                    $start = \Carbon\Carbon::parse($intern->start_date);
                                    $end = \Carbon\Carbon::parse($intern->end_date);
                                    $total = $start->diffInDays($end) + 1;
                                    $passed = now()->diffInDays($start) + 1;
                                    $percent = min(100, round(($passed / $total) * 100));
                                @endphp

                                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1">
                                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="flex justify-between text-[10px] text-gray-400">
                                    <span>{{ $percent }}% Berjalan</span>
                                    <span>Mentor: {{ explode(' ', $intern->mentor->name ?? '-')[0] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-400 text-sm">
                                Belum ada mahasiswa aktif.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
