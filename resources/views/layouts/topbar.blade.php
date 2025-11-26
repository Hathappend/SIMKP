<header class="py-4" @click.stop>

    <div
         class="relative" >

        <div class="flex justify-end items-center px-10 relative"
             :class="showMobileSearch && isMobile() ? 'absolute inset-0 bg-white z-50 px-4' : ''">

            <!-- HAMBURGER -->
            <button
                @click.stop="sidebarOpen = true"
                class="lg:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 transition"
                :class="showMobileSearch && isMobile() ? 'hidden' : ''"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- SEARCH BAR -->
{{--            <div class="w-full max-w-xl mx-auto md:block flex-1 relative" @click.stop>--}}
{{--                <!-- SEARCH ICON (only mobile) -->--}}
{{--                <button--}}
{{--                    @click.stop="showMobileSearch = true; $nextTick(() => $refs.mobileSearch.focus())"--}}
{{--                    class="md:hidden absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2--}}
{{--                            bg-gray-100 hover:bg-gray-200 p-3 rounded-full shadow-sm flex items-center justify-center"--}}
{{--                    :class="showMobileSearch ? 'hidden' : ''">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">--}}
{{--                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />--}}
{{--                    </svg>--}}
{{--                </button>--}}


{{--                <!-- SEARCH INPUT (mobile expanded) -->--}}
{{--                <div class="relative transition-all duration-200"--}}
{{--                     :class="showMobileSearch && isMobile() ? 'max-w-full' : 'hidden md:block'">--}}

{{--                    <!-- Icon -->--}}
{{--                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"--}}
{{--                         fill="none" stroke="currentColor" stroke-width="2"--}}
{{--                         viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                              d="M21 21l-4.35-4.35m1.6-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />--}}
{{--                    </svg>--}}

{{--                    <!-- Input -->--}}
{{--                    <input type="search"--}}
{{--                           x-ref="mobileSearch"--}}
{{--                           placeholder="Pencarian"--}}
{{--                           @click.stop--}}
{{--                           class="--}}
{{--                            pl-12 pr-4 py-2.5 w-full--}}
{{--                            rounded-full border border-gray-300 bg-white--}}
{{--                            text-gray-700--}}
{{--                            focus:outline-none focus:ring-0--}}
{{--                            transition-all duration-200--}}
{{--                        "--}}
{{--                           :class="showMobileSearch && isMobile() ? 'shadow-md' : ''">--}}
{{--                </div>--}}

{{--            </div>--}}

        <!-- RIGHT ACTION ITEMS (CHAT, NOTIF, PROFILE) -->
            <div class="flex items-center space-x-8 ml-6"
                 x-show="!showMobileSearch"
                 x-transition.opacity>

                <!-- CHAT  -->
{{--                <button class="text-gray-600 hover:text-gray-800">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />--}}
{{--                    </svg>--}}
{{--                </button>--}}

                <!-- NOTIF -->
                <div class="relative"
                     x-data="{
                        notifOpen: false,
                        unreadCount: {{ auth()->user()->unreadNotifications->count() }}, // Data awal dari PHP

                        // Fungsi Polling
                        checkNotifications() {
                            fetch('{{ route('api.notifications.check') }}')
                                .then(response => response.json())
                                .then(data => {
                                    // 1. Update angka badge
                                    this.unreadCount = data.count;

                                    // 2. Update isi list HTML
                                    document.getElementById('notification-container').innerHTML = data.html;
                                })
                                .catch(error => console.error('Error fetching notifications:', error));
                        }
                     }"
                     {{-- Jalankan setiap 10 detik --}}
                     x-init="setInterval(() => checkNotifications(), 10000)"
                >

                    <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>

                        <span x-show="unreadCount > 0"
                              x-transition.scale
                              class="absolute top-1.5 right-1.5 flex h-3 w-3"
                              style="display: none;">

                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                        </span>
                    </button>

                    <div x-show="notifOpen"
                         @click.away="notifOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden origin-top-right"
                         style="display: none;">

                        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-sm font-bold text-gray-900">Notifikasi</h3>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.readAll') }}" class="text-xs text-indigo-600 hover:underline">Tandai semua dibaca</a>
                            @endif
                        </div>

                        <div id="notification-container" class="max-h-80 overflow-y-auto">
                            @include('layouts.partials.notification_list', ['notifications' => auth()->user()->unreadNotifications])
                        </div>
                    </div>
                </div>

                <!-- PROFILE DROPDOWN -->
                <div x-data="{ open: false }" class="relative">
                    <div @click.stop="open = !open"
                         class="flex items-center space-x-3 cursor-pointer select-none">

                        <!-- AVATAR -->
                        {{-- Foto Profil Mini --}}
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-8 h-8 rounded-full object-cover border border-gray-600">
                        @else
                            <img class="w-9 h-9 rounded-full object-cover shadow"
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random"
                                 alt="Avatar">
                        @endif

                        <!-- NAME & ROLE: Hanya tampil desktop -->
                        <div class="text-sm leading-tight hidden md:block">
                            <p class="font-medium text-gray-800">
                                {{ Auth::user()->name ?? 'User' }}
                            </p>
                            <p class="text-xs text-gray-500 capitalize">
                                {{ Str::title(str_replace('_', ' ', Auth::user()->getRoleNames()->first())) ?? 'Role' }}
                            </p>
                        </div>

                        <!-- CHEVRON ICON -->
                        <svg class="w-4 h-4 text-gray-600 transition-transform"
                             :class="{ 'rotate-180': open }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    <!-- DROPDOWN -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-3 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">

                        <a href="{{ route("profile.edit") }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition rounded-lg text-sm gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5.121 17.804A4 4 0 0112 15.5a4 4 0 016.879 2.304M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            Profile
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center w-full px-4 py-2.5 text-red-600 hover:bg-red-50 transition rounded-lg text-sm gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>


            </div>

        </div>

    </div>

</header>
