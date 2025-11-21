@extends('layouts.app')

@section('content')
    <div class="min-h-screen "
         x-data="{
            activeTab: 'logbook',
            modalReject: false,
            selectedLogbookId: null,
            rejectUrl: ''
        }">

        {{-- HEADER--}}
        <div class="border-b border-gray-200 shadow-[0_2px_8px_rgba(0,0,0,0.02)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Tombol Back --}}
                <div class="py-4">
                    <a href="{{ route('pembimbing.mahasiswa.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#1B2A52] transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Kembali
                    </a>
                </div>

                {{-- Profil & Stats --}}
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 pb-6">

                    {{-- INFO MAHASISWA --}}
                    <div class="flex items-start gap-5">
                        {{-- Avatar --}}
                        <div class="w-16 h-16 rounded-2xl bg-[#1B2A52] text-white flex items-center justify-center text-2xl font-bold shadow-lg shadow-indigo-900/10 flex-shrink-0">
                            {{ substr($registration->student->name, 0, 2) }}
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight leading-tight mb-2">
                                {{ $registration->student->name }}
                            </h1>

                            <div class="space-y-3">
                                {{-- NIM & Univ --}}
                                <div class="flex flex-wrap items-center gap-y-2 gap-x-3 text-sm text-gray-500">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-gray-100 border border-gray-200 text-gray-700 font-mono text-xs">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                        {{ $registration->student->nim }}
                                    </div>
                                    <span class="text-gray-300 hidden px-2 sm:inline">|</span>
                                    <div class="flex items-center gap-1.5 px-1">
                                        <span>{{ $registration->student->university }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- STATISTIK --}}
                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-100 p-1.5 self-start lg:self-center">

                        {{-- Sisa Waktu --}}
                        <div class="px-5 py-2 text-center border-r border-gray-200 last:border-0">
                            @php
                                $start = \Carbon\Carbon::parse($registration->start_date);
                                $end = \Carbon\Carbon::parse($registration->end_date);
                                $now = now();
                            @endphp

                            @if($now->lessThan($start))
                                {{-- KONDISI: BELUM MULAI --}}
                                <span class="block text-[10px] uppercase tracking-wider font-bold text-gray-400">Status Waktu</span>
                                <span class="block text-sm font-bold text-gray-800 leading-tight mt-1">Belum Mulai</span>

                            @elseif($now->greaterThan($end))
                                {{-- KONDISI: SELESAI --}}
                                <span class="block text-[10px] uppercase tracking-wider font-bold text-gray-400">Status Waktu</span>
                                <span class="block text-sm font-bold text-gray-800 leading-tight mt-1">Selesai</span>

                            @else
                                {{-- KONDISI: SEDANG BERJALAN --}}
                                <span class="block text-[10px] uppercase tracking-wider font-bold text-gray-400">Sisa Waktu</span>
                                <span class="block text-lg font-bold text-gray-800 leading-tight">
                                {{ round($now->diffInDays($end)) }}
                                <span class="text-xs font-normal text-gray-500">Hari</span>
                            </span>
                            @endif
                        </div>

                        {{-- Logbook --}}
                        <div class="px-5 py-2 text-center border-r border-gray-200 last:border-0 relative group cursor-help">
                            <span class="block text-[10px] uppercase tracking-wider font-bold text-gray-400">Logbook</span>
                            <div class="flex items-center justify-center gap-1">
                                <span class="block text-lg font-bold text-gray-800 leading-tight">{{ $totalLogbook }}</span>
                                @if($pendingLogbook > 0)
                                    <span class="flex h-2 w-2 relative -mt-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                </span>
                                @endif
                            </div>
                            {{-- Tooltip sederhana --}}
                            @if($pendingLogbook > 0)
                                <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                    {{ $pendingLogbook }} perlu review
                                </div>
                            @endif
                        </div>

                        {{-- Kehadiran --}}
                        <div class="px-5 py-2 text-center">
                            <span class="block text-[10px] uppercase tracking-wider font-bold text-gray-400">Hadir</span>
                            <span class="block text-lg font-bold text-gray-800 leading-tight">{{ $totalAttendance }} <span class="text-xs font-normal text-gray-500">Kali</span></span>
                        </div>

                    </div>
                </div>

                {{-- ANGGOTA TIM --}}
                @if($registration->members->count() > 0)
                    <div class="pt-3 border-t border-gray-100 mt-6">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Anggota Tim
                        </p>

                        <div class="flex flex-wrap gap-3">
                            @foreach($registration->members as $member)
                                <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-lg p-2 pr-4 shadow-sm hover:border-indigo-200 transition-colors group">
                                    {{-- Avatar Kecil --}}
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-800 leading-none">{{ $member->name }}</span>
                                        <span class="text-[10px] text-gray-500 mt-0.5 font-mono">{{ $member->nim }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tabs --}}
                <div class="flex mt-8 gap-8">
                    <button @click="activeTab = 'logbook'"
                            class="pb-3 border-b-2 text-sm font-medium transition-all relative"
                            :class="activeTab === 'logbook' ? 'border-[#1B2A52] text-[#1B2A52]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300'">
                        Logbook Kegiatan
                        @if($pendingLogbook > 0)
                            <span class="ml-1.5 bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded-full align-middle">{{ $pendingLogbook }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'absensi'"
                            class="pb-3 border-b-2 text-sm font-medium transition-all"
                            :class="activeTab === 'absensi' ? 'border-[#1B2A52] text-[#1B2A52]' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300'">
                        Riwayat Kehadiran
                    </button>
                </div>

            </div>
        </div>

        {{-- KONTEN TAB --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

            <div class="bg-white rounded-2xl mt-10 shadow-sm border border-gray-200 overflow-hidden min-h-[500px]">

                {{-- LOGBOOK --}}
                <div x-show="activeTab === 'logbook'" x-transition.opacity>

                    {{-- HEADER TAB --}}
                    <div class="p-6 border-b border-gray-100 bg-white sticky top-0 z-10">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Aktivitas Harian</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Tinjau dan validasi kegiatan mahasiswa.</p>
                            </div>

                            <div class="flex gap-2">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200">
                                Total: {{ $registration->logbooks->count() }}
                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 min-h-[400px]">

                        {{-- PERLU REVIEW (PENDING) --}}
                        @php
                            $pendingLogs = $registration->logbooks->where('status', 'pending');
                            $historyLogs = $registration->logbooks->where('status', '!=', 'pending');
                        @endphp

                        @if($pendingLogs->count() > 0)
                            <div class="mb-8">
                                <h4 class="text-sm font-bold text-yellow-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                    Menunggu Persetujuan ({{ $pendingLogs->count() }})
                                </h4>

                                <div class="space-y-4">
                                    @foreach($pendingLogs as $log)
                                        <div class="bg-white rounded-xl p-5 border border-yellow-200 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>

                                            <div class="flex flex-col md:flex-row gap-5 pl-2">

                                                {{-- Waktu --}}
                                                <div class="md:w-40 flex-shrink-0">
                                                    <div class="text-sm font-bold text-gray-900">
                                                        {{ \Carbon\Carbon::parse($log->date)->translatedFormat('l, d M Y') }}
                                                    </div>
                                                    <div class="mt-1 inline-flex items-center text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                                        {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($log->end_time)->format('H:i') }}
                                                    </div>
                                                </div>

                                                {{-- Konten --}}
                                                <div class="flex-1">
                                                    <h4 class="text-base font-bold text-gray-900 mb-1">{{ $log->activity }}</h4>
                                                    <p class="text-sm text-gray-600 leading-relaxed">
                                                        {{ $log->description ?: 'Tidak ada deskripsi detail.' }}
                                                    </p>
                                                </div>

                                                {{-- Aksi (Approve/Reject) --}}
                                                <div class="flex flex-row md:flex-col gap-2 md:w-32 flex-shrink-0 mt-3 md:mt-0 justify-end">
                                                    <form action="{{ route('pembimbing.logbook.approve', $log->id) }}" method="POST" class="w-full">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm transform active:scale-95">
                                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                            Setuju
                                                        </button>
                                                    </form>

                                                    <button @click="modalReject = true; selectedLogbookId = {{ $log->id }}; rejectUrl = '{{ route('pembimbing.logbook.reject', $log->id) }}'"
                                                            class="w-full flex items-center justify-center px-3 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-200 text-xs font-bold rounded-lg transition-all shadow-sm">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        Tolak
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- RIWAYAT (HISTORY) Absensi --}}
                        @if($historyLogs->count() > 0)
                            <div>
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-t border-gray-200 pt-6">
                                    Riwayat Kegiatan
                                </h4>

                                <div class="space-y-3">
                                    @foreach($historyLogs as $log)
                                        <div class="bg-white rounded-xl p-5 border border-gray-200 hover:border-gray-300 transition-all group opacity-90 hover:opacity-100">
                                            <div class="flex flex-col md:flex-row gap-5">

                                                {{-- Waktu --}}
                                                <div class="md:w-40 flex-shrink-0">
                                                    <div class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">
                                                        {{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}
                                                    </div>
                                                    <div class="text-xs text-gray-400 mt-0.5">
                                                        {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($log->end_time)->format('H:i') }}
                                                    </div>
                                                </div>

                                                {{-- Konten --}}
                                                <div class="flex-1 border-l border-gray-100 pl-5">
                                                    <h4 class="text-sm font-bold text-gray-800 mb-1">{{ $log->activity }}</h4>

                                                    @if($log->status == 'rejected')
                                                        <div class="mt-2 bg-red-50 border border-red-100 text-red-700 px-3 py-2 rounded-lg text-xs">
                                                            <strong>Ditolak:</strong> "{{ $log->feedback }}"
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-500 line-clamp-1 group-hover:line-clamp-none transition-all">
                                                            {{ $log->description ?: '-' }}
                                                        </p>
                                                    @endif
                                                </div>

                                                {{-- Status Badge --}}
                                                <div class="flex items-start md:w-24 justify-end flex-shrink-0">
                                                    @if($log->status == 'approved')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100">
                                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        Disetujui
                                                    </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-100">
                                                        Ditolak
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- EMPTY STATE --}}
                        @if($registration->logbooks->isEmpty())
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Belum ada logbook</h3>
                                <p class="text-sm text-gray-500 mt-1">Mahasiswa belum mencatat aktivitas apapun.</p>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- ABSENSI --}}
                <div x-show="activeTab === 'absensi'" x-transition.opacity style="display: none;">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-800">Rekap Kehadiran</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-40">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-32">Jam Masuk</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase w-32">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Keterangan / Aktivitas</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase w-32">Bukti</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($registration->attendances as $att)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                    {{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $att->status == 'present' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $att->status == 'sick' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $att->status == 'permission' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $att->notes ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if($att->proof_file)
                                        <a href="{{ Storage::url($att->proof_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs underline">Lihat Foto</a>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-12 text-center text-gray-500">Belum ada data absensi.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- LAPORAN & NILAI --}}
                <div x-show="activeTab === 'laporan'" x-transition.opacity style="display: none;">
                    <div class="p-8 bg-gray-50/30">
                        <div class="max-w-2xl mx-auto space-y-8">

                            {{-- 1. Card Download Laporan --}}
                            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Laporan Akhir</h3>
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full
                                    {{ $registration->report_status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $registration->report_status == 'submitted' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $registration->report_status ? ucfirst($registration->report_status) : 'Belum Upload' }}
                                </span>
                                </div>

                                @if($registration->report_file && $registration->report_status == 'submitted')
                                    {{-- File Ada & Perlu Review --}}
                                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                            <div>
                                                <p class="text-sm font-bold text-blue-900">Laporan_Akhir.pdf</p>
                                                <p class="text-xs text-blue-600">Diunggah {{ $registration->updated_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($registration->report_file) }}" target="_blank" class="px-4 py-2 bg-white text-blue-700 text-sm font-bold rounded shadow-sm border border-blue-200 hover:bg-blue-50">
                                            Periksa
                                        </a>
                                    </div>

                                    {{-- Tombol Aksi Laporan --}}
                                    <div class="mt-6 flex gap-3 justify-end">
                                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-50">Minta Revisi</button>
                                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 shadow-sm">Setujui Laporan</button>
                                    </div>

                                @elseif($registration->report_status == 'approved')
                                    {{-- File Approved --}}
                                    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-100 rounded-lg text-green-800">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <div>
                                            <p class="font-bold text-sm">Laporan telah disetujui.</p>
                                            <a href="{{ Storage::url($registration->report_file) }}" target="_blank" class="text-xs underline hover:text-green-900">Lihat File Final</a>
                                        </div>
                                    </div>

                                @else
                                    <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-lg">
                                        <p class="text-gray-400 text-sm">Mahasiswa belum mengupload laporan akhir.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- 2. Form Penilaian (Tampil jika laporan approved) --}}
                            @if($registration->report_status == 'approved')
                                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-50 rounded-full -mr-10 -mt-10 z-0"></div>

                                    <div class="relative z-10">
                                        <div class="mb-6 border-b border-gray-100 pb-4">
                                            <h3 class="text-lg font-bold text-gray-900">Input Nilai Magang</h3>
                                            <p class="text-sm text-gray-500">Nilai ini akan digunakan untuk sertifikat.</p>
                                        </div>

                                        <form action="#" method="POST"> {{-- Nanti buat route nilai --}}
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kedisiplinan (0-100)</label>
                                                    <input type="number" name="score_discipline" class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kualitas Kerja (0-100)</label>
                                                    <input type="number" name="score_technical" class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Komunikasi</label>
                                                    <input type="number" name="score_softskill" class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Inisiatif</label>
                                                    <input type="number" name="score_initiative" class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                                </div>
                                            </div>

                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Khusus (Opsional)</label>
                                                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52]" placeholder="Kesan & pesan untuk mahasiswa..."></textarea>
                                            </div>

                                            <div class="pt-2 text-right">
                                                <button type="submit" class="px-6 py-3 bg-[#1B2A52] text-white font-bold rounded-xl hover:bg-blue-900 shadow-lg transition-transform transform hover:scale-105">
                                                    Simpan Nilai & Selesaikan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- MODAL REJECT LOGBOOK --}}
        <div x-show="modalReject" style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalReject = false" class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
                <form :action="rejectUrl" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tolak Logbook</h3>
                        <p class="text-sm text-gray-500 mb-4">Berikan alasan revisi untuk mahasiswa.</p>

                        <textarea name="feedback" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 text-sm p-3" placeholder="Contoh: Deskripsi kurang detail..." required></textarea>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" @click="modalReject = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-sm">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
