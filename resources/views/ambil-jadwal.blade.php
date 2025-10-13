@extends('layouts.pageadmin')

@section('title', 'Ambil Jadwal - ITLG Lab Management System')

@section('content')
<div class="space-y-8">
    <!-- Header dengan tombol lab dan button ambil jadwal -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Container untuk semua lab -->
        <div id="lab-schedules">
            <!-- Lab Jaringan 1 (Default Active) -->
            <div id="lab1-schedule" class="lab-schedule active">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse table-fixed">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">Hari/Jam</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SENIN</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SELASA</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">RABU</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">KAMIS</th>
                                <th class="w-1/6 p-4 font-semibold text-center">JUMAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris 08:00 - 09:40 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    08:00 - 09:40
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum IOT B1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Justin Bieber</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 09:40 - 11:20 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    09:40 - 11:20
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum SDA B2</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Cut Nabilah</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 11:20 - 13:00 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    11:20 - 13:00
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum Pemograman Web A1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Nurul Aini</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 13:00 - 14:40 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    13:00 - 14:40
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum Kecerdasan Buatan C2</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Yoshi Andrana</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 14:40 - 16:20 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    14:40 - 16:20
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum Desain Interaksi C1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Yushi Iwamoto</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lab Jaringan 2 -->
            <div id="lab2-schedule" class="lab-schedule hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse table-fixed">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">Hari/Jam</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SENIN</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SELASA</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">RABU</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">KAMIS</th>
                                <th class="w-1/6 p-4 font-semibold text-center">JUMAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris 08:00 - 09:40 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    08:00 - 09:40
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Praktikum FBD B1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Justin Bieber</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 09:40 - 11:20 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    09:40 - 11:20
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Prak SDA B1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Cut Nabilah</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 11:20 - 13:00 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    11:20 - 13:00
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Prak Pemograman Web A1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Nurul Aini</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 13:00 - 14:40 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    13:00 - 14:40
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Prak WS C1</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>Indah Azzahra</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                            </tr>

                            <!-- Baris 14:40 - 16:20 -->
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center">
                                    14:40 - 16:20
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center border-r border-gray-200">
                                    <div class="text-sm text-gray-600">-</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-blue-900 text-sm">Prak SBD C2</div>
                                        <div class="flex items-center justify-center space-x-1 text-xs text-gray-600">
                                            <i class="fas fa-user"></i>
                                            <span>David Hortono</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lab Jaringan 3 -->
            <div id="lab3-schedule" class="lab-schedule hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse table-fixed">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">Hari/Jam</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SENIN</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SELASA</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">RABU</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">KAMIS</th>
                                <th class="w-1/6 p-4 font-semibold text-center">JUMAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Isi jadwal Lab 3 (kosong untuk contoh) -->
                            <tr><td colspan="6" class="p-8 text-center text-gray-500">Jadwal Lab Jaringan 3 akan ditampilkan di sini</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lab Jaringan 4 -->
            <div id="lab4-schedule" class="lab-schedule hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse table-fixed">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">Hari/Jam</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SENIN</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">SELASA</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">RABU</th>
                                <th class="w-1/6 p-4 font-semibold text-center border-r border-white/20">KAMIS</th>
                                <th class="w-1/6 p-4 font-semibold text-center">JUMAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Isi jadwal Lab 4 (kosong untuk contoh) -->
                            <tr><td colspan="6" class="p-8 text-center text-gray-500">Jadwal Lab Jaringan 4 akan ditampilkan di sini</td></tr>
                        </tbody>
                    </table>
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

.table-fixed {
    table-layout: fixed;
}

.w-1\/6 {
    width: 16.666667%;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const labButtons = document.querySelectorAll('.lab-btn');
    const labSchedules = document.querySelectorAll('.lab-schedule');
    
    labButtons.forEach(button => {
        button.addEventListener('click', function() {
            labButtons.forEach(btn => {
                btn.classList.remove('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });

            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');

            labSchedules.forEach(schedule => {
                schedule.classList.remove('active');
                schedule.classList.add('hidden');
            });

            const labId = this.id.replace('-btn', '-schedule');
            const targetSchedule = document.getElementById(labId);
            if (targetSchedule) {
                targetSchedule.classList.remove('hidden');
                targetSchedule.classList.add('active');
            }
        });
    });
});
</script>
@endsection