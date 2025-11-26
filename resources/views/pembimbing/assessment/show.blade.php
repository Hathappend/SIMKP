@extends('layouts.app')
@section('title', "Detail Hasil Penilaian")
@section('content')
    <div class="min-h-screen bg-gray-50">

        {{-- HEADER --}}
        <div class=" border-b border-gray-200 ">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pembimbing.penilaian.index') }}" class="p-2 rounded-full hover:bg-gray-100 transition text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7" /></svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">Hasil Penilaian Magang</h1>
                        <p class="text-xs text-gray-500">Detail nilai akhir dan evaluasi.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

            {{-- DATA TIM / MAHASISWA --}}
            @php
                $studentsList = collect([]);

                // Ketua
                $studentsList->push((object)[
                    'name' => $registration->student->name,
                    'nim' => $registration->student->nim,
                    'role' => 'Ketua Tim',
                    'assessment' => $registration->assessment
                ]);

                // Anggota
                foreach($registration->members as $member) {
                    $score = \App\Models\Assessment::where('registration_id', $registration->id)
                                ->where('registration_member_id', $member->id)
                                ->first();

                    $studentsList->push((object)[
                        'name' => $member->name,
                        'nim' => $member->nim,
                        'role' => 'Anggota',
                        'assessment' => $score
                    ]);
                }
            @endphp

            @foreach($studentsList as $student)
                @if($student->assessment)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                    {{-- Header Kartu --}}
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#1B2A52] text-white flex items-center justify-center font-bold text-sm">
                                {{ substr($student->name, 0, 2) }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ $student->name }}</h3>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span class="font-mono">{{ $student->nim }}</span>
                                    <span>•</span>
                                    <span class="px-2 py-0.5 rounded bg-gray-200 text-gray-600 font-semibold text-[10px] uppercase">{{ $student->role }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Nilai Akhir Besar --}}
                        <div class="text-right">
                            <span class="block text-3xl font-bold text-[#1B2A52]">{{ $student->assessment->grade }}</span>
                            <span class="text-xs text-gray-500 font-medium">Score: {{ $student->assessment->final_score }}</span>
                        </div>
                    </div>

                    {{-- Detail Nilai --}}
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center mb-6">
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Disiplin</div>
                                <div class="font-bold text-gray-800 text-lg">{{ $student->assessment->score_discipline }}</div>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Teknis</div>
                                <div class="font-bold text-gray-800 text-lg">{{ $student->assessment->score_technical }}</div>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Prestasi</div>
                                <div class="font-bold text-gray-800 text-lg">{{ $student->assessment->score_performance }}</div>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Inisiatif</div>
                                <div class="font-bold text-gray-800 text-lg">{{ $student->assessment->score_initiative }}</div>
                            </div>
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Sikap</div>
                                <div class="font-bold text-gray-800 text-lg">{{ $student->assessment->score_personality }}</div>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        @if($student->assessment->notes)
                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
                                <p class="text-xs font-bold text-yellow-700 uppercase mb-1">Catatan Pembimbing</p>
                                <p class="text-sm text-yellow-900 italic">"{{ $student->assessment->notes }}"</p>
                            </div>
                        @endif
                    </div>

                </div>
                @endif
            @endforeach

        </div>
    </div>
@endsection
