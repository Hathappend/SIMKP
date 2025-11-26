<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | SIM KP Diskominfo Jabar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

{{-- Pastikan data-turbo="false" tetap ada --}}
<body class="bg-gray-50/50 antialiased" data-turbo="false">
<x-toast />

<div
    x-data="{
            sidebarOpen: false,
            showMobileSearch: false,
            isMobile() { return window.innerWidth < 768 }
        }"
    class="flex"
>

    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        x-transition.opacity
        style="display: none;">
    </div>

    <div
        x-show="showMobileSearch"
        @click="showMobileSearch = false"
        class="fixed inset-0 z-30 bg-black/20"
        style="display: none;">
    </div>

    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden relative">

        @include('layouts.topbar')

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
