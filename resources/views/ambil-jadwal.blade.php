@extends('layouts.pageadmin')

@section('title', 'Ambil Jadwal - ITLG Lab Management System')

@section('content')
<div class="space-y-4 md:space-y-8">
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
                <a href="{{ route('form-ambil-jadwal') }}" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full text-sm">
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
            <a href="{{ route('form-ambil-jadwal') }}" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full lg:w-auto">
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
                                <tr>
                                    <td colspan="6" class="p-6 md:p-8 text-center text-gray-500 text-sm md:text-base">
                                        Jadwal Lab Jaringan 3 akan ditampilkan di sini
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
                                <tr>
                                    <td colspan="6" class="p-6 md:p-8 text-center text-gray-500 text-sm md:text-base">
                                        Jadwal Lab Jaringan 4 akan ditampilkan di sini
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function untuk handle tombol lab
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

    // Setup untuk semua tombol
    setupLabButtons();

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
    addScrollIndicator();
    window.addEventListener('resize', addScrollIndicator);
});
</script>
@endsection