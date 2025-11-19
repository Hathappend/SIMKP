@extends('layouts.app')

@section('content')
    {{-- Gunakan max-w-5xl dan mx-auto untuk membatasi lebar konten di layar besar --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-16">

        {{--
            INI TAMBAHAN: Tombol Kembali di atas
            Pola "breadcrumb" sederhana ini sangat profesional.
        --}}
        <div class="mb-4">
            <a href="{{ route('admin.registration.create') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-3xl font-semibold text-gray-900">Detail Pengajuan Magang</h1>
            <p class="mt-1 text-lg text-gray-600">Review dan kelola detail pengajuan dari mahasiswa.</p>
        </div>

        <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">

            {{-- Data Mahasiswa --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Data Mahasiswa</h2>
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->student->name ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">NIM</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->student->nim ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Universitas</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->student->university ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->student->study_program ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <hr class="border-gray-200">

            {{-- Informasi Magang --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Informasi Magang</h2>
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Divisi yang Dituju</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">Aplikasi Informatika</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->start_date ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">{{ $application->end_date ?? '-' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Durasi Magang</dt>
                        @php
                            $durasi = '-';
                            if ($application->start_date && $application->end_date) {
                                $durasi = \Carbon\Carbon::parse($application->start_date)
                                            ->diffInDays(\Carbon\Carbon::parse($application->end_date)) + 1 . ' hari';
                            }
                        @endphp

                        <dd class="mt-1 text-base font-medium text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $durasi }}
                        </dd>
                    </div>
                </dl>
            </div>

            <hr class="border-gray-200">

            {{-- Dokumen Pendukung --}}
            <div class="px-6 py-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 border-l-4 border-blue-600 pl-4">Dokumen Pendukung</h2>
                <div class="divide-y divide-gray-200 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between p-4">
                        <p class="font-medium text-gray-700">Surat Pengantar</p>
                        {{-- FIX KONSISTENSI: Asumsi kedua file di-upload ke storage --}}
                        <a href="{{ Storage::url($application->internship_letter) }}" target="_blank" class="text-blue-600 font-medium hover:underline text-sm">
                            Lihat Dokumen
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-4">
                        <p class="font-medium text-gray-700">Surat Rekomendasi Kesbangpol</p>
                        <a href="{{ Storage::url($application->kesbangpol_letter) }}" target="_blank" class="text-blue-600 font-medium hover:underline text-sm">
                            Lihat Dokumen
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Status & Tindakan --}}
            <div x-data="{ modalTolak: false, modalPerbaikan: false, modalSetuju: false, modalArsip: false }" class="px-6 py-8 bg-gray-50/75">

                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-l-4 border-blue-600 pl-4">Status Pengajuan & Status Surat</h2>
                    {{-- Status Pengajuan --}}
                    @if($application->application_status === 'approved')
                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Disetujui
                        </span>
                    @elseif($application->application_status === 'rejected')
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Ditolak
                        </span>
                        <p class="mt-3 mb-3 text-gray-600 text-sm pl-1">
                            <span class="font-medium text-gray-700">Alasan:</span> {{ $application->rejection_note ?? '-' }}
                        </p>
                    @elseif($application->application_status === 'waiting')
                        <span class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.33-.266 2.98-1.742 2.98H4.42c-1.476 0-2.492-1.65-1.742-2.98l5.58-9.92zM10 13a1 1 0 100-2 1 1 0 000 2zm-1-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                            Sedang ditinjau
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zM10 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                            Menunggu
                        </span>
                    @endif

                    {{-- Status Surat --}}
                    @if($application->letter_status === 'completed')
                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Disetujui
                        </span>
                    @elseif($application->letter_status === 'in progress')
                        <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6zM10 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                            Belum Dibuat
                        </span>
                    @endif
                </div>

                @if($application->application_status === 'pending')
                    <hr class="border-gray-300 border-dashed my-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tindakan</h2>
                        <div class="flex flex-wrap gap-3">

                            <button @click="modalSetuju = true" type="button" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Setujui
                            </button>

                            <button @click="modalTolak = true" type="button" class="inline-flex items-center justify-center px-5 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                Tolak
                            </button>
                        </div>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('admin.registration.create') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Kembali ke Daftar
                        </a>

                        <button @click="modalArsip = true" type="button" class="inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.042-.53-.082-.79-.118l.79-.118A48.108 48.108 0 0112 4.5c4.756 0 9.242 1.256 12.834 3.39m-12.834 3.39L10.5 3.19c.09-.3.028-.617-.18-.842a1.12 1.12 0 00-1.424.062L7.25 4.5l-2.036-1.554a1.125 1.125 0 00-1.424-.062 1.125 1.125 0 00-.18.842l.79 4.39z" />
                            </svg>
                            Arsipkan Data
                        </button>
                    </div>
                </div>

                <div
                    x-show="modalArsip"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                    style="display: none;"
                >
                    <div @click.away="modalArsip = false"
                         x-show="modalArsip"
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                         class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden"
                    >
                        <form action="{{ route('admin.pengajuan.archive', $application->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 ...">
                                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4 text-left">
                                        <h3 class="text-xl font-semibold text-gray-900">Arsipkan Pengajuan?</h3>
                                        <p class="text-gray-600 mt-2">
                                            Anda yakin ingin mengarsipkan data ini? Data akan disembunyikan dari daftar utama tapi tidak dihapus permanen.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                                <button type="submit" class="inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                                    Ya, Arsipkan
                                </button>
                                <button @click="modalArsip = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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

        </div>
    </div>
@endsection
