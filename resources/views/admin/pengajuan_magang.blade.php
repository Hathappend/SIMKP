@extends('layouts.app')

@section('content')

    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Pengajuan Magang</h1>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Universitas</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Divisi Tujuan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Endang Sudrajat</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10231223</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">UNIKOM</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Teknik Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Aplikasi Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11 Nov 2025</td>
                <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Menunggu Verifikasi
                        </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button class="text-green-600 hover:text-green-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </td>
            </tr>

            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Endang Sudrajat</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10231223</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">UNIKOM</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Teknik Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Aplikasi Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11 Nov 2025</td>
                <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Disetujui
                        </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z"></path></svg>
                    </button>
                    <button class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>

            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Endang Sudrajat</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10231223</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">UNIKOM</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Teknik Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Aplikasi Informatika</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11 Nov 2025</td>
                <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Ditolak
                        </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                    -
                </td>
            </tr>

            </tbody>
        </table>
    </div>

@endsection
