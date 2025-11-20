@extends('layouts.app')

@section('content')
    <div class="min-h-screen">

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

                {{-- Tombol Download --}}
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

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- CARD 1: INFORMASI PENEMPATAN --}}
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

                {{-- CARD 2: PROGRESS KEHADIRAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 mb-6">Progres Kehadiran</h3>

                        <div class="flex items-end justify-between mb-2">
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-bold text-[#1B2A52]">{{ $daysPassed }}</span>
                                <span class="text-gray-500 font-medium">Hari</span>
                            </div>
                            <span class="text-sm text-gray-400 mb-1.5">
                            Target: {{ $registration->start_date ? \Carbon\Carbon::parse($registration->start_date)->diffInDays($registration->end_date) + 1 : 0 }} Hari
                        </span>
                        </div>

                        {{-- Progress Bar yang lebih tebal --}}
                        <div class="w-full bg-gray-100 rounded-full h-4 mb-4 overflow-hidden">
                            <div class="bg-[#1B2A52] h-full rounded-full transition-all duration-1000 ease-out relative"
                                 style="width: {{ $progressPercentage }}%">
                                {{-- Efek Shine --}}
                                <div class="absolute inset-0 bg-white/20 w-full h-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-sm border-t border-gray-50 pt-4 mt-2">
                        <span class="font-medium text-[#1B2A52]">{{ $progressPercentage }}% Selesai</span>
                        {{-- FIX: Gunakan round() untuk menghilangkan koma --}}
                        <span class="text-gray-500">{{ round($daysRemaining) }} Hari Tersisa</span>
                    </div>
                </div>

                {{-- CARD 3: TIMELINE LAPORAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-full">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-base font-semibold text-gray-800">Progres Laporan</h3>
                        <a href="" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                            Lihat Detail
                        </a>
                    </div>

                    <div class="relative border-l-2 border-gray-100 ml-3 space-y-0">

                        @foreach($reportStages as $stage)
                            <div class="relative pl-6 pb-6 last:pb-0">

                                @if($stage->status == 'approved')
                                    {{-- STATUS: SELESAI --}}
                                    <div class="absolute -left-[9px] top-0 w-5 h-5 rounded-full bg-green-100 border-2 border-green-500 flex items-center justify-center z-10">
                                        <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-bold text-gray-800">{{ $stage->stage_label }}</h4>
                                        <span class="text-[10px] font-semibold text-green-600 bg-green-50 w-max px-1.5 py-0.5 rounded mt-1">Disetujui</span>
                                    </div>

                                @elseif($stage->status == 'revision')
                                    {{-- STATUS: REVISI --}}
                                    <div class="absolute -left-[9px] top-0 w-5 h-5 rounded-full bg-orange-100 border-2 border-orange-500 ring-4 ring-orange-50 flex items-center justify-center z-10">
                                        <span class="text-orange-600 text-xs font-bold">!</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-bold text-gray-800">{{ $stage->stage_label }}</h4>
                                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 w-max px-1.5 py-0.5 rounded mt-1">Perlu Revisi</span>
                                        {{-- Tampilkan Feedback Singkat (Opsional) --}}
                                        @if($stage->feedback)
                                            <p class="text-[10px] text-gray-500 mt-1 italic line-clamp-2">"{{ $stage->feedback }}"</p>
                                        @endif
                                    </div>

                                @elseif($stage->status == 'submitted')
                                    {{-- STATUS: MENUNGGU REVIEW --}}
                                    <div class="absolute -left-[9px] top-0 w-5 h-5 rounded-full bg-blue-100 border-2 border-blue-500 ring-4 ring-blue-50 flex items-center justify-center z-10">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-bold text-gray-800">{{ $stage->stage_label }}</h4>
                                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 w-max px-1.5 py-0.5 rounded mt-1">Menunggu Review</span>
                                    </div>

                                @elseif($stage->status == 'ongoing')
                                    {{-- STATUS: AKTIF (SILAKAN UPLOAD) --}}
                                    <div class="absolute -left-[9px] top-0 w-5 h-5 rounded-full bg-white border-2 border-indigo-500 ring-4 ring-indigo-50 z-10"></div>
                                    <div class="flex flex-col">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $stage->stage_label }}</h4>
                                        <a href="" class="text-[10px] text-indigo-600 hover:underline mt-1">Upload Dokumen &rarr;</a>
                                    </div>

                                @else
                                    {{-- STATUS: LOCKED --}}
                                    <div class="absolute -left-[7px] top-1 w-3.5 h-3.5 rounded-full bg-gray-200 border-2 border-white z-10"></div>
                                    <div class="flex flex-col opacity-50">
                                        <h4 class="text-sm font-medium text-gray-500">{{ $stage->stage_label }}</h4>
                                    </div>
                                @endif

                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            {{-- 3. LOGBOOK SECTION --}}
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

                    <a href="" class="inline-flex items-center px-4 py-2 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Isi Logbook
                    </a>
                </div>

                <div class="space-y-3">
                    {{-- Item Logbook --}}
                    <div class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="bg-white text-gray-800 font-bold px-3 py-2 rounded-lg shadow-sm border border-gray-100 text-sm min-w-[80px] text-center">
                                08:00
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-semibold text-sm group-hover:text-indigo-700 transition-colors">Briefing Pagi dengan Mentor</h4>
                                <p class="text-gray-500 text-xs mt-1 line-clamp-1">Diskusi mengenai task harian dan kendala kemarin.</p>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-0 flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end">
                            <div class="text-xs text-gray-400 hidden sm:block">1 jam</div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Disetujui
                        </span>
                        </div>
                    </div>

                    {{-- Jika Kosong (Uncomment jika backend logbook sudah siap) --}}
                    {{--
                    <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-xl">
                        <p class="text-gray-400 text-sm">Belum ada kegiatan yang dicatat hari ini.</p>
                    </div>
                    --}}
                </div>
            </div>

        </div>
    </div>
@endsection
