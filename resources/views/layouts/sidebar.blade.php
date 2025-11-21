<aside
    class="fixed lg:static z-40 inset-y-0 left-0
           w-72 bg-[#0F1C3F] text-gray-300 h-screen flex flex-col
           rounded-r-3xl shadow-xl py-10 px-6 transform
           transition-transform duration-300
           -translate-x-full lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen }">

    <!-- Logo -->
    <div class="flex justify-center mb-14">
        <img src="{{ asset('images/diskominfo-logo.png') }}"
             class="w-48 h-auto object-contain"
             alt="Logo">
    </div>

    <!-- Menu -->
    <nav class="flex-1 space-y-1 mt-20">

        @role('admin')
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/dashboard*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.registration.create') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/pengajuan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
            </svg>
            <span>Pengajuan Magang</span>
        </a>

        <a href="{{ route("admin.surat.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/surat*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Pembuatan Surat</span>
        </a>

        <a href="#"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/mahasiswa*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <span>Data Mahasiswa</span>
        </a>

        <a href="{{ route("admin.pembimbing.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/pembimbing*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7141 15h4.268c.4043 0 .732-.3838.732-.8571V3.85714c0-.47338-.3277-.85714-.732-.85714H6.71411c-.55228 0-1 .44772-1 1v4m10.99999 7v-3h3v3h-3Zm-3 6H6.71411c-.55228 0-1-.4477-1-1 0-1.6569 1.34315-3 3-3h2.99999c1.6569 0 3 1.3431 3 3 0 .5523-.4477 1-1 1Zm-1-9.5c0 1.3807-1.1193 2.5-2.5 2.5s-2.49999-1.1193-2.49999-2.5S8.8334 9 10.2141 9s2.5 1.1193 2.5 2.5Z"/>
            </svg>


            <span>Data Pembimbing</span>
        </a>

        <a href="{{ route("admin.divisi.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/divisi*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7141 15h4.268c.4043 0 .732-.3838.732-.8571V3.85714c0-.47338-.3277-.85714-.732-.85714H6.71411c-.55228 0-1 .44772-1 1v4m10.99999 7v-3h3v3h-3Zm-3 6H6.71411c-.55228 0-1-.4477-1-1 0-1.6569 1.34315-3 3-3h2.99999c1.6569 0 3 1.3431 3 3 0 .5523-.4477 1-1 1Zm-1-9.5c0 1.3807-1.1193 2.5-2.5 2.5s-2.49999-1.1193-2.49999-2.5S8.8334 9 10.2141 9s2.5 1.1193 2.5 2.5Z"/>
            </svg>


            <span>Data Divisi</span>
        </a>

        <a href="{{ route("admin.user.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/user*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg class="w-6 h-6 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7141 15h4.268c.4043 0 .732-.3838.732-.8571V3.85714c0-.47338-.3277-.85714-.732-.85714H6.71411c-.55228 0-1 .44772-1 1v4m10.99999 7v-3h3v3h-3Zm-3 6H6.71411c-.55228 0-1-.4477-1-1 0-1.6569 1.34315-3 3-3h2.99999c1.6569 0 3 1.3431 3 3 0 .5523-.4477 1-1 1Zm-1-9.5c0 1.3807-1.1193 2.5-2.5 2.5s-2.49999-1.1193-2.49999-2.5S8.8334 9 10.2141 9s2.5 1.1193 2.5 2.5Z"/>
            </svg>


            <span>Data User</span>
        </a>

        <a href="#"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('admin/arsip*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 4h18v4H3z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M5 8h14v10a2 2 0 01-2 2H7a2 2 0 01-2-2V8z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10 12h4" />
            </svg>
            <span>Arsip Dokumen</span>
        </a>
        @endrole

        <!-- Other roles tetap seperti kode kamu -->
        @role('pembimbing')
        <a href="#"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('pembimbing/dashboard*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard Pembimbing</span>
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

        <a href="{{ route("kadiv.pengajuan.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('kadiv/pengajuan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 4.5v6h6M14 3H6v18h12V9l-4-6z" />
            </svg>
            <span>Verifikasi Pengajuan</span>
        </a>
        @endrole

        @role('mahasiswa')
        <a href="{{ route('mahasiswa.dashboard') }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route("mahasiswa.attendance.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/kehadiran*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 7h5l2 2h11v9H3z" />
            </svg>
            <span>Kehadiran</span>
        </a>

        <a href="{{ route("mahasiswa.logbook.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/logbook*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 7h5l2 2h11v9H3z" />
            </svg>
            <span>Logbook</span>
        </a>

        <a href="{{ route("mahasiswa.laporan.index") }}"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg transition
           {{ request()->is('mahasiswa/laporan*') ? 'bg-[#1B2A52] text-white' : 'hover:bg-[#1B2A52] hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 7h5l2 2h11v9H3z" />
            </svg>
            <span>Laporan</span>
        </a>
        @endrole

    </nav>

    <!-- Bottom menu -->
    <div class="mt-10 pt-5 border-t border-gray-600">
        <a href="#"
           class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-[#1B2A52] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.89 3.31.877 2.419 2.42a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.89 1.543-.876 3.31-2.42 2.419a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.89-3.31-.876-2.419-2.419a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.89-1.543.876-3.31 2.419-2.419.924.533 2.146.183 2.573-1.065z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            <span>Pengaturan</span>
        </a>
    </div>

</aside>
