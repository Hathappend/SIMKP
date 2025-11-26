@forelse($notifications as $notification)
    <a href="{{ route('notifications.open', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
        <div class="flex gap-3">
            <div class="flex-shrink-0 mt-1">

                {{-- LOGIKA IKON BERDASARKAN DATA --}}
                @php
                    $iconType = $notification->data['icon'] ?? 'default';
                    $color = $notification->data['color'] ?? 'blue';

                    // Mapping Warna Tailwind
                    $bgClass = match($color) {
                        'yellow' => 'bg-yellow-100 text-yellow-600',
                        'green' => 'bg-green-100 text-green-600',
                        'red' => 'bg-red-100 text-red-600',
                        default => 'bg-blue-100 text-blue-600',
                    };
                @endphp

                <div class="w-8 h-8 rounded-full {{ $bgClass }} flex items-center justify-center">
                    @if($iconType == 'document-text')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    @elseif($iconType == 'user-plus')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    @elseif($iconType == 'document-check')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($iconType == 'book-open')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    @elseif($iconType == 'check-circle')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($iconType == 'x-circle')
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        {{-- Ikon Default (Lonceng) --}}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </a>
@empty
    <div class="px-4 py-6 text-center text-gray-500 text-sm">
        Tidak ada notifikasi baru.
    </div>
@endforelse
