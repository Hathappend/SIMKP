@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100/50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

            {{-- HEADER --}}
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-tight">Upload Laporan Akhir</h1>
                <p class="text-sm sm:text-base text-gray-500 mt-2 sm:mt-1">
                    Upload laporan lengkap (Bab 1 s.d Penutup) untuk divalidasi oleh Pembimbing.
                </p>
            </div>

            {{-- ALERT SUKSES --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3">
                    <div class="flex-shrink-0 mt-0.5 sm:mt-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <span class="text-sm sm:text-base font-medium leading-snug">{{ session('success') }}</span>
                </div>
            @endif

            {{-- MAIN CARD --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- STATUS BANNER --}}
                <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-y-3 sm:gap-y-0">

                        <div class="w-full sm:w-auto">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Status Laporan</p>

                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 shadow-sm">
                                @if(!$registration->report_status)
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <div class="w-2.5 h-2.5 rounded-full bg-gray-300"></div>
                                        <span class="font-semibold text-sm">Belum Diupload</span>
                                    </div>
                                @elseif($registration->report_status == 'submitted')
                                    <div class="flex items-center gap-2 text-blue-600">
                                    <span class="relative flex h-2.5 w-2.5">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                                    </span>
                                        <span class="font-semibold text-sm">Sedang Diperiksa</span>
                                    </div>
                                @elseif($registration->report_status == 'revision')
                                    <div class="flex items-center gap-2 text-red-600">
                                        <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                                        <span class="font-semibold text-sm">Perlu Revisi</span>
                                    </div>
                                @elseif($registration->report_status == 'approved')
                                    <div class="flex items-center gap-2 text-green-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="font-semibold text-sm">Disetujui (Final)</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tanggal Update --}}
                        @if($registration->report_file)
                            <div class="hidden sm:block text-right mt-2 sm:mt-0">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Terakhir Diupdate</p>
                                <p class="text-sm font-medium text-gray-700">{{ $registration->updated_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ALERT --}}
                @if($registration->report_status == 'revision')
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 sm:p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4 w-full">
                                <h3 class="text-base sm:text-lg font-bold text-red-800 leading-tight">Laporan Ditolak</h3>
                                <p class="text-xs sm:text-sm text-red-700 mt-1 font-semibold">Catatan Pembimbing:</p>
                                <div class="mt-2 p-3 bg-white rounded-lg border border-red-200 text-red-800 text-sm font-medium italic shadow-sm">
                                    "{{ $registration->report_feedback }}"
                                </div>
                                <p class="mt-2 sm:mt-3 text-xs text-red-600 leading-snug">Silakan perbaiki laporan Anda sesuai catatan di atas dan upload ulang.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Konten Body --}}
                <div class="p-5 sm:p-8">

                    {{-- FORM UPLOAD --}}
                    @if($registration->report_status != 'approved')

                        {{-- Info Keamanan Data --}}
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <div class="text-xs sm:text-sm text-yellow-800 leading-snug">
                                <strong class="block mb-1 font-bold text-yellow-900">Kebijakan Keamanan Data</strong>
                                <span>Pastikan laporan Anda <u>TIDAK MEMUAT</u> data sensitif seperti:</span>
                                <ul class="list-disc list-inside mt-1.5 text-yellow-800 space-y-0.5 font-medium">
                                    <li>Credential (Username/Password).</li>
                                    <li>Data Pribadi (NIK, KK, dll).</li>
                                    <li>Topologi/IP Address Server.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- FORM UPLOAD --}}
                        <form action="{{ route('mahasiswa.laporan.update') }}" method="POST" enctype="multipart/form-data"
                              x-data="{
                              agreement: false,
                              fileName: null,
                              fileSize: null
                          }">
                            @csrf

                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">File Laporan (PDF)</label>

                                <div class="relative w-full h-40 sm:h-48 rounded-xl border-2 border-dashed transition-all duration-200 group overflow-hidden flex items-center justify-center bg-gray-50"
                                     :class="fileName ? 'border-[#1B2A52] bg-blue-50/50' : 'border-gray-300 hover:bg-gray-100 hover:border-indigo-400'">

                                    {{-- INPUT FILE --}}
                                    <input type="file" name="file" id="file-upload" accept=".pdf"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                           @change="
                                           if ($event.target.files.length > 0) {
                                               fileName = $event.target.files[0].name;
                                               let size = $event.target.files[0].size / 1024 / 1024;
                                               fileSize = size.toFixed(2) + ' MB';
                                           }
                                       ">

                                    {{-- BELUM ADA FILE --}}
                                    <div x-show="!fileName" class="text-center pointer-events-none p-4 sm:p-6 transition-opacity duration-300">
                                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 group-hover:text-indigo-500 transition-colors mb-2 sm:mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-700">
                                            <span class="hidden sm:inline">Klik di sini atau drag file</span>
                                            <span class="sm:hidden">Tap untuk pilih file</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PDF Maksimal 10MB</p>
                                    </div>

                                    {{-- FILE SUDAH DIPILIH --}}
                                    <div x-show="fileName" class="text-center pointer-events-none p-4 sm:p-6 transition-opacity duration-300 z-0" style="display: none;"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100">

                                        <div class="mx-auto w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-lg border border-blue-200 shadow-sm flex items-center justify-center mb-2 sm:mb-3">
                                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                        </div>

                                        <div class="px-2">
                                            <p class="text-sm sm:text-base font-bold text-[#1B2A52] break-all leading-tight line-clamp-2" x-text="fileName"></p>
                                            <p class="text-xs text-gray-500 mt-1 font-medium" x-text="fileSize"></p>
                                        </div>

                                        <p class="text-xs text-indigo-600 mt-3 font-semibold">Tap lagi untuk ganti</p>
                                    </div>

                                </div>
                                @error('file') <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>

                            {{-- Layout Checkbox --}}
                            <div class="flex items-start mb-6 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input id="agreement" type="checkbox" x-model="agreement" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded cursor-pointer">
                                </div>
                                <div class="ml-3 text-xs sm:text-sm leading-snug">
                                    <label for="agreement" class="font-medium text-gray-700 cursor-pointer select-none">
                                        Saya menyatakan bahwa laporan ini <strong>telah disensor dari data sensitif/rahasia instansi</strong>.
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        :disabled="!agreement || !fileName"
                                        :class="(!agreement || !fileName) ? 'bg-gray-300 cursor-not-allowed text-gray-500' : 'bg-[#1B2A52] hover:bg-blue-900 shadow-md text-white'"
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm sm:text-base font-bold rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1B2A52]">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $registration->report_file ? 'Update Laporan' : 'Kirim Laporan' }}
                                </button>
                            </div>
                        </form>

                    @else
                        {{-- SUDAH APPROVED --}}
                        <div class="text-center py-8 sm:py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-green-100 mb-4">
                                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Disetujui!</h3>
                            <p class="text-sm sm:text-base text-gray-500 mt-2 leading-relaxed px-4">
                                Selamat, laporan akhir Anda telah divalidasi dan dinyatakan aman oleh pembimbing.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- FILE PREVIEW  --}}
                @if($registration->report_file)
                    <div class="bg-gray-50 px-5 py-4 sm:px-8 sm:py-6 border-t border-gray-200">
                        <h4 class="text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide">File Terkirim</h4>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm gap-3 sm:gap-0">

                            <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto overflow-hidden">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-red-50 flex items-center justify-center text-red-500 shrink-0 border border-red-100">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-900 truncate">Laporan_Akhir.pdf</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ number_format(Storage::disk('public')->size($registration->report_file) / 1024, 0) }} KB</p>
                                </div>
                            </div>

                            <a href="{{ Storage::url($registration->report_file) }}" target="_blank" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-bold sm:bg-transparent sm:text-indigo-600 sm:hover:bg-transparent sm:hover:text-indigo-800 sm:p-0 mt-1 sm:mt-0">
                                <span>Lihat File</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
