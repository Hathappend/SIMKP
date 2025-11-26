<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kerja Praktek Diskominfo Jabar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        .text-gradient-animated {
            background-size: 200% 200%;
            animation: gradient-move 3s ease infinite;
        }
        @keyframes gradient-move {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-800 overflow-x-hidden">

{{-- NAVBAR (GLASSMORPHISM) --}}
<nav class="fixed w-full z-50 top-0 start-0 transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-white/20 shadow-sm">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between px-6 py-4 lg:px-8">
        <a href="#" class="flex items-center gap-3 group">
            <img src="{{ asset('images/diskominfo-logo.png') }}" class="h-10 group-hover:scale-105 transition-transform" alt="Logo Diskominfo">
            <div class="leading-tight hidden sm:block">
                <h1 class="text-lg font-bold text-[#1B2A52] tracking-tight">DISKOMINFO</h1>
                <p class="text-[10px] text-gray-500 font-medium tracking-widest">PROVINSI JAWA BARAT</p>
            </div>
        </a>
        <div class="flex space-x-3 md:space-x-4">
            @auth
                @php
                    if (auth()->user()->hasRole('admin')) {
                        $dashboardRoute = route('admin.dashboard');
                    } elseif (auth()->user()->hasRole('kepala_divisi')) {
                        $dashboardRoute = route('kadiv.dashboard');
                    } elseif (auth()->user()->hasRole('pembimbing')) {
                        $dashboardRoute = route('pembimbing.dashboard');
                    } elseif (auth()->user()->hasRole('mahasiswa')) {
                        $dashboardRoute = route('mahasiswa.dashboard');
                    } else {
                        $dashboardRoute = null;
                    }
                @endphp

                @if ($dashboardRoute)
                    <a href="{{ $dashboardRoute }}"
                       class="text-white bg-[#1B2A52] hover:bg-blue-900 font-bold rounded-xl text-sm px-6 py-2.5 transition-all shadow-lg hover:shadow-blue-900/30 hover:-translate-y-0.5">
                        Dashboard
                    </a>
                @endif

            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#1B2A52] font-bold rounded-xl text-sm px-6 py-2.5 transition-colors hover:bg-gray-100">
                    Masuk
                </a>
                <a href="{{ route('registration.create') }}" class="text-white bg-[#1B2A52] hover:bg-blue-900 font-bold rounded-xl text-sm px-6 py-2.5 transition-all shadow-lg hover:shadow-blue-900/30 hover:-translate-y-0.5">
                    Daftar KP
                </a>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO SECTION --}}
