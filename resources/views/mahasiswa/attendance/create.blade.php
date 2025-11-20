@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- JUDUL --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Kehadiran Kerja Praktek</h1>
                <p class="text-gray-500 mt-1">Catat kehadiran harian Anda selama kegiatan magang.</p>
            </div>

            {{-- BAGIAN 1: FORM ABSEN HARI INI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 relative overflow-hidden">

                {{-- Header Hari Ini --}}
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Hari Ini</h2>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div class="text-3xl font-mono font-bold text-indigo-600">
                        {{ \Carbon\Carbon::now()->format('H:i') }}
                    </div>
                </div>

                @if($todayAttendance)
                    {{-- STATE: SUDAH ABSEN --}}
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-green-800">Anda Sudah Mengisi Kehadiran</h3>
                        <p class="text-green-600 mt-1">
                            Status: <span class="font-semibold">{{ $todayAttendance->status_label }}</span>
                            • Jam: {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-4 italic">"{{ $todayAttendance->notes }}"</p>
                    </div>

                @else
                    {{-- STATE: BELUM ABSEN (FORM) --}}
                    <form action="{{ route('mahasiswa.attendance.store') }}" method="POST" enctype="multipart/form-data" x-data="{ status: 'present' }">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Pilihan Status --}}
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700">Status Kehadiran</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="present" class="peer sr-only" x-model="status">
                                        <div class="rounded-lg border border-gray-200 p-3 text-center hover:bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition-all">
                                            <span class="block text-sm font-bold">Hadir</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="permission" class="peer sr-only" x-model="status">
                                        <div class="rounded-lg border border-gray-200 p-3 text-center hover:bg-gray-50 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 transition-all">
                                            <span class="block text-sm font-bold">Izin</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="sick" class="peer sr-only" x-model="status">
                                        <div class="rounded-lg border border-gray-200 p-3 text-center hover:bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all">
                                            <span class="block text-sm font-bold">Sakit</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- Upload Bukti --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bukti Foto <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <input type="file" name="proof_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                                <p class="text-xs text-gray-400 mt-1">Screenshot pekerjaan atau surat dokter.</p>
                            </div>

                            {{-- Keterangan --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan <span x-show="status == 'present'">(Aktivitas Hari Ini)</span><span x-show="status != 'present'">(Alasan)</span>
                                </label>
                                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Melanjutkan pengerjaan modul login..." required></textarea>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-[#1B2A52] text-white font-medium rounded-lg shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                Kirim Kehadiran
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- BAGIAN 2: RIWAYAT KEHADIRAN --}}
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Kehadiran</h3>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jam</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Bukti</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($history as $h)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($h->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    {{ \Carbon\Carbon::parse($h->check_in_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($h->status == 'present')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Hadir</span>
                                    @elseif($h->status == 'permission')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Izin</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Sakit</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ Str::limit($h->notes, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($h->proof_file)
                                        <a href="{{ Storage::url($h->proof_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 hover:underline">Lihat</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada riwayat kehadiran.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $history->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
