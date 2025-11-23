@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30"
         x-data="{
            modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
            modalHapus: false,

            modalEditMember: false,
            modalHapusMember: false,
            selectedMember: { id: '', name: '', nim: '', study_program: '' },

            updateMemberUrl: '',
            deleteMemberUrl: ''
         }">

        {{-- HEADER PROFIL --}}
        <div class="border-b border-gray-200 ">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

                {{-- Breadcrumb --}}
                <div class="mb-6">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#1B2A52] transition-colors text-sm font-medium group">
                        <div class="mr-2 p-1.5 rounded-full bg-gray-50 group-hover:bg-gray-100 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                        </div>
                        Kembali ke Data Mahasiswa
                    </a>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                    {{-- Identitas Utama --}}
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-[#1B2A52] text-white flex items-center justify-center text-2xl md:text-3xl font-bold shadow-lg shadow-indigo-900/10 flex-shrink-0 ring-4 ring-white">
                            {{ substr($student->name, 0, 2) }}
                        </div>

                        <div class="pt-1">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight leading-tight mb-2">
                                {{ $student->name }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-sm text-gray-500">
                                <div class="flex items-center gap-1.5 bg-gray-100 px-2.5 py-0.5 rounded border border-gray-200 text-gray-700 font-mono">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                    {{ $student->nim }}
                                </div>
                                <span class="hidden sm:inline text-gray-300">|</span>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <span class="font-medium">{{ $student->university }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Utama --}}
                    <div class="self-start md:self-center flex items-center gap-3">
                        <button @click="modalEdit = true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            Edit Profil
                        </button>
                        <button @click="modalHapus = true" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
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
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">Email</p><p class="text-sm font-medium text-gray-900">{{ $student->email }}</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-50 text-green-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg></div>
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">No. HP</p><p class="text-sm font-medium text-gray-900">{{ $student->phone_number ?? '-' }}</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg></div>
                            <div><p class="text-xs text-gray-500 font-bold uppercase mb-0.5">Program Studi</p><p class="text-sm font-medium text-gray-900">{{ $student->study_program }}</p></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. RIWAYAT MAGANG --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    Riwayat Magang & Kelompok
                </h3>

                @forelse($student->registrations as $reg)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6 transition hover:shadow-md">

                        {{-- HEADER CARD MAGANG --}}
                        <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base font-bold text-gray-900">{{ $reg->division->name ?? 'Divisi Dihapus' }}</h4>
                                    {{-- Status Badge --}}
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border
                                    {{ $reg->application_status == 'approved' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                    {{ $reg->application_status == 'completed' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $reg->application_status == 'rejected' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                    {{ in_array($reg->application_status, ['pending', 'waiting']) ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}">
                                    {{ $reg->application_status }}
                                </span>
                                </div>

                                <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                                    </svg>

                                    <span>{{ \Carbon\Carbon::parse($reg->start_date)->format('d M Y') }}</span>
                                    <span class="text-gray-300">-</span>
                                    <span>{{ \Carbon\Carbon::parse($reg->end_date)->format('d M Y') }}</span>
                                </div>
                            </div>

                            {{-- NILAI KETUA --}}
                            @if($reg->assessment)
                                <div class="flex items-center bg-yellow-50 border border-yellow-100 rounded-lg p-2 pr-4">
                                    <div class="w-10 h-10 bg-white rounded-md flex items-center justify-center text-lg font-bold text-yellow-700 shadow-sm border border-yellow-100 mr-3">
                                        {{ $reg->assessment->grade }}
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-yellow-600 uppercase">Nilai Ketua</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $reg->assessment->final_score }} <span class="text-xs font-normal text-gray-500">/ 100</span></p>
                                    </div>

                                    @if($reg->assessment->certificate_path)
                                        <a href="{{ Storage::url($reg->assessment->certificate_path) }}" target="_blank" class="ml-3 p-1.5 bg-white rounded hover:bg-yellow-100 text-yellow-600 transition-colors border border-yellow-200" title="Download Sertifikat">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12" /></svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- ANGGOTA TIM  --}}
                        <div class="p-6 bg-gray-50/30">
                            @php
                                $realMembers = $reg->members->filter(fn($m) => $m->nim !== $student->nim);
                            @endphp

                            @if($realMembers->count() > 0)
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Anggota Tim Lainnya</h5>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50 text-gray-500 text-xs">
                                        <tr>
                                            <th class="px-4 py-2 font-semibold text-left">Nama Anggota</th>
                                            <th class="px-4 py-2 font-semibold text-left">NIM</th>
                                            <th class="px-4 py-2 font-semibold text-left">Prodi</th>
                                            <th class="px-4 py-2 font-semibold text-center">Nilai</th>
                                            <th class="px-4 py-2 font-semibold text-right">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-sm">
                                        @foreach($realMembers as $member)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $member->name }}</td>
                                                <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $member->nim }}</td>
                                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $member->study_program }}</td>

                                                <td class="px-4 py-3 text-center">
                                                    @if($member->assessment)
                                                        <div class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 px-2 py-0.5 rounded border border-green-100">
                                                            <span class="font-bold">{{ $member->assessment->grade }}</span>
                                                            <span class="text-[10px] opacity-70">({{ $member->assessment->final_score }})</span>

                                                            @if($member->assessment->certificate_path)
                                                                <a href="{{ Storage::url($member->assessment->certificate_path) }}" target="_blank" class="ml-1 text-green-600 hover:text-green-800">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0L8 8m4-4v12" /></svg>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-300 text-xs">-</span>
                                                    @endif
                                                </td>

                                                <td class="px-4 py-3 text-right">
                                                    <button type="button"
                                                            @click='
                                                            modalEditMember = true;
                                                            selectedMember = @json($member);
                                                            updateMemberUrl = "{{ route("admin.members.update", $member->id) }}";'
                                                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-all" title="edit">
                                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                    Edit
                                                    </button>

                                                    <button
                                                        @click='
                                                            modalHapusMember = true;
                                                            selectedMember = @json($member);
                                                            deleteMemberUrl = "{{ route("admin.members.destroy", $member->id) }}"; '

                                                        class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 shadow-sm transition-all">
                                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Magang Individu (Tidak ada anggota tambahan)
                                </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white border-2 border-dashed border-gray-300 rounded-xl">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <p class="text-gray-500 font-medium">Belum ada riwayat magang.</p>
                    </div>
                @endforelse

            </div>

        </div>


        {{-- MODAL EDIT MAHASISWA (KETUA) --}}
        <div x-show="modalEdit" style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 backdrop-blur-sm"
             x-transition.opacity>

            <div @click.away="modalEdit = false"
                 class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all"
                 x-show="modalEdit"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <form action="{{ route('admin.mahasiswa.update', $student->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Edit Biodata Ketua</h3>
                        <button type="button" @click="modalEdit = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $student->name) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                                <input type="text" name="nim" value="{{ old('nim', $student->nim) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $student->phone_number) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $student->email) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Universitas</label>
                            <input type="text" name="university" value="{{ old('university', $student->university) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <input type="text" name="study_program" value="{{ old('study_program', $student->study_program) }}" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button type="button" @click="modalEdit = false" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-white">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#1B2A52] text-white rounded-lg text-sm font-medium hover:bg-blue-900 shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL HAPUS (KETUA) --}}
        <div x-show="modalHapus" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 backdrop-blur-sm" x-transition.opacity>
            <div @click.away="modalHapus = false" class="w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden">
                <form action="{{ route('admin.mahasiswa.destroy', $student->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Hapus Mahasiswa?</h3>
                        <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus data <strong>{{ $student->name }}</strong>?</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-center gap-3">
                        <button type="button" @click="modalHapus = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 shadow-sm">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT ANGGOTA  --}}
        <div x-show="modalEditMember" style="display: none;"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/50 backdrop-blur-[2px]"
             x-transition.opacity>

            <div @click.away="modalEditMember = false" class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden transition-all"
                 x-show="modalEditMember"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <form :action="updateMemberUrl" method="POST">
                    @csrf @method('PUT')

                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Edit Anggota Tim</h3>
                        <button type="button" @click="modalEditMember = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- NAMA LENGKAP --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="selectedMember.name"
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- NIM --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">NIM</label>
                                <input type="text" name="nim" x-model="selectedMember.nim"
                                       class="w-full border-gray-300 rounded-lg text-sm font-mono focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                            </div>

                            {{-- PROGRAM STUDI --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Program Studi</label>
                                <input type="text" name="study_program" x-model="selectedMember.study_program"
                                       class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#1B2A52] focus:border-[#1B2A52]" required>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="modalEditMember = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#1B2A52] text-white rounded-lg text-sm font-bold hover:bg-blue-900 shadow-sm transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL HAPUS ANGGOTA --}}
        <div x-show="modalHapusMember" style="display: none;"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/50 backdrop-blur-[2px]"
             x-transition.opacity>

            <div @click.away="modalHapusMember = false" class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all"
                 x-show="modalHapusMember"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">

                <form :action="deleteMemberUrl" method="POST">
                    @csrf @method('DELETE')

                    <div class="p-6 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Hapus Anggota?</h3>
                        <p class="text-sm text-gray-500 mt-2">
                            Anda yakin ingin menghapus anggota <strong x-text="selectedMember.name"></strong>?
                            <br><span class="text-xs text-red-500">(Data Ketua tidak akan terhapus)</span>
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-center gap-3">
                        <button type="button" @click="modalHapusMember = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-sm transition-colors">
                            Ya, Hapus Anggota
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
