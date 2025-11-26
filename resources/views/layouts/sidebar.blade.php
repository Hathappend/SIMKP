<aside
    class="fixed lg:sticky top-0 z-40 inset-y-0 left-0
           w-72 bg-[#0F1C3F] text-gray-300 h-screen flex flex-col justify-between
           shadow-xl transition-transform duration-300
           -translate-x-full lg:translate-x-0 border-r border-gray-800"
    :class="{ 'translate-x-0': sidebarOpen }">

    {{-- LOGO --}}
    <div class="flex justify-center mb-14 items-center h-24 flex-shrink-0 px-6 border-b border-gray-800/50">
        <img src="{{ asset('images/diskominfo-logo.png') }}"
             class="w-48 h-auto object-contain"
             alt="Logo">
    </div>

    {{-- MENU UTAMA --}}
    <nav class="flex-1 px-4 space-y-2 mt-6 overflow-y-auto custom-scrollbar">

        {{-- === ADMIN === --}}
        @role('admin')

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200 group
            {{ request()->is('admin/dashboard*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span >Dashboard</span>
        </a>

        <a href="{{ route('admin.registration.create') }}"
           class="flex items-center justify-between py-3 px-4 rounded-xl transition-all duration-200 group
            {{ request()->is('admin/pengajuan*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                </svg>
                <span >Pengajuan Magang</span>
            </div>
            @if(isset($pendingRegistrationCount) && $pendingRegistrationCount > 0)
                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm">
                    {{ $pendingRegistrationCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.surat.index') }}"
           class="flex items-center justify-between py-3 px-4 rounded-xl transition-all duration-200 group
            {{ request()->is('admin/surat*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>Pembuatan Surat</span>
            </div>
            @if(isset($pendingLetterCount) && $pendingLetterCount > 0)
                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-yellow-500 rounded-full shadow-sm">
                    {{ $pendingLetterCount }}
                </span>
            @endif
        </a>

        <div class="pt-4 pb-2">
            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Master Data</p>
        </div>

        <a href="{{ route('admin.mahasiswa.index') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200
            {{ request()->is('admin/mahasiswa*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span >Data Mahasiswa</span>
        </a>

        <a href="{{ route('admin.pembimbing.index') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200
            {{ request()->is('admin/pembimbing*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span >Data Pembimbing</span>
        </a>

        <a href="{{ route('admin.divisi.index') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200
            {{ request()->is('admin/divisi*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
            </svg>
            <span class="font-medium text-sm">Data Divisi</span>
        </a>

        <a href="{{ route('admin.user.index') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200
            {{ request()->is('admin/user*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            <span >Data User</span>
        </a>

        <a href="{{ route('admin.arsip.index') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-xl transition-all duration-200
            {{ request()->is('admin/arsip-dokumen*') ? 'bg-[#1B2A52] text-white shadow-lg shadow-blue-900/20' : 'hover:bg-white/5 hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            <span >Arsip Dokumen</span>
        </a>
        @endrole

        <!-- Other roles tetap seperti kode kamu -->
        @role('pembimbing')
        <a href="{{ route("pembimbing.dashboard") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('pembimbing/dashboard*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route("pembimbing.mahasiswa.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('pembimbing/mahasiswa*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 21a8.25 8.25 0 0115 0H4.5z" />
            </svg>
            <span>Mahasiswa Bimbingan</span>
        </a>

        <a href="{{ route('pembimbing.laporan.index') }}"
           class="flex items-center justify-between py-3 px-4 rounded-lg transition group
            {{ request()->is('pembimbing/laporan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}"
           title="Validasi laporan akhir mahasiswa">

            {{-- Icon & Teks --}}
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="font-medium">Review Laporan</span>
            </div>

            @if(isset($pendingReportCount) && $pendingReportCount > 0)
                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm group-hover:bg-white group-hover:text-[#1B2A52] transition-colors">
                    {{ $pendingReportCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('pembimbing.penilaian.index') }}"
           class="flex items-center justify-between py-3 px-4 rounded-lg transition group
           {{ request()->is('mentor/penilaian*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}"
           title="Input nilai akhir magang">

            {{-- Icon & Teks --}}
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                <span class="font-medium">Penilaian KP</span>
            </div>

            @if(isset($pendingGradingCount) && $pendingGradingCount > 0)
                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-yellow-500 rounded-full shadow-sm group-hover:bg-white group-hover:text-[#1B2A52] transition-colors">
                    {{ $pendingGradingCount }}
                </span>
            @endif
        </a>
        @endrole

        @role('kepala_divisi')
        <a href="{{ route("kadiv.dashboard") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('kadiv/dashboard*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('kadiv.pengajuan.index') }}"
           class="flex items-center justify-between py-3 px-4 rounded-lg transition group
           {{ request()->is('kadiv/pengajuan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white ' }}"
           title="Daftar pengajuan mahasiswa yang perlu persetujuan Anda">

            {{-- Ikon & Teks --}}
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                <span class="font-medium">Verifikasi Pengajuan</span>
            </div>

            {{-- Badge Notifikasi (Hanya muncul jika > 0) --}}
            @if(isset($pendingVerificationCount) && $pendingVerificationCount > 0)
                <span class="flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm group-hover:bg-white group-hover:text-[#1B2A52] transition-colors">
            {{ $pendingVerificationCount }}
        </span>
            @endif
        </a>

        <a href="{{ route("kadiv.mahasiswa.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('kadiv/mahasiswa*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
            </svg>
            <span>Data Mahasiswa</span>
        </a>
        @endrole

        @role('mahasiswa')
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route("mahasiswa.attendance.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/kehadiran*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
            </svg>
            <span>Kehadiran</span>
        </a>

        <a href="{{ route("mahasiswa.logbook.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/logbook*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <span>Logbook</span>
        </a>

        <a href="{{ route("mahasiswa.laporan.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/laporan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>

            <span>Laporan</span>
        </a>
        @endrole

    </nav>

    <div class="p-4 border-t border-gray-800 bg-[#0b1633]/50 mt-auto flex-shrink-0">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-colors {{ request()->is('profile*') ? 'bg-white/10 text-white' : 'text-gray-400' }}">

            {{-- Foto Profil Mini --}}
            @if(Auth::user()->avatar)
                <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-gray-600">
            @else
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-xs font-bold text-white">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">Pengaturan Akun</p>
            </div>
        </a>
    </div>

</aside>

{{-- STYLE CSS UNTUK SCROLLBAR --}}
<style>
    .no-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .no-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .no-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
    }
    .no-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.3);
    }
    /* Class utility untuk Firefox */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
    }
</style>
