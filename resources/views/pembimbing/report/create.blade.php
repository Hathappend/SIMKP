@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- HEADER --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Validasi Laporan Akhir</h1>
                    <p class="text-gray-500 mt-1">Periksa dokumen laporan akhir mahasiswa bimbingan Anda.</p>
                </div>

                {{-- Statistik --}}
                <div class="flex gap-3">
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase">Menunggu</span>
                        <span class="block text-lg font-bold text-blue-600">{{ $reports->where('report_status', 'submitted')->count() }}</span>
                    </div>
                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase">Selesai</span>
                        <span class="block text-lg font-bold text-green-600">{{ $reports->where('report_status', 'approved')->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- PRIORITY INBOX Card --}}
            <div class="mb-12">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                    Perlu Tindakan Segera
                </h2>

                @if($pendingReports->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pendingReports as $reg)
                            <div class="bg-white rounded-xl border border-blue-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>

                                <div class="p-5 pl-7">
                                    <div class="flex justify-between items-start mb-3">
                                        {{-- Info User --}}
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold border border-blue-100">
                                                {{ substr($reg->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $reg->student->name }}</h4>
                                                <p class="text-xs text-gray-500">{{ $reg->student->nim }}</p>
                                            </div>
                                        </div>
                                        {{-- Badge --}}
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wide rounded">
                                        Baru
                                    </span>
                                    </div>

                                    <div class="mb-4">
                                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Waktu Upload</p>
                                        <p class="text-sm text-gray-700 font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ $reg->updated_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <a href="{{ route('pembimbing.laporan.show', $reg->id) }}" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-center text-sm font-bold rounded-lg transition-colors shadow-sm shadow-blue-200">
                                        Review Laporan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 text-green-500 mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-gray-900 font-bold">Semua Beres!</h3>
                        <p class="text-sm text-gray-500">Tidak ada laporan baru yang perlu direview saat ini.</p>
                    </div>
                @endif
            </div>

            {{-- HISTORY Tabel --}}
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Riwayat Laporan
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Terakhir Update</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nilai</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @forelse($historyReports as $reg)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold mr-3">
                                            {{ substr($reg->student->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $reg->student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $reg->student->university }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $reg->updated_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$reg->report_status)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Belum Upload
                                        </span>
                                    @elseif($reg->report_status == 'revision')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                            Revisi
                                        </span>
                                    @elseif($reg->report_status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                            Disetujui
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    {{ $reg->assessment->grade ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('pembimbing.laporan.show', $reg->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase tracking-wide hover:underline">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500 text-sm">
                                    Belum ada riwayat laporan lainnya.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
