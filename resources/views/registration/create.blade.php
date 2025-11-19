<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Kerja Praktek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite('resources/css/app.css') --}}
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 text-gray-800">

<div class="min-h-screen flex flex-col md:flex-row">

    <div class="hidden md:block md:w-1/2 lg:w-3/5 relative">
        <div class="absolute inset-0 bg-gradient-to-tr from-black/60 via-black/30 to-transparent z-10"></div>
        <img src="{{ asset('images/diskominfo.png') }}"
             alt="Diskominfo Jabar"
             class="absolute inset-0 w-full h-full object-cover object-right filter brightness-90 saturate-125 contrast-110">
        <div class="absolute bottom-8 left-8 text-white z-20">
            <h2 class="text-3xl font-semibold drop-shadow-lg">Diskominfo Jawa Barat</h2>
            <p class="text-sm text-gray-200 mt-2 drop-shadow">Sistem Kerja Praktek Terintegrasi</p>
        </div>
    </div>

    <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center bg-white px-6 py-10 md:px-10 lg:px-12 shadow-2xl">

        <div class="flex justify-end mb-6">
            <img src="{{ asset('images/diskominfo-logo.png') }}" alt="Logo Diskominfo Jabar" class="h-10">
        </div>

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Registrasi Kerja Praktek</h1>
            <p class="text-gray-500 text-sm mt-2">
                Silakan isi data berikut untuk mengajukan Kerja Praktek Anda
            </p>
        </div>

        <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
              class="space-y-5 max-w-md mx-auto w-full">
            @csrf

            @if(session('success'))
                <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" placeholder="Masukkan nama lengkap"
                       value="{{ old('full_name') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('full_name') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('full_name')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                <input type="text" id="nim" name="nim" placeholder="Masukkan NIM"
                       value="{{ old('nim') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('nim') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('nim')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="study_program" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <input type="text" id="study_program" name="study_program" placeholder="Masukkan program studi"
                       value="{{ old('study_program') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('study_program') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('study_program')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="university" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                <input type="text" id="university" name="university" placeholder="Masukkan Asal Sekolah"
                       value="{{ old('university') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('university') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('university')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Divisi Tujuan</label>
                <div class="relative">
                    <select id="division_id" name="division_id"
                            class="w-full px-4 py-2.5 border {{ $errors->has('division_id') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition appearance-none">
                        <option value="" disabled {{ old('division_id') ? '' : 'selected' }}>-- Pilih Divisi --</option>

                        {{-- Pastikan variabel $divisions dikirim dari controller --}}
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                @error('division_id')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Contoh: example@gmail.com"
                       value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('email')
                <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pelaksanaan KP</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full px-4 py-2.5 border {{ $errors->has('start_date') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-800 transition">
                        @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full px-4 py-2.5 border {{ $errors->has('end_date') ? 'border-red-400' : 'border-gray-300' }} rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-800 transition">
                        @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center text-sm text-gray-600 mt-2">
                Sudah memiliki akun?
                <a href="" class="text-gray-900 font-medium hover:underline hover:text-gray-700 transition">
                    Masuk di sini
                </a>
            </div>

            <div class="pt-3">
                <button type="submit"
                        class="w-full py-3 rounded-lg bg-gray-900 text-white font-semibold text-sm tracking-wide hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                    Daftar Sekarang
                </button>
            </div>
        </form>

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
            // Ubah border jadi solid saat file dipilih
            zone.classList.remove('border-dashed');
            zone.classList.add('border-solid', 'border-gray-400', 'bg-gray-100');
        } else {
            textDisplay.textContent = '';
            textDisplay.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }
</script>

</body>
</html>
