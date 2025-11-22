@extends('layouts.app')

@section('content')
    {{-- Alpine.js state untuk modal tambah, edit, dan delete --}}
    <div x-data="{
            modalLogbookAdd: {{ $errors->any() && !session('edit_mode') ? 'true' : 'false' }},
            modalLogbookEdit: {{ $errors->any() && session('edit_mode') ? 'true' : 'false' }},
            modalLogbookDelete: false,
            currentLogbook: null,
            editUrl: '',
            deleteUrl: ''
         }"
         class="min-h-screen bg-gray-50/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Logbook Kegiatan</h1>
                    <p class="text-gray-500 mt-1">Catat dan kelola riwayat aktivitas harian Anda.</p>
                </div>

                @if($registration->application_status == 'completed')

                    {{-- MAGANG SELESAI (TERKUNCI) --}}
                    <button disabled
                            class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-400 text-sm font-medium rounded-lg border border-gray-200 cursor-not-allowed shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Magang Selesai
                    </button>

                @else

                    {{-- AKTIF (BISA TAMBAH) --}}
                    <button @click="modalLogbookAdd = true"
                            class="inline-flex items-center px-5 py-2.5 bg-[#1B2A52] hover:bg-blue-900 text-white text-sm font-medium rounded-lg shadow-sm transition-all transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Logbook Baru
                    </button>

                @endif
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative mb-8 flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 mt-0.5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative mb-8 flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 mt-0.5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- LOGBOOK LIST --}}
            <div class="space-y-10">
                @forelse($groupedLogbooks as $date => $logbooksByDate)
                    <div class="relative">
                        {{-- Tanggal Header --}}
                        <div class="flex items-center gap-4 mb-4">
                            <h2 class="text-lg font-bold text-gray-800 whitespace-nowrap">{{ $date }}</h2>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        {{-- List Kegiatan --}}
                        <div class="space-y-3">
                            @foreach($logbooksByDate as $log)
                                {{-- CARD LOGBOOK --}}
                                <div class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-xl bg-white border border-gray-100 hover:border-indigo-200 hover:bg-white hover:shadow-sm transition-all duration-200">

                                    <div class="flex items-start gap-4 w-full sm:w-auto flex-1 mr-4">
                                        {{-- Jam Box --}}
                                        <div class="bg-white text-gray-800 font-bold px-3 py-2 rounded-lg shadow-sm border border-gray-100 text-sm min-w-[80px] text-center flex-shrink-0 group-hover:border-indigo-100 transition-colors">
                                            {{ \Carbon\Carbon::parse($log->start_time)->format('H:i') }}
                                        </div>

                                        {{-- Teks Kegiatan --}}
                                        <div class="min-w-0 flex-1 pt-1">
                                            <h4 class="text-gray-900 font-semibold text-sm group-hover:text-[#1B2A52] transition-colors leading-tight truncate pr-2">
                                                {{ $log->activity }}
                                            </h4>
                                            <p class="text-gray-500 text-xs mt-1 line-clamp-1 sm:line-clamp-2">
                                                {{ $log->description ?: 'Tidak ada deskripsi detail.' }}
                                            </p>

                                            {{-- Pesan Revisi --}}
                                            @if($log->status == 'rejected' && $log->feedback)
                                                <div class="mt-2 inline-flex items-start gap-1.5 bg-red-50 px-2 py-1 rounded text-red-600 text-[10px]">
                                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    <span>Revisi: {{ Str::limit($log->feedback, 50) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Kanan: Durasi, Status & Menu --}}
                                    <div class="mt-3 sm:mt-0 flex items-center gap-4 w-full sm:w-auto justify-between sm:justify-end flex-shrink-0 pl-[96px] sm:pl-0">
                                        {{-- Durasi --}}
                                        @php
                                            $start = \Carbon\Carbon::parse($log->start_time);
                                            $end = \Carbon\Carbon::parse($log->end_time);
                                            $diffInMinutes = $start->diffInMinutes($end);
                                            $hours = floor($diffInMinutes / 60);
                                            $minutes = $diffInMinutes % 60;
                                            $durationStr = ($hours > 0 ? $hours . 'j ' : '') . ($minutes > 0 ? $minutes . 'm' : '');
                                            if(empty($durationStr)) $durationStr = '0m';
                                        @endphp
                                        <div class="text-xs text-gray-400 font-medium hidden sm:block">{{ $durationStr }}</div>

                                        {{-- Status Badge --}}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border
                                        bg-{{ $log->status_color }}-100 text-{{ $log->status_color }}-800 border-{{ $log->status_color }}-200">
                                            {{ $log->status_label }}
                                        </span>

                                        {{-- Dropdown Menu  --}}
                                        @if($log->status !== 'approved')
                                            <div class="relative ml-1" x-data="{ open: false }" @click.outside="open = false">
                                                <button @click="open = !open" type="button" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                                </button>

                                                <div x-show="open"
                                                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="absolute right-0 z-20 mt-2 w-36 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" style="display: none;">

                                                    <div class="py-1">

                                                        {{-- TOMBOL EDIT --}}
                                                        <button type="button"
                                                                @if($registration->application_status == 'completed')
                                                                    disabled
                                                                class="flex items-center w-full px-4 py-2 text-xs text-gray-300 cursor-not-allowed"
                                                                @else
                                                                    @click="currentLogbook = {{ $log->toJson() }};
                                                                    if(currentLogbook.start_time) currentLogbook.start_time = currentLogbook.start_time.substring(0, 5);
                                                                    if(currentLogbook.end_time) currentLogbook.end_time = currentLogbook.end_time.substring(0, 5);
                                                                    editUrl = '{{ route('mahasiswa.logbook.update', $log->id) }}';
                                                                    modalLogbookEdit = true;
                                                                    open = false;"
                                                                class="flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors"
                                                            @endif
                                                        >
                                                            <svg class="w-3.5 h-3.5 mr-2 {{ $registration->application_status == 'completed' ? 'text-gray-300' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                            Edit
                                                        </button>

                                                        {{-- TOMBOL HAPUS --}}
                                                        <button type="button"
                                                                @if($registration->application_status == 'completed')
                                                                    disabled
                                                                class="flex w-full items-center px-4 py-2 text-xs text-gray-300 cursor-not-allowed"
                                                                @else
                                                                    @click="currentLogbook = {{ $log->toJson() }}; deleteUrl = '{{ route('mahasiswa.logbook.destroy', $log->id) }}'; modalLogbookDelete = true; open = false;"
                                                                class="flex w-full items-center px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors"
                                                            @endif
                                                        >
                                                            <svg class="w-3.5 h-3.5 mr-2 {{ $registration->application_status == 'completed' ? 'text-gray-300' : 'text-red-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            Hapus
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-8"></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-2xl bg-white">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Belum ada kegiatan</h3>
                        <p class="text-gray-500 mt-2 max-w-xs mx-auto">Catat aktivitas magangmu setiap hari agar pembimbing bisa memantau progresmu.</p>
                        <button @click="modalLogbookAdd = true" class="mt-6 inline-flex items-center px-6 py-2.5 bg-[#1B2A52] text-white font-medium rounded-lg hover:bg-blue-900 transition-all shadow-sm">
                            Mulai Mencatat
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- MODAL TAMBAH LOGBOOK --}}
            <div x-show="modalLogbookAdd" style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div @click.away="modalLogbookAdd = false" class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <form action="{{ route('mahasiswa.logbook.store') }}" method="POST">
                        @csrf

                        <div class="p-6 space-y-5">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                                <h3 class="text-xl font-bold text-gray-900">Isi Logbook Kegiatan</h3>
                                <button type="button" @click="modalLogbookAdd = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="date" value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('date') border-red-500 @enderror">
                                @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}"
                                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('start_time') border-red-500 @enderror">
                                    @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time', '17:00') }}"
                                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('end_time') border-red-500 @enderror">
                                    @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan</label>
                                <input type="text" name="activity" value="{{ old('activity') }}" placeholder="Contoh: Rapat Progres"
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('activity') border-red-500 @enderror">
                                @error('activity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea name="description" rows="3" placeholder="Jelaskan apa yang Anda kerjakan..."
                                          class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="modalLogbookAdd = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-[#1B2A52] text-white font-medium rounded-lg shadow-sm hover:bg-blue-900">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL EDIT LOGBOOK --}}
            <div x-show="modalLogbookEdit" style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div @click.away="modalLogbookEdit = false" class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <form x-bind:action="editUrl" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-6 space-y-5">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                                <h3 class="text-xl font-bold text-gray-900">Edit Logbook Kegiatan</h3>
                                <button type="button" @click="modalLogbookEdit = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" name="date" x-model="currentLogbook.date"
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('date') border-red-500 @enderror">
                                @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                                    <input type="time" name="start_time" x-model="currentLogbook.start_time"
                                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('start_time') border-red-500 @enderror">
                                    @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                                    <input type="time" name="end_time" x-model="currentLogbook.end_time"
                                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('end_time') border-red-500 @enderror">
                                    @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan</label>
                                <input type="text" name="activity" x-model="currentLogbook.activity" placeholder="Contoh: Rapat Progres"
                                       class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('activity') border-red-500 @enderror">
                                @error('activity') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea name="description" rows="3" x-model="currentLogbook.description" placeholder="Jelaskan apa yang Anda kerjakan..."
                                          class="block w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1B2A52] focus:ring-[#1B2A52] @error('description') border-red-500 @enderror"></textarea>
                                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" @click="modalLogbookEdit = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-[#1B2A52] text-white font-medium rounded-lg shadow-sm hover:bg-blue-900">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- MODAL DELETE LOGBOOK --}}
            <div x-show="modalLogbookDelete" style="display: none;"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                <div @click.away="modalLogbookDelete = false" class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <form x-bind:action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="p-6 text-center">
                            <svg class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <h3 class="mt-4 text-lg font-semibold text-gray-900">Hapus Logbook?</h3>
                            <p class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus logbook kegiatan "<span x-text="currentLogbook ? currentLogbook.activity : ''" class="font-medium text-gray-700"></span>" ini? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-center gap-3">
                            <button type="button" @click="modalLogbookDelete = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-medium rounded-lg shadow-sm hover:bg-red-700">
                                Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
