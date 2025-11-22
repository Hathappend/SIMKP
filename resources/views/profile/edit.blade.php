@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30"
         x-data="{
        photoName: null,
        photoPreview: null,

        // Fungsi untuk memicu input file yang tersembunyi
        triggerFileInput() {
            document.getElementById('photo').click();
        },

        // Fungsi untuk menampilkan preview gambar
        updatePhotoPreview() {
            const reader = new FileReader();
            const file = document.getElementById('photo').files[0];

            if (!file) return;

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
     }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Kelola informasi akun dan biodata diri Anda.</p>
            </div>

            {{-- ALERT SUKSES / ERROR --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ========================================== --}}
                {{-- KOLOM KIRI: KARTU IDENTITAS & PASSWORD     --}}
                {{-- ========================================== --}}
                <div class="space-y-6">

                    {{-- 1. KARTU FOTO PROFIL --}}
                    {{-- 1. KARTU FOTO PROFIL --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-[#1B2A52] to-blue-900 z-0"></div>

                        <div class="relative z-10">
                            {{-- Lingkaran Foto --}}
                            <div class="relative inline-block group">

                                {{-- Container Foto --}}
                                <div class="w-32 h-32 rounded-full border-4 border-white shadow-md mx-auto overflow-hidden bg-white relative flex items-center justify-center">

                                    {{--
                                        LOGIKA TAMPILAN (PRIORITAS):
                                        1. Preview JS (Jika user baru pilih file)
                                        2. Gambar Database (Jika user punya avatar)
                                        3. Inisial Nama (Default)
                                    --}}

                                    {{-- A. Layer Preview (Alpine JS) --}}
                                    <template x-if="photoPreview">
                                    <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                          :style="'background-image: url(\'' + photoPreview + '\');'">
                                    </span>
                                    </template>

                                    {{-- B. Layer Database / Inisial (Hanya muncul jika tidak ada preview) --}}
                                    <template x-if="!photoPreview">
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            @if($user->avatar)
                                                <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" alt="Foto Profil">
                                            @else
                                                <span class="text-3xl font-bold text-gray-400 select-none">{{ substr($user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                    </template>

                                </div>

                                {{-- Tombol Kamera (Pemicu) --}}
                                <button type="button" @click="triggerFileInput()"
                                        class="absolute bottom-1 right-1 bg-white text-gray-600 p-2.5 rounded-full shadow-lg border border-gray-200 hover:text-[#1B2A52] hover:bg-gray-50 transition-all transform hover:scale-110 z-20"
                                        title="Ganti Foto">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </button>
                            </div>

                            <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-wide">
                            {{ ucfirst($user->getRoleNames()->first() ?? 'Pengguna') }}
                        </span>

                            {{-- Pesan Notifikasi Preview --}}
                            <div x-show="photoPreview" style="display: none;" class="mt-4 text-xs text-green-600 bg-green-50 py-2 px-3 rounded-lg border border-green-100 animate-pulse font-medium">
                                Foto dipilih. Klik "Simpan Perubahan" untuk menerapkan.
                            </div>
                        </div>
                    </div>

                    {{-- 2. FORM GANTI PASSWORD (FORM TERPISAH) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase mb-4 border-b border-gray-100 pb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Keamanan Akun
                        </h3>

                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Password Baru</label>
                                    <input type="password" name="password" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold text-sm rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
                                Ubah Password
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- KOLOM KANAN: FORM UPDATE UTAMA             --}}
                {{-- ========================================== --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Biodata Diri</h3>
                        </div>

                        <div class="p-6 sm:p-8">
                            {{-- FORM UTAMA (Mencakup Foto & Biodata) --}}
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PATCH')

                                {{--
                                    INPUT FILE HIDDEN (Proxy)
                                    Ini adalah input file yang sebenarnya. Dipicu oleh tombol di kolom kiri.
                                    Diletakkan di sini agar masuk dalam satu form submit.
                                --}}
                                <input type="file" id="photo" name="photo" class="hidden" accept="image/*" @change="updatePhotoPreview()">

                                {{-- A. DATA UMUM --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Login</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                                    </div>
                                </div>

                                {{-- B. KHUSUS MAHASISWA --}}
                                @role('mahasiswa')
                                <div class="mb-6 p-5 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="flex items-center gap-2 mb-4">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wide">Data Akademik (Terkunci)</h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">NIM</label>
                                            <input type="text" value="{{ $user->student->nim ?? '-' }}" class="w-full bg-white border-gray-200 text-gray-500 rounded-lg text-sm cursor-not-allowed font-mono" readonly>
                                            {{-- Hidden input agar validasi unique di controller tidak error --}}
                                            <input type="hidden" name="nim" value="{{ $user->student->nim ?? '' }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Universitas</label>
                                            <input type="text" name="university" value="{{ $user->student->university ?? '-' }}" class="w-full bg-white border-gray-200 text-gray-500 rounded-lg text-sm cursor-not-allowed" readonly>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-gray-500 mb-1">Program Studi</label>
                                            <input type="text" name="study_program" value="{{ $user->student->study_program ?? '-' }}" class="w-full bg-white border-gray-200 text-gray-500 rounded-lg text-sm cursor-not-allowed" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">No. Handphone (WhatsApp)</label>
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->student->phone_number ?? '') }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" placeholder="Contoh: 08123456789">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Domisili</label>
                                        <textarea name="address" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" placeholder="Alamat lengkap saat ini...">{{ old('address', $user->student->address ?? '') }}</textarea>
                                    </div>
                                </div>
                                @endrole

                                {{-- C. KHUSUS PEMBIMBING --}}
                                @role('pembimbing')
                                <hr class="border-gray-100 my-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Data Kepegawaian</p>
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan</label>
                                        <input type="text" name="position" value="{{ old('position', $user->mentor->position ?? ($user->division->name ?? '-')) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">NIP (Opsional)</label>
                                        <input type="text" name="nip" value="{{ old('nip', $user->mentor->nip ?? '') }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">No. Handphone</label>
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->mentor->phone_number ?? '') }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                                    </div>
                                </div>
                                @endrole

                                <div class="flex justify-end pt-8 mt-6 border-t border-gray-100">
                                    <button type="submit" class="px-8 py-3 bg-[#1B2A52] text-white font-bold rounded-xl hover:bg-blue-900 shadow-lg transition-all transform hover:-translate-y-0.5">
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
