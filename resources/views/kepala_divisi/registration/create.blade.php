@extends('layouts.app')
@section('title', "Verifikasi Pengajuan Magang")
@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Verifikasi Pengajuan</h1>
                <p class="text-gray-500 mt-1">Tinjau dan setujui permohonan magang yang masuk ke divisi Anda.</p>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Menunggu  --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border transition-all group relative overflow-hidden
                {{ $stats['waiting'] > 0 ? 'border-yellow-300 ring-1 ring-yellow-100' : 'border-gray-200' }}">

                    @if($stats['waiting'] > 0)
                        <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-100 rounded-bl-full -mr-2 -mt-2"></div>
                    @endif

                    <div class="flex items-center gap-4 relative z-10">
                        <div class="p-3 rounded-xl {{ $stats['waiting'] > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-400' }}">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider {{ $stats['waiting'] > 0 ? 'text-yellow-700' : 'text-gray-400' }}">Perlu Tindakan</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $stats['waiting'] }}</h3>
                        </div>
                    </div>
                    @if($stats['waiting'] > 0)
                        <p class="text-xs text-yellow-600 mt-3 font-medium animate-pulse">Ada pengajuan baru menunggu Anda!</p>
                    @endif
                </div>

                {{-- Disetujui --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Diterima</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</h3>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                    <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ditolak</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
            </div>

            {{-- SEARCH & FILTER --}}
            <div class="flex flex-col md:flex-row justify-end items-end md:items-center mb-6 gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['full_name', 'university', 'study_program'];
                        $filters = [
                            ['key' => 'status', 'label' => 'Status', 'type' => 'checkbox-list', 'options' => [
                                ['value' => 'waiting', 'label' => 'Menunggu'],
                                ['value' => 'approved', 'label' => 'Diterima'],
                                ['value' => 'rejected', 'label' => 'Ditolak'],
                            ]],
                            ['key' => 'date', 'label' => 'Tanggal', 'type' => 'date-range']
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#verifikasiTable" />
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="verifikasiTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kampus</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($applications as $index => $app)
                        <tr class="hover:bg-gray-50 transition-colors group {{ $app->application_status == 'waiting' ? 'bg-yellow-50/30' : '' }}"
                            data-full_name="{{ strtolower($app->student->name) }}"
                            data-university="{{ strtolower($app->student->university) }}"
                            data-study_program="{{ strtolower($app->student->study_program) }}"
                            data-status="{{ strtolower($app->application_status) }}"
                            data-date="{{ $app->created_at->format('Y-m-d') }}"
                        >
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>

                            {{-- Mahasiswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold mr-3 border border-indigo-100">
                                        {{ substr($app->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $app->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->student->study_program }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kampus --}}
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $app->student->university }}</td>

                            {{-- Tanggal --}}
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                {{ $app->created_at->format('d M Y') }}
                                <div class="text-[10px] text-gray-400">{{ $app->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($app->application_status == 'waiting')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Menunggu Review
                                    </span>
                                @elseif($app->application_status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        Diterima
                                    </span>
                                @elseif($app->application_status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                @if($app->application_status == 'waiting')
                                    <a href="{{ route('kadiv.pengajuan.show', $app->id) }}"
                                       class="inline-flex items-center px-4 py-1.5 bg-[#1B2A52] text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition-colors shadow-sm">
                                        Review
                                    </a>
                                @else
                                    <a href="{{ route('kadiv.pengajuan.show', $app->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition-colors">
                                        Detail
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
                                    <p class="text-lg font-medium">Belum ada pengajuan magang.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang konfirmasi admin.</p>
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
