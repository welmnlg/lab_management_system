@extends('layouts.main')

@section('title', 'Ambil Jadwal - ITLG Lab Management System')

@section('content')
<div class="space-y-6 md:space-y-10">
    <!-- Status Terkunci (Default State) - DIUBAH POSISINYA -->
    <div id="status-terkunci" class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 min-h-[60vh] flex items-center justify-center">
        <div class="text-center py-8 md:py-12 w-full max-w-md mx-auto">
            <!-- Icon Kunci -->
            <div class="mx-auto w-16 h-16 md:w-20 md:h-20 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-lock text-white text-2xl md:text-3xl"></i>
            </div>
            
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-2">Fitur Ambil Jadwal Terkunci</h2>
            <p class="text-gray-600 mb-6 max-w-md mx-auto text-sm md:text-base">
                Saat ini periode pengambilan jadwal praktikum belum dibuka. 
                Silakan buka semester baru untuk memulai periode pengambilan jadwal.
            </p>
            
            <!-- Tombol Buka Semester Baru -->
            <button id="buka-semester-btn" class="bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 mx-auto transform hover:scale-105">
                <i class="fas fa-plus-circle"></i>
                <span>Buka Semester Baru</span>
            </button>
        </div>
    </div>

    <!-- Status Terbuka (Akan Ditampilkan Setelah Buka Semester) - TIDAK DIUBAH -->
    <div id="status-terbuka" class="hidden">
        <!-- Header Info Semester -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                <!-- Info Semester -->
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Periode Ambil Jadwal Sedang Berjalan</h2>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="font-semibold text-green-800 text-sm md:text-base" id="nama-semester">Ganjil 2024/2025</h3>
                                <p class="text-green-600 text-xs md:text-sm" id="periode-semester">
                                    Periode: 1 Sep 2024 - 20 Des 2024
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-green-600">
                                    Sisa waktu: 
                                    <span class="font-bold" id="sisa-waktu">45 hari</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container Lab dan Tabel (Diambil dari kode yang diberikan) -->
        <div class="space-y-4 md:space-y-4">
            <!-- Header dengan tombol lab dan button ambil jadwal -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
                <!-- Layout untuk Mobile -->
                <div class="block md:hidden">
                    <div class="space-y-3">
                        <!-- Baris 1: Lab 1 & Lab 2 -->
                        <div class="flex gap-2">
                            <button id="lab1-btn-mobile" class="lab-btn active bg-gradient-to-r from-blue-900 to-red-700 text-white px-3 py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex-1 text-center">
                                Lab Jaringan 1
                            </button>
                            <button id="lab2-btn-mobile" class="lab-btn bg-gray-200 text-gray-700 px-3 py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex-1 text-center hover:bg-gray-300">
                                Lab Jaringan 2
                            </button>
                        </div>
                        
                        <!-- Baris 2: Lab 3 & Lab 4 -->
                        <div class="flex gap-2">
                            <button id="lab3-btn-mobile" class="lab-btn bg-gray-200 text-gray-700 px-3 py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex-1 text-center hover:bg-gray-300">
                                Lab Jaringan 3
                            </button>
                            <button id="lab4-btn-mobile" class="lab-btn bg-gray-200 text-gray-700 px-3 py-3 rounded-lg font-semibold transition-all duration-200 text-sm flex-1 text-center hover:bg-gray-300">
                                Lab Jaringan 4
                            </button>
                        </div>
                        
                        <!-- Baris 3: Tombol Ambil Jadwal -->
                        <a href="{{ route('form-ambil-jadwal') }}" class="bg-[#1D518B] hover:bg-[#164376] text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full text-sm">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Ambil Jadwal Baru</span>
                        </a>
                    </div>
                </div>

                <!-- Layout untuk Desktop -->
                <div class="hidden md:flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <button id="lab1-btn" class="lab-btn active bg-gradient-to-r from-blue-900 to-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm">
                                Lab Jaringan 1
                            </button>
                            <button id="lab2-btn" class="lab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm hover:bg-gray-300">
                                Lab Jaringan 2
                            </button>
                            <button id="lab3-btn" class="lab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm hover:bg-gray-300">
                                Lab Jaringan 3
                            </button>
                            <button id="lab4-btn" class="lab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm hover:bg-gray-300">
                                Lab Jaringan 4
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('form-ambil-jadwal') }}" class="bg-[#1D518B] hover:bg-[#164376] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full lg:w-auto">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Ambil Jadwal Baru</span>
                    </a>
                </div>
            </div>

            <!-- Tabel Jadwal -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Container untuk semua lab -->
                <div id="lab-schedules">
                    <!-- Lab Jaringan 1 (Default Active) -->
                    <div id="lab1-schedule" class="lab-schedule active">
                        <div class="overflow-x-auto">
                            <div class="min-w-[800px]">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                            <th class="w-[120px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">Hari/Jam</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SENIN</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SELASA</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">RABU</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">KAMIS</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center text-xs md:text-sm">JUMAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris 08:00 - 09:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                08:00 - 09:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum IOT B1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Justin Bieber</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 09:40 - 11:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                09:40 - 11:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum SDA B2</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Cut Nabilah</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 11:20 - 13:00 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                11:20 - 13:00
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum Pemograman Web A1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Nurul Aini</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 13:00 - 14:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                13:00 - 14:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum Kecerdasan Buatan C2</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Yoshi Andrana</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 14:40 - 16:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                14:40 - 16:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum Desain Interaksi C1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Yushi Iwamoto</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Jaringan 2 -->
                    <div id="lab2-schedule" class="lab-schedule hidden">
                        <div class="overflow-x-auto">
                            <div class="min-w-[800px]">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                            <th class="w-[120px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">Hari/Jam</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SENIN</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SELASA</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">RABU</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">KAMIS</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center text-xs md:text-sm">JUMAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris 08:00 - 09:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                08:00 - 09:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum FBD B1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Justin Bieber</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 09:40 - 11:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                09:40 - 11:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Prak SDA B1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Cut Nabilah</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 11:20 - 13:00 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                11:20 - 13:00
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Prak Pemograman Web A1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Nurul Aini</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 13:00 - 14:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                13:00 - 14:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Prak WS C1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Indah Azzahra</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 14:40 - 16:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                14:40 - 16:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Prak SBD C2</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">David Hortono</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Jaringan 3 -->
                     <div id="lab3-schedule" class="lab-schedule hidden">
                        <div class="overflow-x-auto">
                            <div class="min-w-[800px]">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                            <th class="w-[120px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">Hari/Jam</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SENIN</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SELASA</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">RABU</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">KAMIS</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center text-xs md:text-sm">JUMAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris 08:00 - 09:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                08:00 - 09:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="space-y-1 md:space-y-2">
                                                    <div class="font-semibold text-blue-900 text-xs md:text-sm">Praktikum FBD B1</div>
                                                    <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                                        <i class="fas fa-user text-xs"></i>
                                                        <span class="text-xs">Justin Bieber</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 09:40 - 11:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                09:40 - 11:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 11:20 - 13:00 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                11:20 - 13:00
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 13:00 - 14:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                13:00 - 14:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 14:40 - 16:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                14:40 - 16:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Jaringan 4 -->
                     <div id="lab4-schedule" class="lab-schedule hidden">
                        <div class="overflow-x-auto">
                            <div class="min-w-[800px]">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                            <th class="w-[120px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">Hari/Jam</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SENIN</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">SELASA</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">RABU</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">KAMIS</th>
                                            <th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center text-xs md:text-sm">JUMAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Baris 08:00 - 09:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                08:00 - 09:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 09:40 - 11:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                09:40 - 11:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 11:20 - 13:00 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                11:20 - 13:00
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 13:00 - 14:40 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                13:00 - 14:40
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>

                                        <!-- Baris 14:40 - 16:20 -->
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">
                                                14:40 - 16:20
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                            <td class="p-3 md:p-4 text-center border-r border-gray-200">
                                                <div class="text-xs md:text-sm text-gray-600">-</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container untuk Tombol Selesaikan Semester -->
        <div class="mt-6 md:mt-8 flex justify-center">
            <button id="selesaikan-semester-btn" class="bg-[#99391B] hover:bg-[#7a2e16] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                <span class="text-sm md:text-base">Selesaikan Semester</span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Buka Semester Baru -->
<div id="buka-semester-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg md:rounded-xl w-full max-w-md mx-auto">
        <!-- Header Modal -->
        <div class="border-b border-gray-200 p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-bold text-gray-800">Buka Semester Baru</h3>
            <p class="text-gray-600 text-sm mt-1">Tentukan periode pengambilan jadwal praktikum</p>
        </div>
        
        <!-- Form -->
        <form id="form-buka-semester" class="p-4 md:p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Semester</label>
                    <input type="text" name="nama_semester" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="Contoh: Ganjil 2024/2025" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
            </div>
            
            <!-- Footer Modal -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 space-y-2 sm:space-y-0">
                <button type="button" id="batal-buka-semester" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 shadow-lg">
                    Buka Semester
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Selesaikan Semester -->
<div id="selesaikan-semester-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-lg md:rounded-xl w-full max-w-md mx-auto">
        <div class="p-6 text-center">
            <!-- Icon Warning -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            
            <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Selesaikan Semester?</h3>
            <p class="text-gray-600 mb-6 text-sm md:text-base">
                Dengan menyelesaikan semester, semua pengambilan jadwal akan dihapus dan jadwal menjadi kosong kembali. Tindakan ini tidak dapat dikembalikan.
            </p>
            
            <div class="flex flex-col-reverse sm:flex-row sm:justify-center sm:space-x-3 space-y-2 sm:space-y-0">
                <button id="batal-selesaikan" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-lg border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button id="konfirmasi-selesaikan" class="w-full sm:w-auto bg-[#99391B] hover:bg-[#7a2e16] text-white px-6 py-2 rounded-lg font-semibold transition-all duration-200 shadow-lg">
                    Ya, Selesaikan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.lab-btn.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #dc2626 100%);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.lab-btn:not(.active) {
    background-color: #f3f4f6;
    color: #374151;
}

