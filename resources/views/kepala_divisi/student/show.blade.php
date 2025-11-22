@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">

        {{-- HEADER (CLEAN STYLE) --}}
        <div class="border-b border-gray-200 ">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                {{-- Breadcrumb --}}
                <div class="mb-6">
                    <a href="{{ route('kadiv.mahasiswa.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#1B2A52] transition-colors text-sm font-medium group">
                        <div class="mr-2 p-1.5 rounded-full bg-gray-50 group-hover:bg-gray-100 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </div>
                        Kembali ke Data Mahasiswa
                    </a>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start gap-6">

                    {{-- Identitas Utama --}}
                    <div class="flex items-start gap-5">
                        {{-- Avatar --}}
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-[#1B2A52] text-white flex items-center justify-center text-2xl md:text-3xl font-bold shadow-lg shadow-indigo-900/10 flex-shrink-0 ring-4 ring-white">
                            {{ substr($registration->student->name, 0, 2) }}
                        </div>

                        <div class="pt-1">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight leading-tight mb-2">
                                {{ $registration->student->name }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-sm text-gray-500">
                                <div class="flex items-center gap-1.5 bg-gray-100 px-2.5 py-0.5 rounded border border-gray-200 text-gray-700 font-mono">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                    {{ $registration->student->nim }}
                                </div>
                                <span class="hidden sm:inline text-gray-300">|</span>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <span class="font-medium">{{ $registration->student->university }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    <div class="self-start md:self-center">
                        @if($registration->application_status == 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-600 animate-pulse"></span> Sedang Magang
                        </span>
                        @elseif($registration->application_status == 'completed')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200 shadow-sm">
                            Selesai
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            {{-- INFORMASI PRIBADI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Informasi Pribadi</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">Email</p><p class="text-sm font-medium text-gray-900">{{ $registration->student->email }}</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg></div>
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">No. HP</p><p class="text-sm font-medium text-gray-900">{{ $registration->student->phone_number ?? '-' }}</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg></div>
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">Program Studi</p><p class="text-sm font-medium text-gray-900">{{ $registration->student->study_program }}</p></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. DETAIL MAGANG & KELOMPOK --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">

                {{-- Header Card --}}
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Penempatan & Periode</h4>
                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                            <span class="font-medium text-[#1B2A52]">{{ $registration->division->name }}</span>
                            <span class="text-gray-300">|</span>
                            <span>{{ \Carbon\Carbon::parse($registration->start_date)->format('d M Y') }}</span>
                            <span>-</span>
                            <span>{{ \Carbon\Carbon::parse($registration->end_date)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Pembimbing</p>
                        <p class="text-sm font-bold text-gray-800">{{ $registration->mentor->name ?? 'Belum ditentukan' }}</p>
                    </div>
                </div>

                {{-- DAFTAR ANGGOTA TIM (Jika Ada) --}}
                <div class="p-6 bg-gray-50/30">

                    @php
                        $otherMembers = $registration->members->filter(function($m) use ($registration) {
                            return $m->nim !== $registration->student->nim;
                        });
                    @endphp

                    @if($otherMembers->count() > 0)
                        <div class="mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Anggota Tim Lainnya</h5>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50 text-gray-500 text-xs">
                                <tr>
                                    <th class="px-4 py-2.5 font-bold text-left uppercase tracking-wider w-1/3">Nama</th>
                                    <th class="px-4 py-2.5 font-bold text-left uppercase tracking-wider">NIM</th>
                                    <th class="px-4 py-2.5 font-bold text-left uppercase tracking-wider">Program Studi</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm">
                                @foreach($otherMembers as $member)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $member->name }}</td>
                                        <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $member->nim }}</td>
                                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $member->study_program }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Jika Sendirian --}}
                        <div class="flex items-center justify-center gap-2 p-4 border-2 border-dashed border-gray-200 rounded-xl text-gray-400 bg-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <span class="text-sm font-medium">Mahasiswa ini magang secara individu (Tidak ada anggota tim).</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- STATISTIK AKTIVITAS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Logbook</h4>
                        <span class="text-xs text-gray-500">Total Kegiatan</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-[#1B2A52]">{{ $totalLogbook }}</span>
                        <span class="text-sm text-gray-500">Entri</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Kehadiran</h4>
                        <span class="text-xs text-gray-500">Total Hadir</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-green-600">{{ $totalAttendance }}</span>
                        <span class="text-sm text-gray-500">Hari</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