<section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    {{-- Animated Blobs --}}
    <div class="absolute top-0 left-1/2 w-[800px] h-[800px] bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute top-20 right-1/4 w-[600px] h-[600px] bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center relative z-10">
            <span class="inline-flex items-center py-1.5 px-4 rounded-full bg-white/80 backdrop-blur-sm text-[#1B2A52] text-xs font-bold mb-8 tracking-wide uppercase border border-blue-100 shadow-sm hover:shadow-md transition-shadow cursor-default">
                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                Penerimaan Kerja Praktek Mahasiswa
            </span>

        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight mb-6 leading-[1.1]">
            Wadah Pengembangan <br class="hidden md:block"> Talenta
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1B2A52] via-blue-600 to-[#1B2A52] text-gradient-animated">Digital Masa Depan</span>
        </h1>

        <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl mx-auto leading-relaxed">
            Diskominfo Jabar membuka kesempatan bagi mahasiswa aktif untuk berkontribusi dalam inovasi pemerintahan melalui program magang terstruktur.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('registration.create') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-[#1B2A52] rounded-2xl hover:bg-blue-900 shadow-xl shadow-blue-900/20 transition-all transform hover:-translate-y-1">
                Ajukan Permohonan
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
            </a>
            <a href="#syarat" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-700 bg-white border border-gray-200 rounded-2xl hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm hover:shadow-md">
                Lihat Persyaratan
            </a>
        </div>
    </div>
</section>

{{-- ALUR PENDAFTARAN --}}
<section class="py-20 bg-white border-t border-gray-100 relative">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Alur Pendaftaran</h2>
            <p class="text-gray-500 mt-2">4 Langkah mudah memulai perjalanan karir Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            {{-- Garis Penghubung --}}
            <div class="hidden md:block absolute top-10 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-blue-50 via-indigo-100 to-blue-50 -z-10"></div>

            {{-- Step Items --}}
            @foreach([
                ['num' => '1', 'title' => 'Buat Akun', 'desc' => 'Daftarkan diri Anda pada portal ini untuk memulai proses.', 'color' => 'blue'],
                ['num' => '2', 'title' => 'Upload Berkas', 'desc' => 'Unggah surat pengantar kampus & rekomendasi Kesbangpol.', 'color' => 'indigo'],
                ['num' => '3', 'title' => 'Verifikasi', 'desc' => 'Admin & Kepala Divisi akan meninjau berkas Anda.', 'color' => 'purple'],
                ['num' => '4', 'title' => 'Mulai KP', 'desc' => 'Dapatkan surat balasan dan mulai magang.', 'color' => 'green']
            ] as $step)
                <div class="relative group">
                    <div class="w-20 h-20 mx-auto bg-white border-4 border-{{ $step['color'] }}-50 text-{{ $step['color'] }}-600 rounded-full flex items-center justify-center text-2xl font-bold mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        {{ $step['num'] }}
                    </div>
                    <div class="text-center bg-gray-50 rounded-2xl p-6 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 transition-all border border-transparent hover:border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PERSYARATAN DOKUMEN --}}
<section id="syarat" class="py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">
        <div class="bg-[#1B2A52] rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col md:flex-row relative">

            {{-- Pattern Overlay --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>

            <div class="p-10 md:p-16 md:w-3/5 text-white flex flex-col justify-center relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Persyaratan Dokumen</h2>
                <p class="text-blue-100 mb-10 text-lg">Siapkan file PDF berikut sebelum mendaftar agar proses verifikasi lebih cepat.</p>

                <div class="space-y-6">
                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 border border-white/20 group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold">Surat Pengantar Kampus</h4>
                            <p class="text-sm text-blue-200 mt-1 leading-relaxed">Dokumen resmi dari Universitas yang menyatakan permohonan KP.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-5 group">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0 border border-white/20 group-hover:bg-white/20 transition-colors">
                            <svg class="w-6 h-6 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold">Surat Rekomendasi Kesbangpol</h4>
                            <p class="text-sm text-blue-200 mt-1 leading-relaxed">Surat izin penelitian dari Bakesbangpol Provinsi/Kota.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA Block --}}
            <div class="bg-blue-900/40 p-10 md:p-16 md:w-2/5 flex flex-col items-center justify-center text-center relative overflow-hidden border-l border-white/5">
                <div class="relative z-10 w-full">
                    <p class="text-blue-200 text-sm font-bold mb-6 uppercase tracking-widest">Dokumen Lengkap?</p>
                    <a href="{{ route('registration.create') }}" class="inline-flex items-center justify-center w-full py-4 px-6 bg-white text-[#1B2A52] font-bold rounded-2xl hover:bg-blue-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 group">
                        Isi Formulir Pendaftaran
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                    <p class="text-xs text-blue-300 mt-4 opacity-80">Format PDF • Maks. 2MB</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-20 bg-white mb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Pertanyaan Umum</h2>
            <p class="text-gray-500 mt-2">Hal yang sering ditanyakan mahasiswa.</p>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            {{-- FAQ 1 --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button @click="active = active === 1 ? null : 1" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition text-left font-semibold text-gray-800">
                    <span>Berapa lama proses verifikasi berkas?</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="active === 1" x-collapse class="p-4 text-sm text-gray-600 bg-white border-t border-gray-200">
                    Proses verifikasi biasanya memakan waktu <strong>3-5 hari kerja</strong>. Anda akan mendapatkan notifikasi via email jika ada pembaruan status.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button @click="active = active === 2 ? null : 2" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition text-left font-semibold text-gray-800">
                    <span>Apakah magang ini berbayar/digaji?</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="active === 2" x-collapse class="p-4 text-sm text-gray-600 bg-white border-t border-gray-200">
                    Program Kerja Praktek di Diskominfo bersifat <strong>Unpaid (Tidak Berbayar)</strong>. Fokus program ini adalah pembelajaran dan pengalaman kerja nyata.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <button @click="active = active === 3 ? null : 3" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition text-left font-semibold text-gray-800">
                    <span>Apakah boleh daftar berkelompok?</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="active === 3" x-collapse class="p-4 text-sm text-gray-600 bg-white border-t border-gray-200">
                    <strong>Boleh.</strong> Silakan daftar menggunakan akun Ketua Kelompok, lalu tambahkan nama anggota di kolom "Anggota Tim" saat mengisi formulir.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-[#0F1C3F] text-white py-16 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
        <img src="{{ asset('images/diskominfo-logo.png') }}" class="h-14 mx-auto mb-8 opacity-90" alt="Logo Diskominfo">
        <p class="text-blue-200 text-sm mb-8 max-w-md mx-auto leading-relaxed">
            Mewujudkan tata kelola pemerintahan yang inovatif dan kolaboratif melalui transformasi digital.
        </p>
        <div class="flex justify-center gap-6 mb-12 text-sm font-medium text-blue-300">
            <a href="https://diskominfo.jabarprov.go.id/" class="hover:text-white transition-colors">Website Resmi</a>
            <a href="#" class="hover:text-white transition-colors">Kontak Kami</a>
            <a href="#" class="hover:text-white transition-colors">Bantuan</a>
        </div>
        <div class="pt-8 border-t border-white/10 text-xs text-gray-500">
            &copy; {{ date('Y') }} Diskominfo Jabar. Sistem Informasi Manajemen Kerja Praktek.
        </div>
    </div>
</footer>

</body>
</html>
