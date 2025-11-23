@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-gray-500 mt-1">Ringkasan aktivitas sistem magang Diskominfo.</p>
            </div>

            {{-- STATISTIK CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                {{-- Pengajuan Masuk --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengajuan Masuk</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-[#1B2A52] transition-colors">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-[#1B2A52] group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-blue-600 font-medium cursor-pointer hover:underline">
                        <a href="{{ route('admin.registration.create') }}">Lihat Detail &rarr;</a>
                    </div>
                </div>

                {{-- Mahasiswa Aktif --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Sedang Magang</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-green-600 transition-colors">{{ $stats['active'] }}</h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-400">Mahasiswa aktif di berbagai divisi</div>
                </div>

                {{-- Surat Pending --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Antrian Surat</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-yellow-600 transition-colors">{{ $stats['letters'] }}</h3>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-yellow-600 font-medium cursor-pointer hover:underline">
                        <a href="{{ route('admin.surat.index') }}">Proses Sekarang &rarr;</a>
                    </div>
                </div>

                {{-- Total Mentor --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pembimbing</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-purple-600 transition-colors">{{ $stats['mentors'] }}</h3>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-purple-600 font-medium cursor-pointer hover:underline">
                        <a href="{{ route('admin.pembimbing.index') }}">Kelola Data &rarr;</a>
                    </div>
                </div>

            </div>

            {{-- Grafik & Aktivitas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- CHART SECTION --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Tren Pendaftaran Magang</h3>
                        <div class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                            6 Bulan Terakhir
                        </div>
                    </div>

                    <div class="h-64 flex items-end justify-between gap-3 px-2 sm:px-4 pb-2">

                        @foreach($chart['labels'] as $index => $month)
                            <div class="w-full flex flex-col items-center gap-2 group cursor-pointer h-full justify-end">

                                {{-- Container Batang --}}
                                <div class="relative w-full bg-indigo-50/50 rounded-t-lg hover:bg-indigo-100 transition-all flex flex-col justify-end h-full">

                                    @php
                                        $hasData = $chart['counts'][$index] > 0;
                                        $barColor = $hasData ? 'bg-[#1B2A52]' : 'bg-gray-200';
                                        $height = $chart['percentages'][$index];
                                    @endphp

                                    <div class="w-full {{ $barColor }} rounded-t-lg transition-all duration-1000 ease-out group-hover:opacity-80 relative"
                                         style="height: {{ $height }}%;">

                                    </div>

                                    {{-- Tooltip Hover --}}
                                    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-bold py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity shadow-lg z-20 whitespace-nowrap pointer-events-none">
                                        {{ $chart['counts'][$index] }} Pendaftar
                                    </div>
                                </div>

                                {{-- Label Bulan --}}
                                <span class="text-xs font-medium text-gray-500">{{ $month }}</span>
                            </div>
                        @endforeach

                    </div>
                </div>

                {{-- RECENT ACTIVITY --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pendaftaran Terbaru</h3>

                    <div class="flex-1">
                        @forelse($recentActivities as $reg)
                            <div class="flex items-start gap-3 pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                                {{-- Avatar --}}
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 shrink-0">
                                    {{ substr($reg->student->name, 0, 2) }}
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate" title="{{ $reg->student->name }}">
                                        {{ $reg->student->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">{{ $reg->student->university }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $reg->created_at->diffForHumans() }}</p>
                                </div>

                                {{-- Status Badge --}}
                                @if($reg->application_status == 'pending')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700">Baru</span>
                                @elseif($reg->application_status == 'approved')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Aktif</span>
                                @elseif($reg->application_status == 'completed')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700">Selesai</span>
                                @elseif($reg->application_status == 'waiting')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700">Ditinjau</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Tolak</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                Belum ada aktivitas pendaftaran.
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol Lihat Semua --}}
                    <a href="{{ route('admin.registration.create') }}" class="block text-center text-sm font-bold text-indigo-600 hover:text-indigo-800 mt-4 pt-3 border-t border-gray-100 transition-colors">
                        Lihat Semua Pendaftaran &rarr;
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
