<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kerja Praktek</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 text-gray-800 min-h-screen flex items-center justify-center">

<div class="flex flex-col md:flex-row w-full md:min-h-screen">

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

    <!-- Bagian Kanan (Form Login) -->
    <div class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center px-6 py-10 md:px-10 lg:px-12
                md:bg-white md:shadow-2xl transition-all duration-500">

        <!-- Logo (Desktop) -->
        <div class="hidden md:flex justify-end mb-6">
            <img src="{{ asset('images/diskominfo-logo.png') }}" alt="Logo Diskominfo Jabar" class="h-10">
        </div>

        <!-- Logo (Mobile) -->
        <div class="flex md:hidden justify-center mb-6">
            <img src="{{ asset('images/diskominfo-logo.png') }}" alt="Logo Diskominfo Jabar" class="h-12">
        </div>

        <!-- Judul -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">LOGIN</h1>
            <p class="text-gray-500 text-sm mt-2">Silahkan masuk untuk melaksanakan Kerja Praktek</p>
        </div>

        <!-- Status / Error -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 text-center">
                {{ __('Email atau password salah.') }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5 max-w-md mx-auto w-full">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="Example@gmail.com"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50
                              focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="***************"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50
                              focus:bg-white focus:border-gray-500 focus:ring-2 focus:ring-gray-800 transition">
                <div class="text-right mt-1">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>
            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="form-checkbox h-4 w-4 text-gray-800 rounded mr-2">
                    Remember me
                </label>
            </div>

            <!-- Tombol Login -->
            <div>
                <button type="submit"
                        class="w-full py-3 rounded-lg bg-gray-900 text-white font-semibold text-sm tracking-wide
                               hover:bg-gray-800 transition-all shadow-md hover:shadow-lg">
                    {{ __('Login') }}
                </button>
            </div>

            <!-- Buat Akun -->
            <div class="text-center text-sm text-gray-600 mt-4">
                Belum punya akun?
                <a href="{{ route('registration.create') }}"
                   class="text-gray-900 font-medium hover:underline hover:text-gray-700 transition">
                    Daftar Sekarang
                </a>
            </div>
        </form>

        <!-- Footer -->
        <p class="text-xs text-center text-gray-400 mt-10">
            © 2025 Diskominfo Jabar. All rights reserved.
        </p>
    </div>
</div>

</body>
</html>
