@extends('layouts.app')

@section('content')
    <div class="min-h-screen " x-data="{ modalApprove: false, modalReject: false }">

        {{-- HEADER --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 ">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

                {{-- Identitas Utama --}}
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Detail Pengajuan</h1>

                        {{-- Status Badge  --}}
                        @if($registration->application_status === 'approved')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Disetujui</span>
                        @elseif($registration->application_status === 'rejected')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Ditolak</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">Menunggu Verifikasi</span>
                        @endif
                    </div>
                    <p class="text-gray-500">ID Registrasi: <span class="font-mono text-gray-700 font-medium">#{{ $registration->id }}</span></p>
                </div>

                <a href="{{ route('kadiv.pengajuan.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#1B2A52]">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" /></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- DATA DETAIL --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- DATA MAHASISWA --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Data Mahasiswa</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nama Lengkap</p>
                                <p class="text-base font-bold text-gray-900">{{ $registration->student->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">NIM</p>
                                <p class="text-base font-medium text-gray-900 font-mono">{{ $registration->student->nim }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Universitas</p>
                                <p class="text-sm font-medium text-gray-900">{{ $registration->student->university }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Program Studi</p>
                                <p class="text-sm font-medium text-gray-900">{{ $registration->student->study_program }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- RENCANA MAGANG --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Rencana Magang</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Divisi Tujuan</p>
                                <div class="flex items-center gap-2">
                                <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                </span>
                                    <p class="text-base font-bold text-gray-900">{{ $registration->division->name }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Tanggal Mulai</p>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($registration->start_date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Tanggal Selesai</p>
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($registration->end_date)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Durasi</p>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ \Carbon\Carbon::parse($registration->start_date)->diffInDays($registration->end_date) + 1 }} Hari
                            </span>
                            </div>
                        </div>
                    </div>

                    {{-- ANGGOTA KELOMPOK (Jika Ada) --}}
                    @php
                        $members = $registration->members->filter(fn($m) => $m->nim !== $registration->student->nim);
                    @endphp
                    @if($members->count() > 0)
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Anggota Tim</h3>
                            </div>
                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($members as $member)
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="w-8 h-8 rounded bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900">{{ $member->name }}</p>
                                            <p class="text-[10px] text-gray-500 font-mono">{{ $member->nim }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- DOKUMEN & KEPUTUSAN --}}
                <div class="space-y-8">

                    {{-- CARD DOKUMEN --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Dokumen Lampiran</h3>
                        </div>
                        <div class="p-4 space-y-3">

                            {{-- File 1 --}}
                            <a href="{{ Storage::url($registration->internship_letter) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0 border border-red-100">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate group-hover:text-indigo-700">Surat Pengantar</p>
                                    <p class="text-[10px] text-gray-500">PDF Document</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>

                            {{-- File 2 --}}
                            <a href="{{ Storage::url($registration->kesbangpol_letter) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all group">
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

                    {{-- CARD KEPUTUSAN (Hanya jika Waiting) --}}
                    @if($registration->application_status === 'waiting')
                        <div class="bg-white rounded-xl shadow-lg border border-indigo-100 p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-100 rounded-bl-full -mr-5 -mt-5 opacity-50"></div>

                            <h3 class="text-base font-bold text-gray-900 mb-2">Keputusan Anda</h3>
                            <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                                Apakah mahasiswa ini diterima magang di divisi Anda? Jika ya, silakan pilih pembimbing.
                            </p>

                            <div class="space-y-3">
                                <button @click="modalApprove = true" class="w-full flex items-center justify-center gap-2 py-3 bg-[#1B2A52] text-white font-bold text-sm rounded-xl hover:bg-blue-900 shadow-md transition-all transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Terima & Pilih Mentor
                                </button>

                                <button @click="modalReject = true" class="w-full flex items-center justify-center gap-2 py-3 bg-white border-2 border-red-100 text-red-600 font-bold text-sm rounded-xl hover:bg-red-50 hover:border-red-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Tolak Pengajuan
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- INFO HASIL (Jika Sudah Selesai/Ditolak) --}}
                    @if($registration->application_status === 'approved')
                        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                            <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-1">Status: Diterima</p>
                            <p class="text-sm text-green-700">Mahasiswa ini sedang menjalani masa magang di divisi Anda.</p>
                            <div class="mt-3 pt-3 border-t border-green-200/60 flex items-center gap-2">
                                <span class="text-xs font-bold text-green-800">Pembimbing:</span>
                                <span class="text-xs bg-white px-2 py-1 rounded border border-green-200 text-green-700">{{ $registration->mentor->name }}</span>
                            </div>
                        </div>
                    @elseif($registration->application_status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                            <p class="text-xs font-bold text-red-800 uppercase tracking-wide mb-1">Status: Ditolak</p>
                            <p class="text-sm text-red-700 italic">"{{ $registration->rejection_note }}"</p>
                        </div>
                    @endif

                </div>

            </div>
        </div>

        <div x-show="modalApprove" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 backdrop-blur-sm" x-transition>
            <div @click.away="modalApprove = false" class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
                <form action="{{ route('kadiv.pengajuan.approve', $registration->id) }}" method="POST">
                    @csrf
                    <div class="p-6 bg-green-50 border-b border-green-100 flex items-center gap-3">
                        <div class="p-2 bg-green-100 rounded-full text-green-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></div>
                        <div><h3 class="text-lg font-bold text-green-900">Terima Pengajuan</h3><p class="text-sm text-green-700">Pilih pembimbing lapangan.</p></div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Mentor</label>
                        {{-- Pastikan variable $mentors dikirim dari Controller --}}
                        <select name="mentor_id" class="w-full border-gray-300 rounded-xl focus:ring-green-500 focus:border-green-500" required>
                            <option value="">-- Pilih Salah Satu --</option>
                            @foreach(\App\Models\Mentor::where('division_id', Auth::user()->division_id)->get() as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->position }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" @click="modalApprove = false" class="px-4 py-2 border rounded-lg text-sm font-bold text-gray-600 hover:bg-white">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700 shadow-sm">Simpan & Terima</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Reject --}}
        <div x-show="modalReject" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 backdrop-blur-sm" x-transition>
            <div @click.away="modalReject = false" class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">
                <form action="{{ route('kadiv.pengajuan.reject', $registration->id) }}" method="POST">
                    @csrf
                    <div class="p-6 bg-red-50 border-b border-red-100 flex items-center gap-3">
                        <div class="p-2 bg-red-100 rounded-full text-red-600"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></div>
                        <div><h3 class="text-lg font-bold text-red-900">Tolak Pengajuan</h3><p class="text-sm text-red-700">Berikan alasan penolakan.</p></div>
                    </div>
                    <div class="p-6">
                        <textarea name="rejection_note" rows="4" class="w-full border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 text-sm p-3" placeholder="Contoh: Kuota penuh..." required></textarea>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" @click="modalReject = false" class="px-4 py-2 border rounded-lg text-sm font-bold text-gray-600 hover:bg-white">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-sm">Kirim Penolakan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
