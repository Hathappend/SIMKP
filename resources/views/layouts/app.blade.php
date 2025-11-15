<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">

<!-- Root Alpine State -->
<div
    x-data="{
        sidebarOpen: false,
        showMobileSearch: false,
        isMobile() { return window.innerWidth < 768 }
    }"
    @click=" if (isMobile()) showMobileSearch = false "
    class="flex h-screen"
>

    <!-- MOBILE OVERLAY -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        x-transition.opacity>
    </div>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- TOPBAR -->
        @include('layouts.topbar')

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
