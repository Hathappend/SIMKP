@extends('layouts.app')

@section('content')

    <div x-data="{
        modalTambah: {{ $errors->store->any() ? 'true' : 'false' }},
        modalEdit: {{ $errors->update->any() ? 'true' : 'false' }},
        modalHapus: false,
        selectedDivisi: {{ $errors->update->any() ? json_encode(['name' => old('name')]) : '{}' }},
        deleteUrl: '',
        editUrl: '{{ $errors->update->any() ? old('original_url', route('admin.divisi.update', ['division' => old('id')])) : '' }}'
     }"
         class="md:mx-12 mt-10 mb-16">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Kelola Divisi</h1>
                <p class="mt-1 text-lg text-gray-600">Tambah, edit, atau hapus divisi yang tersedia untuk magang.</p>
            </div>
            <div>
                <button
                    @click="modalTambah = true" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    Tambah Divisi
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <table class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Divisi</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                {{-- Loop data divisi (asumsi $divisions dari controller) --}}
                @forelse ($divisions as $index => $division)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $division->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-right flex items-center justify-end gap-3">

                            <button
                                @click="
                                    modalEdit = true;
                                    selectedDivisi = {{ json_encode($division) }};
                                    editUrl = '{{ route('admin.divisi.update', $division->id) }}';
                                "
                                class="p-2 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 hover:border-gray-300 transition-all duration-150"
                                title="Edit Divisi">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-gray-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12.572l-4.903 4.903" />
                                </svg>
                            </button>

                            <button
                                @click="
                                    modalHapus = true;
                                    selectedDivisi = {{ json_encode($division) }};
                                    deleteUrl = '{{ route('admin.divisi.destroy', $division->id) }}';
                                "
                                class="p-2 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-150"
                                title="Hapus Divisi">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-5 h-5 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12.54 0c-.27-0.042-.53-.082-.79-.118l.79-.118A48.108 48.108 0 0112 4.5c4.756 0 9.242 1.256 12.834 3.39m-12.834 3.39L10.5 3.19c.09-.3.028-.617-.18-.842a1.12 1.12 0 00-1.424.062L7.25 4.5l-2.036-1.554a1.125 1.125 0 00-1.424-.062 1.125 1.125 0 00-.18.842l.79 4.39z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-10">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="h-16 w-16 text-gray-400 opacity-70 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                                <p class="text-lg font-semibold">Belum Ada Divisi</p>
                                <p class="text-sm text-gray-400 mt-1">Klik tombol "Tambah Divisi" untuk membuat data baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div> <div
            x-show="modalTambah"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
            style="display: none;"
        >
            <div @click.away="modalTambah = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                <form action="{{ route('admin.divisi.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="form_action" value="store">

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Tambah Divisi Baru</h3>
                        <p class="text-gray-600 mb-4">Masukkan nama untuk divisi baru yang akan menerima pengajuan magang.</p>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi</label>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name') }}"
                                   class="w-full p-3 border rounded-lg @error('name', 'store') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror" placeholder="Contoh: Aplikasi Informatika" required>

                            @error('name', 'store')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalTambah = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div
            x-show="modalEdit"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
            style="display: none;"
        >
            <div @click.away="modalEdit = false" class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                <form :action="editUrl" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" :value="selectedDivisi.id">
                    <input type="hidden" name="form_action" value="update">
                    <input type="hidden" name="original_url" :value="editUrl">

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Edit Divisi</h3>
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi</label>
                            <input type="text" id="edit_name" name="name"
                                   value="{{ old('name') }}"
                                   :value="{{ $errors->update->any() ? old('name') : 'selectedDivisi.name' }}"
                                   class="w-full p-3 border rounded-lg
                                  @error('name', 'update') border-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:ring-blue-500 focus:border-blue-500 @enderror"
                                   required>

                            @error('name', 'update')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button @click="modalEdit = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div
            x-show="modalHapus"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
            style="display: none;"
        >
            <div @click.away="modalHapus = false" class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
                <form :action="deleteUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="text-xl font-semibold text-gray-900">Hapus Divisi?</h3>
                                <p class="text-gray-600 mt-2">
                                    Anda yakin ingin menghapus divisi <strong x-text="selectedDivisi.name"></strong>? Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="inline-flex justify-center px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                            Ya, Hapus
                        </button>
                        <button @click="modalHapus = false" type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> @endsection