.lab-schedule {
    transition: all 0.3s ease-in-out;
}

.lab-schedule.active {
    display: block;
}

.lab-schedule:not(.active) {
    display: none;
}

/* Scroll indicator untuk mobile */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .min-w-\[800px\] {
        min-width: 800px;
    }
    
    /* Improve touch scrolling */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}

/* Desktop optimization */
@media (min-width: 769px) {
    .overflow-x-auto {
        overflow-x: visible;
    }
}

/* Animation for modal */
@keyframes modalEnter {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-enter {
    animation: modalEnter 0.3s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const statusTerkunci = document.getElementById('status-terkunci');
    const statusTerbuka = document.getElementById('status-terbuka');
    const bukaSemesterBtn = document.getElementById('buka-semester-btn');
    const bukaSemesterModal = document.getElementById('buka-semester-modal');
    const batalBukaSemester = document.getElementById('batal-buka-semester');
    const formBukaSemester = document.getElementById('form-buka-semester');
    const selesaikanSemesterBtn = document.getElementById('selesaikan-semester-btn');
    const selesaikanSemesterModal = document.getElementById('selesaikan-semester-modal');
    const batalSelesaikan = document.getElementById('batal-selesaikan');
    const konfirmasiSelesaikan = document.getElementById('konfirmasi-selesaikan');

    // Check if there's active semester in localStorage (simulasi backend)
    const activeSemester = localStorage.getItem('activeSemester');
    if (activeSemester) {
        showTerbukaState(JSON.parse(activeSemester));
    }

    // Modal Buka Semester
    if (bukaSemesterBtn) {
        bukaSemesterBtn.addEventListener('click', function() {
            bukaSemesterModal.classList.remove('hidden');
            bukaSemesterModal.classList.add('modal-enter');
        });
    }

    if (batalBukaSemester) {
        batalBukaSemester.addEventListener('click', function() {
            bukaSemesterModal.classList.add('hidden');
        });
    }

    // Form Buka Semester
    if (formBukaSemester) {
        formBukaSemester.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const semesterData = {
                nama: formData.get('nama_semester'),
                mulai: formData.get('tanggal_mulai'),
                selesai: formData.get('tanggal_selesai')
            };
            
            // Simpan ke localStorage (nanti diganti dengan API call)
            localStorage.setItem('activeSemester', JSON.stringify(semesterData));
            
            // Tutup modal dan update UI
            bukaSemesterModal.classList.add('hidden');
            showTerbukaState(semesterData);
            
            // Reset form
            this.reset();
        });
    }

    // Modal Selesaikan Semester
    if (selesaikanSemesterBtn) {
        selesaikanSemesterBtn.addEventListener('click', function() {
            selesaikanSemesterModal.classList.remove('hidden');
            selesaikanSemesterModal.classList.add('modal-enter');
        });
    }

    if (batalSelesaikan) {
        batalSelesaikan.addEventListener('click', function() {
            selesaikanSemesterModal.classList.add('hidden');
        });
    }

    if (konfirmasiSelesaikan) {
        konfirmasiSelesaikan.addEventListener('click', function() {
            // Hapus dari localStorage (nanti diganti dengan API call)
            localStorage.removeItem('activeSemester');
            
            // Kembali ke state terkunci
            selesaikanSemesterModal.classList.add('hidden');
            showTerkunciState();
        });
    }

    // Close modal ketika klik di luar
    [bukaSemesterModal, selesaikanSemesterModal].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    });

    // Function untuk menampilkan state terkunci
    function showTerkunciState() {
        statusTerkunci.classList.remove('hidden');
        statusTerbuka.classList.add('hidden');
    }

    // Function untuk menampilkan state terbuka
    function showTerbukaState(semesterData) {
        // Update info semester
        document.getElementById('nama-semester').textContent = semesterData.nama;
        document.getElementById('periode-semester').textContent = 
            `Periode: ${formatDate(semesterData.mulai)} - ${formatDate(semesterData.selesai)}`;
        
        // Hitung sisa waktu
        const sisaHari = calculateDaysLeft(semesterData.selesai);
        document.getElementById('sisa-waktu').textContent = `${sisaHari} hari`;
        
        // Tampilkan state terbuka
        statusTerkunci.classList.add('hidden');
        statusTerbuka.classList.remove('hidden');
    }

    // Format date to readable format
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    // Calculate days left
    function calculateDaysLeft(endDate) {
        const end = new Date(endDate);
        const now = new Date();
        const diffTime = end - now;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays > 0 ? diffDays : 0;
    }

    // Setup lab buttons functionality
    function setupLabButtons() {
        const labButtons = document.querySelectorAll('.lab-btn');
        const labSchedules = document.querySelectorAll('.lab-schedule');
        
        labButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update button states
                labButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });

                this.classList.remove('bg-gray-200', 'text-gray-700');
                this.classList.add('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');

                // Update schedule visibility
                labSchedules.forEach(schedule => {
                    schedule.classList.remove('active');
                    schedule.classList.add('hidden');
                });

                // Get lab number from button ID
                const buttonId = this.id;
                let labNumber;
                
                if (buttonId.includes('mobile')) {
                    labNumber = buttonId.replace('-btn-mobile', '');
                } else {
                    labNumber = buttonId.replace('-btn', '');
                }

                const targetSchedule = document.getElementById(labNumber + '-schedule');
                if (targetSchedule) {
                    targetSchedule.classList.remove('hidden');
                    targetSchedule.classList.add('active');
                }

                // Sync state dengan versi lainnya
                if (buttonId.includes('mobile')) {
                    const desktopBtn = document.getElementById(buttonId.replace('-mobile', ''));
                    if (desktopBtn) {
                        desktopBtn.classList.remove('bg-gray-200', 'text-gray-700');
                        desktopBtn.classList.add('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                    }
                } else {
                    const mobileBtn = document.getElementById(buttonId + '-mobile');
                    if (mobileBtn) {
                        mobileBtn.classList.remove('bg-gray-200', 'text-gray-700');
                        mobileBtn.classList.add('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                    }
                }
            });
        });
    }

    // Add scroll indicator for mobile
    function addScrollIndicator() {
        const scrollContainers = document.querySelectorAll('.overflow-x-auto');
        scrollContainers.forEach(container => {
            // Check if mobile
            if (window.innerWidth <= 768) {
                let hasScrollIndicator = container.parentNode.querySelector('.scroll-indicator');
                if (!hasScrollIndicator) {
                    const indicator = document.createElement('div');
                    indicator.className = 'scroll-indicator text-center text-xs text-gray-500 py-2 bg-gray-50 border-b border-gray-200';
                    indicator.textContent = '← Geser untuk melihat jadwal lengkap →';
                    container.parentNode.insertBefore(indicator, container);
                }
            } else {
                // Remove indicator if exists on desktop
                const indicator = container.parentNode.querySelector('.scroll-indicator');
                if (indicator) {
                    indicator.remove();
                }
            }
        });
    }

    // Initialize
    setupLabButtons();
    addScrollIndicator();
    window.addEventListener('resize', addScrollIndicator);
});
</script>
@endsection