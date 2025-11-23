@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50 pb-20">

        {{-- HEADER --}}
        <div class="border-b border-gray-200 pt-8 pb-6">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-4">
                    <a href="{{ route('pembimbing.penilaian.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" /></svg>
                        Batal & Kembali
                    </a>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Input Nilai Akhir</h1>
                <p class="text-gray-500 mt-1">Silakan berikan penilaian objektif untuk setiap anggota tim berdasarkan kinerja mereka.</p>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <form action="{{ route('pembimbing.penilaian.store', $registration->id) }}" method="POST">
                @csrf

                <div class="space-y-12">

                    @foreach($membersData as $student)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                            {{-- HEADER KARTU MAHASISWA --}}
                            <div class="bg-gray-50/50 px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-[#1B2A52] text-white flex items-center justify-center text-lg font-bold shadow-sm">
                                        {{ substr($student->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                            {{ $student->name }}
                                            @if($student->role == 'Ketua')
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wide">Ketua Tim</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wide">Anggota</span>
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-500 font-mono">{{ $student->nim }}</p>
                                    </div>
                                </div>

                                {{-- DATA PENDUKUNG --}}
                                <div class="flex gap-3">
                                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-center shadow-sm">
                                        <span class="block text-[10px] uppercase font-bold text-gray-400">Kehadiran</span>
                                        <span class="block text-sm font-bold text-gray-800">{{ $student->attendance_count }} Hari</span>
                                    </div>
                                    <div class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-center shadow-sm">
                                        <span class="block text-[10px] uppercase font-bold text-gray-400">Logbook</span>
                                        <span class="block text-sm font-bold text-gray-800">{{ $student->logbook_count }} Keg</span>
                                    </div>
                                </div>
                            </div>

                            {{-- FORM INPUT NILAI --}}
                            <div class="p-6 sm:p-8">
                                {{-- Key unik untuk array input: 'ketua' atau ID member --}}
                                @php $key = $student->id ?? 'ketua'; @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                                    {{-- Disiplin --}}
                                    <div>
                                        <label class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                            Disiplin Kerja
                                            <span class="text-xs text-gray-400 font-normal">(0-100)</span>
                                        </label>
                                        <input type="number" name="scores[{{ $key }}][discipline]"
                                               value="{{ $student->existing_score->score_discipline ?? '' }}"
                                               class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] transition-shadow"
                                               placeholder="Nilai..." min="0" max="100" required>
                                    </div>

                                    {{-- Teknis --}}
                                    <div>
                                        <label class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                            Kemampuan Teknis
                                            <span class="text-xs text-gray-400 font-normal">(0-100)</span>
                                        </label>
                                        <input type="number" name="scores[{{ $key }}][technical]"
                                               value="{{ $student->existing_score->score_technical ?? '' }}"
                                               class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] transition-shadow"
                                               placeholder="Nilai..." min="0" max="100" required>
                                    </div>

                                    {{-- Prestasi --}}
                                    <div>
                                        <label class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                            Prestasi / Hasil Kerja
                                            <span class="text-xs text-gray-400 font-normal">(0-100)</span>
                                        </label>
                                        <input type="number" name="scores[{{ $key }}][performance]"
                                               value="{{ $student->existing_score->score_performance ?? '' }}"
                                               class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] transition-shadow"
                                               placeholder="Nilai..." min="0" max="100" required>
                                    </div>

                                    {{-- Inisiatif --}}
                                    <div>
                                        <label class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                            Inisiatif & Kreativitas
                                            <span class="text-xs text-gray-400 font-normal">(0-100)</span>
                                        </label>
                                        <input type="number" name="scores[{{ $key }}][initiative]"
                                               value="{{ $student->existing_score->score_initiative ?? '' }}"
                                               class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] transition-shadow"
                                               placeholder="Nilai..." min="0" max="100" required>
                                    </div>

                                    {{-- Kepribadian --}}
                                    <div class="md:col-span-2">
                                        <label class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                            Kepribadian & Sikap
                                            <span class="text-xs text-gray-400 font-normal">(0-100)</span>
                                        </label>
                                        <input type="number" name="scores[{{ $key }}][personality]"
                                               value="{{ $student->existing_score->score_personality ?? '' }}"
                                               class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] transition-shadow"
                                               placeholder="Nilai..." min="0" max="100" required>
                                    </div>

                                    {{-- Catatan --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Khusus (Opsional)</label>
                                        <textarea name="scores[{{ $key }}][notes]" rows="2"
                                                  class="w-full border-gray-300 rounded-lg focus:ring-[#1B2A52] focus:border-[#1B2A52] text-sm"
                                                  placeholder="Komentar untuk {{ $student->name }}...">{{ $student->existing_score->notes ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- FOOTER ACTION --}}
                <div class="mt-10 flex justify-end border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-500">Pastikan semua nilai sudah benar. Tindakan ini akan menyelesaikan status magang.</span>
                        <button type="submit" class="px-8 py-3 bg-[#1B2A52] text-white font-bold rounded-xl hover:bg-blue-900 shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Simpan Semua Nilai
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
