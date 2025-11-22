@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER & FILTER --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
                <div class="w-full md:w-auto">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Mahasiswa Divisi</h1>
                    <p class="text-gray-500 mt-1">Daftar peserta magang aktif dan alumni di divisi Anda.</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    @php
                        $fields = ['name', 'university', 'mentor_name'];
                        $filters = [
                            ['key' => 'status', 'label' => 'Status', 'type' => 'checkbox-list', 'open'=>true, 'options' => [
                                ['value' => 'approved', 'label' => 'Aktif Magang'],
                                ['value' => 'completed', 'label' => 'Selesai (Alumni)'],
                            ]]
                        ];
                    @endphp
                    <x-filter-bar :fields="$fields" :filters="$filters" table="#kadivStudentTable" />
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table id="kadivStudentTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Pembimbing</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($students as $reg)
                        <tr class="hover:bg-gray-50 transition-colors group"
                            data-name="{{ strtolower($reg->student->name) }}"
                            data-university="{{ strtolower($reg->student->university) }}"
                            data-mentor_name="{{ strtolower($reg->mentor->name ?? '-') }}"
                            data-status="{{ $reg->application_status }}"
                        >

                            {{-- Mahasiswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold mr-3 border border-blue-100">
                                        {{ substr($reg->student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $reg->student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reg->student->university }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Pembimbing --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-800">{{ $reg->mentor->name ?? 'Belum ditentukan' }}</div>
                                <div class="text-xs text-gray-500">{{ $reg->mentor->position ?? '-' }}</div>
                            </td>

                            {{-- Periode --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($reg->start_date)->format('d M') }} -
                                {{ \Carbon\Carbon::parse($reg->end_date)->format('d M Y') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4 text-center">
                                @if($reg->application_status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @elseif($reg->application_status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('kadiv.mahasiswa.show', $reg->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase tracking-wide hover:underline">
                                    Detail
                                </a>
                            </td>
                        </tr>

                        {{-- Pesan Data Kosong untuk Filter --}}
                        <tr id="noDataMessage" class="hidden">
                            <td colspan="100%" class="py-10">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <img src="{{ asset('images/empty.jpg') }}" class="h-24 opacity-70 mb-3" alt="">
                                    <p class="text-lg font-semibold">Data tidak ditemukan</p>
                                    <p class="text-sm text-gray-400 mt-1">Coba periksa kata kunci atau filter yang digunakan.</p>
                                </div>
                            </td>
                        </tr>


                    @empty
                        {{-- Pesan Data Kosong Database --}}
                        <tr>
                            <td colspan="100%" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-lg font-medium">Belum ada data mahasiswa.</p>
                                    <p class="text-sm text-gray-400">Saat ini belum ada mahasiswa yang mengajukan magang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
