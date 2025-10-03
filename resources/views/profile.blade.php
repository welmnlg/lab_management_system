@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')

<div class="space-y-6">

    {{-- KARTU INFORMASI PROFIL --}}
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex flex-col md:flex-row items-center gap-6">
            {{-- Foto Profil --}}
            <div class="flex-shrink-0">
                <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-200"
                    src="https://via.placeholder.com/150" alt="Foto Profil">
            </div>

            {{-- Detail Teks (Data Statis) --}}
            <div class="flex-grow text-center md:text-left">
                <h2 class="text-2xl font-bold text-gray-800 uppercase">AULIA HALIMATUSYADDIAH</h2>
                <p class="text-gray-600">NIM: 221402132</p>
                <p class="text-gray-600">Email: auliahalim217@gmail.com</p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex-shrink-0 mt-4 md:mt-0">
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H5v-2H3v-2H1l4.257-4.257A6 6 0 1121 9z">
                        </path>
                    </svg>
                    <span>Ganti Kata Sandi</span>
                </button>
            </div>
        </div>
    </div>


    {{-- KARTU JADWAL PRAKTIKUM --}}
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Praktikum Semester Ganjil T.A. 2025/2026</h2>

        {{-- Tab Hari --}}
        <div class="flex space-x-2 overflow-x-auto pb-2 mb-6">
            <button
                class="flex-shrink-0 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold text-sm">
                SENIN <span class="block text-xs font-normal">22 September</span>
            </button>
            <button
                class="flex-shrink-0 px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-sm hover:bg-gray-300">
                SELASA <span class="block text-xs font-normal">23 September</span>
            </button>
            {{-- ... tombol hari lainnya ... --}}
        </div>

        {{-- Daftar Jadwal --}}
        <div class="space-y-4">

            <!-- Jadwal 1: Sedang Berlangsung -->
            <div
                class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 border-green-500 shadow-sm">
                {{-- Bagian Kiri (Info) --}}
                <div class="flex-grow w-full">
                    <p class="font-bold text-gray-800">Mata Kuliah: Praktikum Web Semantik</p>
                    <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mt-2">
                        <span class="flex items-center gap-1.5 bg-gray-200 px-2 py-1 rounded-md"><svg class="w-4 h-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> 08:00 - 09:40</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">KOM B1</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">Lab Jaringan 1</span>
                    </div>
                    <div class="mt-3">
                        <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Sedang
                            Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div class="flex-shrink-0 flex sm:flex-col md:flex-row gap-2 w-full sm:w-auto">
                    <a href="{{ route('scanqr') }}"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90">
                        Pindah Ruangan
                    </a> <button
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90">Selesai</button>
                </div>
            </div>

            <!-- Jadwal 2: Akan Berlangsung -->
            <div
                class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 border-gray-400 shadow-sm">
                {{-- Bagian Kiri (Info) --}}
                <div class="flex-grow w-full">
                    <p class="font-bold text-gray-800">Mata Kuliah: Praktikum Web Semantik</p>
                    <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mt-2">
                        <span class="flex items-center gap-1.5 bg-gray-200 px-2 py-1 rounded-md"><svg class="w-4 h-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> 09:40 - 11:20</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">KOM B2</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">Lab Jaringan 2</span>
                    </div>
                    <div class="mt-3">
                        <span class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Akan
                            Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div class="flex-shrink-0 flex sm:flex-col md:flex-row gap-2 w-full sm:w-auto">
                    <button
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90">Batal</button>
                    <button
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90">Konfirmasi</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection