@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-16">

        {{-- Tombol Kembali (Rute Kadiv) --}}
        <div class="mb-4">
            <a href="{{ route('kadiv.pengajuan.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Detail Pengajuan Magang</h1>
            <p class="mt-1 text-lg text-gray-600">Review detail mahasiswa dan tentukan pembimbing.</p>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">

            {{-- Data Mahasiswa (Sama) --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Data Mahasiswa</h2>
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->student->name ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">NIM</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->student->nim ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Universitas</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->student->university ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->student->study_program ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <hr class="border-gray-200">

            {{-- Informasi Magang (Sama) --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Informasi Magang</h2>
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Divisi yang Dituju</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">Aplikasi Informatika</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->start_date ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $registration->end_date ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Durasi Magang</dt>
                        @php
                            $durasi = '-';
                            if ($registration->start_date && $registration->end_date) {
                                $durasi = \Carbon\Carbon::parse($registration->start_date)
                                            ->diffInDays(\Carbon\Carbon::parse($registration->end_date)) + 1 . ' hari';
                            }
                        @endphp
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $durasi }}</dd>
                    </div>
                </dl>
            </div>

            <hr class="border-gray-200">

            {{-- Dokumen Pendukung (Sama) --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Dokumen Pendukung</h2>
                <div class="divide-y divide-gray-200 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between p-4">
                        <p class="font-medium text-gray-700">Surat Pengantar</p>
                        <a href="{{ Storage::url($registration->internship_letter) }}" target="_blank" class="text-blue-600 font-medium hover:underline text-sm">Lihat Dokumen</a>
                    </div>
                    <div class="flex items-center justify-between p-4">
                        <p class="font-medium text-gray-700">Surat Rekomendasi Kesbangpol</p>
                        <a href="{{ Storage::url($registration->kesbangpol_letter) }}" target="_blank" class="text-blue-600 font-medium hover:underline text-sm">Lihat Dokumen</a>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Status & Tindakan KADIV --}}
            <div x-data="{ modalApprove: false, modalReject: false }" class="px-6 py-8 bg-gray-50/75">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-l-4 border-blue-600 pl-4">Status Pengajuan</h2>
                    @if($registration->application_status === 'approved')
                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            Disetujui
                        </span>
                        <p class="mt-3 text-gray-600 text-sm pl-1">
                            <span class="font-medium text-gray-700">Pembimbing:</span> {{ $registration->mentor->name ?? 'Belum ditentukan' }}
                        </p>
                    @elseif($registration->application_status === 'rejected')
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                            Ditolak
                        </span>
                        <p class="mt-3 text-gray-600 text-sm pl-1">
                            <span class="font-medium text-gray-700">Alasan:</span> {{ $registration->rejection_note ?? '-' }}
                        </p>
                    @elseif($registration->application_status === 'waiting')
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full animate-pulse">
                            Menunggu Keputusan Anda
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                            Menunggu Admin
                        </span>
                    @endif
                </div>

                @if($registration->application_status === 'waiting')
                    <hr class="border-gray-300 border-dashed my-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Keputusan</h2>
                        <div class="flex flex-wrap gap-3">
                            {{-- Tombol TERIMA (Memicu Modal Pilih Mentor) --}}
                            <button @click="modalApprove = true" type="button" class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Terima & Pilih Pembimbing
                            </button>

                            {{-- Tombol TOLAK --}}
                            <button @click="modalReject = true" type="button" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('kadiv.pengajuan.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Kembali ke Daftar
                    </a>
                </div>

                <div x-show="modalApprove" style="display: none;"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                     x-transition>

                    <div @click.away="modalApprove = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                        <form action="{{ route('kadiv.pengajuan.approve', $registration->id) }}" method="POST">
                            @csrf

                            <div class="p-6 space-y-4">
                                <div class="flex items-center gap-3 mb-2 p-3 bg-green-50 rounded-lg border border-green-100">
                                    <div class="p-2 bg-white rounded-full text-green-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Terima Pengajuan</h3>
                                        <p class="text-xs text-gray-500">Mahasiswa akan resmi diterima di divisi Anda.</p>
                                    </div>
                                </div>

                                <p class="text-gray-600 text-sm">Silakan tunjuk satu staf Anda untuk menjadi pembimbing lapangan bagi mahasiswa ini.</p>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pembimbing</label>
                                    <select name="mentor_id" class="w-full p-3 border rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500" required>
                                        <option value="">-- Pilih Salah Satu --</option>
                                        @foreach($mentors as $mentor)
                                            <option value="{{ $mentor->id }}">
                                                {{ $mentor->name }} ({{ $mentor->position }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mentor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button @click="modalApprove = false" type="button" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-sm">
                                    Simpan & Terima
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="modalReject" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75" x-transition>
                    <div @click.away="modalReject = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                        <form action="{{ route('kadiv.pengajuan.reject', $registration->id) }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Tolak Pengajuan</h3>
                                <p class="text-gray-600 mb-4">Tuliskan alasan penolakan.</p>
                                <div>
                                    <label for="rejection_note" class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                                    <textarea id="rejection_note" name="rejection_note" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Kuota penuh." required></textarea>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button @click="modalReject = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
