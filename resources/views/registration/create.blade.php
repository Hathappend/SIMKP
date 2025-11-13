<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Kerja Praktek</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 text-gray-800">

<div class="min-h-screen flex flex-col md:flex-row">

    <!-- Sisi Kiri -->
    <div class="hidden md:block md:w-1/2 lg:w-3/5 relative">
        <!-- Overlay gelap transparan -->
        <div class="absolute inset-0 bg-gradient-to-tr from-black/60 via-black/30 to-transparent z-10"></div>

        <!-- Gambar dengan filter -->
        <img src="{{ asset('images/diskominfo.png') }}"
             alt="Diskominfo Jabar"
             class="absolute inset-0 w-full h-full object-cover object-right filter brightness-90 saturate-125 contrast-110">

        <!-- Teks di bawah -->
        <div class="absolute bottom-8 left-8 text-white z-20">
            <h2 class="text-3xl font-semibold drop-shadow-lg">Diskominfo Jawa Barat</h2>
            <p class="text-sm text-gray-200 mt-2 drop-shadow">Sistem Kerja Praktek Terintegrasi</p>
        </div>
    </div>



    <!-- Sisi Kanan -->
    <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center bg-white px-6 py-10 md:px-10 lg:px-12 shadow-2xl">

        <!-- Logo -->
        <div class="flex justify-end mb-6">
            <img src="{{ asset('images/diskominfo-logo.png') }}" alt="Logo Diskominfo Jabar" class="h-10">
        </div>

        <!-- Judul -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Registrasi Kerja Praktek</h1>
            <p class="text-gray-500 text-sm mt-2">
                Silakan isi data berikut untuk mengajukan Kerja Praktek Anda
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
              class="space-y-5 max-w-md mx-auto w-full">
            @csrf

            <!-- Pesan sukses -->
            @if(session('success'))
                <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Nama Lengkap -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" placeholder="Masukkan nama lengkap"
                       value="{{ old('full_name') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('full_name') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('full_name')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- NIM -->
            <div>
                <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                <input type="text" id="nim" name="nim" placeholder="Masukkan NIM"
                       value="{{ old('nim') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('nim') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('nim')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Program Studi -->
            <div>
                <label for="study_program" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <input type="text" id="study_program" name="study_program" placeholder="Masukkan program studi"
                       value="{{ old('study_program') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('study_program') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('study_program')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Contoh: example@gmail.com"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('email')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Waktu Pelaksanaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pelaksanaan KP</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="date" id="start_date" name="start_date"
                               class="w-full px-4 py-2.5 border {{ $errors->has('start_date') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-800 transition">
                        @error('start_date')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                             d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <input type="date" id="end_date" name="end_date"
                               class="w-full px-4 py-2.5 border {{ $errors->has('end_date') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-800 transition">
                        @error('end_date')
                        <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                             d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Upload Surat Pengantar -->
            <div>
                <label for="internship_letter" class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Surat Pengantar Kampus
                </label>
                <div id="dropzone-letter" class="relative border-2 border-dashed {{ $errors->has('internship_letter') ? 'border-red-400' : 'border-gray-300' }} rounded-xl p-5 text-center hover:border-gray-400 bg-gray-50 transition">
                    <input type="file" id="internship_letter" name="internship_letter"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           onchange="updateFileName('internship_letter', 'show-file-name', 'dropzone-letter')">
                    <p class="text-gray-500 text-sm select-none">Klik atau seret file ke sini</p>
                    <p id="show-file-name" class="mt-1 text-sm text-gray-700 font-medium truncate hidden"></p>
                </div>
                @error('internship_letter')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Upload Surat Kesbangpol -->
            <div>
                <label for="kesbangpol_letter" class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Surat Kesbangpol
                </label>
                <div id="dropzone-kesbangpol" class="relative border-2 border-dashed {{ $errors->has('kesbangpol_letter') ? 'border-red-400' : 'border-gray-300' }} rounded-xl p-5 text-center hover:border-gray-400 bg-gray-50 transition">
                    <input type="file" id="kesbangpol_letter" name="kesbangpol_letter"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           onchange="updateFileName('kesbangpol_letter', 'file-name-kesbangpol', 'dropzone-kesbangpol')">
                    <p class="text-gray-500 text-sm select-none">Klik atau seret file ke sini</p>
                    <p id="file-name-kesbangpol" class="mt-1 text-sm text-gray-700 font-medium truncate hidden"></p>
                </div>
                @error('kesbangpol_letter')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                     d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    {{ $message }}
                </p>
                @enderror

            </div>



            <!-- Sudah punya akun -->
            <div class="text-center text-sm text-gray-600 mt-2">
                Sudah memiliki akun?
                <a href=""
                   class="text-gray-900 font-medium hover:underline hover:text-gray-700 transition">
                    Masuk di sini
                </a>
            </div>

            <!-- Tombol -->
            <div class="pt-3">
                <button type="submit"
                        class="w-full py-3 rounded-lg bg-gray-900 text-white font-semibold text-sm tracking-wide hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Footer -->
        <p class="text-xs text-center text-gray-400 mt-10">
            © 2025 Diskominfo Jabar. All rights reserved.
        </p>
    </div>
</div>

<script>
    function updateFileName(inputId, textId, zoneId) {
        const input = document.getElementById(inputId);
        const textDisplay = document.getElementById(textId);
        const zone = document.getElementById(zoneId);
        const placeholder = zone.querySelector('p.text-gray-500');

        if (input.files && input.files.length > 0) {
            textDisplay.textContent = input.files[0].name;
            textDisplay.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            textDisplay.textContent = '';
            textDisplay.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }
</script>

</body>
</html>
