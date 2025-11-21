@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50" x-data="{ modalRevisi: false }">

        {{-- 1. HEADER --}}
        <div class=" border-b border-gray-200 ">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

                {{-- Container Header --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

                    {{-- Navigasi & Identitas --}}
                    <div class="flex items-center gap-3 w-full sm:w-auto overflow-hidden">
                        <a href="{{ route('pembimbing.laporan.index') }}" class="p-2 rounded-full hover:bg-gray-100 transition text-gray-400 hover:text-gray-600 flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7 7-7" /></svg>
                        </a>

                        <div class="min-w-0 flex-1">
                            <h1 class="text-base sm:text-lg font-bold text-gray-900 leading-tight truncate pr-2">
                                {{ $registration->student->name }}
                            </h1>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 flex-shrink-0">{{ $registration->student->nim }}</span>
                                <span class="hidden xs:inline">•</span>
                                <span class="truncate max-w-[300px] sm:max-w-xs" title="{{ $registration->student->university }}">
                                {{ $registration->student->university }}
                            </span>
                            </div>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="flex-shrink-0 ml-11 sm:ml-0 self-start sm:self-center">
                        @if(!$registration->report_status)
                            <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full border border-gray-200 whitespace-nowrap">Belum Upload</span>
                        @elseif($registration->report_status == 'submitted')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-200 shadow-sm whitespace-nowrap">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            Menunggu Review
                        </span>
                        @elseif($registration->report_status == 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200 whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Disetujui
                        </span>
                        @elseif($registration->report_status == 'revision')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 text-xs font-bold rounded-full border border-red-200 whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Revisi
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIM KONTEN  --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">

            {{-- CARD DOKUMEN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base">Dokumen Laporan Akhir</h3>
                        <p class="text-xs text-gray-500 mt-0.5">File PDF yang diunggah mahasiswa.</p>
                    </div>
                    @if($registration->report_file)
                        <span class="text-xs text-gray-400 font-medium bg-white px-2 py-1 rounded border border-gray-200 whitespace-nowrap">
                        {{ $registration->updated_at->diffForHumans() }}
                    </span>
                    @endif
                </div>

                <div class="p-4 sm:p-8">
                    @if($registration->report_file)
                        {{-- File Info Card --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-5 bg-white border-2 border-indigo-50 rounded-xl hover:border-indigo-100 transition-colors group gap-4">

                            {{-- Icon & Nama File --}}
                            <div class="flex items-center gap-4 w-full sm:w-auto overflow-hidden">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-red-50 text-red-500 rounded-xl flex items-center justify-center border border-red-100 shadow-sm flex-shrink-0">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm sm:text-base font-bold text-gray-900 group-hover:text-[#1B2A52] transition-colors truncate pr-2">Laporan_Akhir_Magang.pdf</p>
                                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ number_format(Illuminate\Support\Facades\Storage::disk('public')->size($registration->report_file) / 1024, 0) }} KB</p>
                                </div>
                            </div>

                            {{-- Tombol Buka File --}}
                            <a href="{{ Storage::url($registration->report_file) }}" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-[#1B2A52] text-white text-sm font-bold rounded-lg hover:bg-blue-900 transition shadow-md transform active:scale-95 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Buka File
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 sm:py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <p class="text-gray-500 font-medium text-sm">Mahasiswa belum mengupload laporan.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ALERT REVISI --}}
            @if($registration->report_status == 'revision')
                <div class="p-4 sm:p-5 bg-red-50 border border-red-200 rounded-xl text-red-800 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide mb-1">Revisi Terakhir Anda:</p>
                            <p class="italic text-sm bg-white/50 p-2 rounded border border-red-100 break-words">"{{ $registration->report_feedback }}"</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- KEPUTUSAN VALIDASI --}}
            @if($registration->report_status == 'submitted')
                <div class="bg-white p-4 sm:p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm sm:text-base">Keputusan Validasi</h3>

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        {{-- Tombol Tolak --}}
                        <button @click="modalRevisi = true"
                                class="w-full sm:flex-1 py-3 px-4 border-2 border-red-100 bg-white text-red-600 font-bold rounded-xl hover:bg-red-50 hover:border-red-200 transition-all flex items-center justify-center gap-2 group active:scale-95">
                            <svg class="w-5 h-5 text-red-400 group-hover:text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Tolak & Minta Revisi
                        </button>

                        {{-- Tombol Setujui --}}
                        <form action="{{ route('pembimbing.laporan.approve', $registration->id) }}" method="POST" class="w-full sm:flex-1">
                            @csrf @method('PUT')
                            <button type="submit" class="w-full py-3 px-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-lg shadow-green-100 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Validasi Aman (ACC)
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 text-center leading-relaxed">
                        Pastikan Anda sudah membaca isi dokumen dan memastikan tidak ada data sensitif perusahaan.
                    </p>
                </div>
            @endif

        </div>

        {{-- MODAL REVISI --}}
        <div x-show="modalRevisi" style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4 sm:p-0"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div @click.away="modalRevisi = false" class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                <form action="{{ route('pembimbing.laporan.revise', $registration->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="p-5 sm:p-6 border-b border-gray-100 bg-red-50 flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-full text-red-600 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900">Kembalikan Laporan</h3>
                            <p class="text-sm text-red-700 leading-tight">Berikan catatan perbaikan.</p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi</label>
                        <textarea name="feedback" rows="4" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm p-3" placeholder="Contoh: Hapus data sensitif pada halaman 12..." required></textarea>
                    </div>

                    <div class="px-5 sm:px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" @click="modalRevisi = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-sm transition-colors">
                            Kirim Revisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
