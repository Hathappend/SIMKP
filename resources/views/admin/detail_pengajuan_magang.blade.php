@extends('layouts.app')
@section('title', "Detail Pengajuan Magang")
@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-20">

        {{-- HEADER & STATUS --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Detail Pengajuan</h1>

                    {{-- Status Badge  --}}
                    @if($application->application_status === 'approved')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                    @elseif($application->application_status === 'rejected')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">Menunggu Verifikasi</span>
                    @endif
                </div>
                <p class="text-gray-500">ID Registrasi: <span class="font-mono text-gray-700 font-medium">#{{ $application->id }}</span></p>
            </div>

            <a href="{{ route('admin.registration.create') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#1B2A52]">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" /></svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">

                {{-- Card Data Mahasiswa --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Data Mahasiswa</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Lengkap</label>
                            <p class="text-base font-bold text-gray-900">{{ $application->student->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">NIM</label>
                            <p class="text-base font-medium text-gray-900 font-mono bg-gray-100 inline-block px-2 rounded">{{ $application->student->nim }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Universitas</label>
                            <p class="text-sm font-medium text-gray-800">{{ $application->student->university }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Program Studi</label>
                            <p class="text-sm font-medium text-gray-800">{{ $application->student->study_program }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Informasi Magang --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Rencana Magang</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Divisi Tujuan</label>
                            <div class="flex items-center gap-2">
                                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </span>
                                <p class="text-base font-bold text-gray-900">{{ $application->division->name }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Mulai</label>
                            <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($application->start_date)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Selesai</label>
                            <p class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($application->end_date)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Durasi</label>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ \Carbon\Carbon::parse($application->start_date)->diffInDays($application->end_date) + 1 }} Hari
                             </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- DOKUMEN & AKSI --}}
            <div class="space-y-8">

                {{-- Card Dokumen --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Lampiran</h3>
                    </div>
                    <div class="p-4 space-y-3">

                        {{-- File Item 1 --}}
                        <a href="{{ Storage::url($application->internship_letter) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0 border border-red-100">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate group-hover:text-indigo-700">Surat Pengantar</p>
                                <p class="text-[10px] text-gray-500">PDF Document</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>

                        {{-- File Item 2 --}}
                        <a href="{{ Storage::url($application->kesbangpol_letter) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0 border border-red-100">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate group-hover:text-indigo-700">Proposal / Kesbangpol</p>
                                <p class="text-[10px] text-gray-500">PDF Document</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </a>

                    </div>
                </div>

                {{-- CARD ACTION --}}
                @if($application->application_status === 'pending')
                    <div class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 relative overflow-hidden" x-data="{ modalTolak: false, modalSetuju: false }">

                        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>

                        <h3 class="text-base font-bold text-gray-900 mb-2">Keputusan Admin</h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
                            Pastikan semua dokumen valid sebelum menyetujui. Pengajuan yang disetujui akan diteruskan ke Kepala Divisi.
                        </p>

                        <div class="space-y-3">
                            {{-- Tombol Setuju --}}
                            <button @click="modalSetuju = true" class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1B2A52] text-white font-bold text-sm rounded-lg hover:bg-blue-900 shadow-md transition-transform transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Verifikasi & Setuju
                            </button>

                            {{-- Tombol Tolak --}}
                            <button @click="modalTolak = true" class="w-full flex items-center justify-center gap-2 py-2.5 bg-white border border-red-200 text-red-600 font-bold text-sm rounded-lg hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak Pengajuan
                            </button>
                        </div>

                        {{-- Modal Setuju --}}
                        <div
                            x-show="modalSetuju"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                            style="display: none;"
                        >
                            <div
                                @click.away="modalSetuju = false"
                                x-show="modalSetuju"
                                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                                class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden"
                            >
                                <div class="p-6">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4 text-left">
                                            <h3 class="text-xl font-semibold text-gray-900">Setujui Pengajuan?</h3>
                                            <p class="text-gray-600 mt-2">
                                                Anda akan meneruskan pengajuan ini ke Kepala Divisi untuk ditinjau lebih lanjut.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                                    <a href="{{ route('admin.pengajuan.forward', $application->id) }}"
                                       class="inline-flex justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                        Ya, Lanjutkan
                                    </a>
                                    <button @click="modalSetuju = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Tolak --}}
                        <div
                            x-show="modalTolak"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                            style="display: none;"
                        >
                            <div
                                @click.away="modalTolak = false"
                                x-show="modalTolak"
                                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                                class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden"
                            >
                                <form action="{{ route('admin.pengajuan.reject', $application->id) }}" method="POST">
                                    @csrf
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Tolak Pengajuan</h3>
                                        <p class="text-gray-600 mb-4">
                                            Tuliskan alasan penolakan. Tindakan ini <strong>final</strong> dan pengajuan akan ditutup.
                                        </p>
                                        <div>
                                            <label for="rejection_note" class="block text-sm font-medium text-gray-700 mb-2">
                                                Alasan Penolakan
                                            </label>
                                            <textarea
                                                id="rejection_note"
                                                name="rejection_note"
                                                rows="4"
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500"
                                                placeholder="Contoh: Kuota untuk divisi Aplikasi Informatika sudah penuh."
                                                required
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                        <button @click="modalTolak = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                                            Kirim Penolakan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                @endif

                {{-- INFO PENOLAKAN (Jika Rejected) --}}
                @if($application->application_status === 'rejected')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <h4 class="text-sm font-bold text-red-800 mb-1">Alasan Penolakan</h4>
                        <p class="text-sm text-red-700 italic">"{{ $application->rejection_note }}"</p>
                    </div>
                @endif

            </div>

        </div>
    </div>

@endsection

