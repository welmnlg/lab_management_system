@extends('layouts.main')
@section('title', 'Ambil Jadwal - ITLG Lab Management System')
@section('content')
<div class="space-y-6 md:space-y-10">
    <!-- Status Terkunci (Default State) -->
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
    <!-- Status Terbuka (Akan Ditampilkan Setelah Buka Semester) -->
    <div id="status-terbuka" class="hidden">
        <!-- Header Info Semester (Full Width dengan background putih) -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <h2 class="text-lg md:text-xl font-bold text-gray-800">Periode Ambil Jadwal Sedang Berjalan</h2>
            </div>
            <!-- Box Hijau dengan opacity -->
            <div class="bg-green-500/10 border border-green-200 rounded-lg p-4 backdrop-blur-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="font-semibold text-green-800 text-lg md:text-xl" id="nama-semester">-</h3>
                        <p class="text-green-700 text-sm md:text-base" id="periode-semester">
                            Periode: - 
                        </p>
                        <p class="text-green-600 text-xs md:text-sm" id="periode-pengambilan">
                            Pengambilan Jadwal: -
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-green-700">
                            Sisa waktu: 
                            <span class="font-bold text-green-800 text-lg" id="sisa-waktu">-</span>
                        </p>
                        <p class="text-xs text-green-600" id="status-pengambilan">
                            Status: -
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container Lab dan Tabel -->
        <div class="space-y-4 md:space-y-6">
            <!-- Header dengan tombol lab dan button ambil jadwal -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                    <div class="flex-1">

                        <!-- Container untuk tombol lab -->
                        <div id="lab-buttons" class="flex flex-wrap gap-2 mb-4">
                            <!-- Tombol lab akan dimuat di sini -->
                        </div>
                    </div>
                    <!-- Tombol Action Group -->
                    <div class="flex flex-col gap-3">

                        <!-- Tombol Ambil Jadwal Baru -->
                        <a href="{{ route('form-ambil-jadwal') }}" class="bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full lg:w-auto text-sm">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Ambil Jadwal Baru</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Tabel Jadwal -->
            <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Container untuk semua lab -->
                <div id="lab-schedules">
                    <!-- Jadwal akan dimuat via JavaScript -->
                    <div id="no-schedule" class="text-center py-12">
                        <div class="text-gray-500">
                            <i class="fas fa-calendar-times text-4xl mb-4"></i>
                            <p class="text-lg">Pilih gedung dan ruangan untuk melihat jadwal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container untuk Tombol Selesaikan Semester dan Buka Pengambilan -->
        <div class="mt-6 md:mt-8 flex justify-center space-x-4">
            <button id="selesaikan-semester-btn" class="bg-[#99391B] hover:bg-[#7a2e16] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                <i class="fas fa-flag-checkered mr-2"></i>
                <span class="text-sm md:text-base">Selesaikan Semester</span>
            </button>
            <button id="buka-pengambilan-btn" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                <i class="fas fa-lock-open mr-2"></i>
                <span class="text-sm md:text-base">Buka Pengambilan Jadwal</span>
            </button>
        </div>
    </div>
</div>
<!-- Modal Buka Semester Baru -->
<div id="buka-semester-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4 pt-20 md:pt-24 lg:pt-0 lg:pl-64">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto my-auto">
        <!-- Header Modal -->
        <div class="border-b border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800">Buka Semester Baru</h3>
            <p class="text-gray-600 text-sm mt-1">Tentukan periode pengambilan jadwal praktikum</p>
        </div>
        <!-- Form -->
        <form id="form-buka-semester" class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester_type" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                        <option value="">Pilih Semester</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                    <input type="text" name="academic_year" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="Contoh: 2024/2025" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Semester</label>
                    <input type="date" name="start_date" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai Semester</label>
                    <input type="date" name="end_date" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Pengambilan Jadwal</label>
                    <input type="date" name="schedule_start_date" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai Pengambilan Jadwal</label>
                    <input type="date" name="schedule_end_date" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                </div>
            </div>
            <!-- Footer Modal -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 space-y-2 sm:space-y-0">
                <button type="button" id="batal-buka-semester" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg">
                    Buka Semester
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Konfirmasi Selesaikan Semester -->
<div id="selesaikan-semester-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4" style="padding-top: 120px;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <div class="p-6 text-center">
            <!-- Icon Warning -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Selesaikan Semester?</h3>
            <p class="text-gray-600 mb-6 text-base">
                Dengan menyelesaikan semester, semua pengambilan jadwal akan dihapus dan jadwal menjadi kosong kembali. Tindakan ini tidak dapat dikembalikan.
            </p>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-center sm:space-x-3 space-y-2 sm:space-y-0">
                <button id="batal-selesaikan" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button id="konfirmasi-selesaikan" class="w-full sm:w-auto bg-[#99391B] hover:bg-[#7a2e16] text-white px-6 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg">
                    Ya, Selesaikan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Kelola Gedung & Ruangan -->

<!-- Modal Edit Jadwal -->
<div id="edit-jadwal-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
            <h3 class="text-xl font-bold text-white text-center">Edit Jadwal</h3>
        </div>
        <!-- Form Content -->
        <form id="formEditJadwal" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="schedule_id" id="edit_schedule_id">
            <!-- Mata Kuliah (Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Mata Kuliah</label>
                <input type="text" id="edit_course_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm" disabled>
            </div>
            <!-- Kelas (Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Kelas</label>
                <input type="text" id="edit_class_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm" disabled>
            </div>
            <!-- Gedung (Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Gedung</label>
                <input type="text" id="edit_building_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm" disabled>
            </div>
            <!-- Ruangan -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Ruangan <span class="text-red-500">*</span></label>
                <select name="room_id" id="edit_room_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" required>
                    <option value="">Pilih Ruangan</option>
                </select>
            </div>
            <!-- Hari -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Hari <span class="text-red-500">*</span></label>
                <select name="day_of_week" id="edit_day_of_week" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" required>
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                </select>
            </div>
            <!-- Waktu -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Waktu <span class="text-red-500">*</span></label>
                <select name="time_slot" id="edit_time_slot" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" required>
                    <option value="">Pilih Waktu</option>
                    <option value="08.00 - 08:50">08.00 - 08:50</option>
                    <option value="08:50 - 09:40">08:50 - 09:40</option>
                    <option value="09:40 - 10:30">09:40 - 10:30</option>
                    <option value="10:30 - 11:20">10:30 - 11:20</option>
                    <option value="11:20 - 12:10">11:20 - 12:10</option>
                    <option value="12:10 - 13:00">12:10 - 13:00</option>
                    <option value="13:00 - 13:50">13:00 - 13:50</option>
                    <option value="13:50 - 14:40">13:50 - 14:40</option>
                    <option value="14:40 - 15:30">14:40 - 15:30</option>
                    <option value="15:30 - 16:20">15:30 - 16:20</option>
                </select>
            </div>
            <!-- Footer Modal -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 space-y-2 sm:space-y-0">
                <button type="button" id="batal-edit-jadwal" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Buka Pengambilan Jadwal -->
<div id="buka-pengambilan-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
            <h3 class="text-xl font-bold text-white text-center" id="modal-pengambilan-title">Buka Pengambilan Jadwal</h3>
        </div>
        <!-- Form Content -->
        <form id="formBukaPengambilan" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="pengambilan_action" name="action" value="open">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Pilih Aslab yang Diizinkan</label>
                <div id="aslab-list" class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    <!-- Daftar aslab akan dimuat via JavaScript -->
                </div>
            </div>
            <!-- Footer Modal -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 space-y-2 sm:space-y-0">
                <button type="button" id="batal-buka-pengambilan" class="w-full sm:w-auto px-4 py-2 text-gray-600 hover:text-gray-800 font-medium rounded-xl border border-gray-300 hover:border-gray-400 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-2 rounded-xl font-semibold transition-all duration-200 shadow-lg">
                    <span id="submit-pengambilan-text">Buka Pengambilan</span>
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Loading Spinner -->
<div id="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        <span class="text-gray-700">Memproses...</span>
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
/* Style untuk tabel jadwal - format baru sesuai BENTUKAN JADWAL */
.schedule-cell {
    min-height: 80px;
    padding: 8px;
    border: 1px solid #e5e7eb;
    vertical-align: top;
    position: relative;
}
.schedule-item {
    background: white;
    color: #374151;
    border-radius: 8px;
    padding: 12px;
    font-size: 12px;
    margin-bottom: 4px;
    cursor: default;
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
    text-align: center;
    position: relative;
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.schedule-item:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
/* CRITICAL: Style untuk editable items */
.schedule-item.editable {
    border-left: 4px solid #10b981;
    cursor: pointer !important;
    position: relative;
    background-color: #ecfdf5 !important;
}
.schedule-item.editable::after {
    content: '✏️ KLIK UNTUK EDIT';
    position: absolute;
    bottom: 2px;
    right: 2px;
    font-size: 8px;
    color: #10b981;
    font-weight: bold;
    background: white;
    padding: 2px 4px;
    border-radius: 3px;
}
.schedule-item.editable:hover {
    background-color: #d1fae5 !important;
    border-left-color: #059669;
    border-left-width: 6px;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}
.schedule-item .course-name {
    font-weight: bold;
    margin-bottom: 4px;
    color: #1f2937;
    font-size: 11px;
    line-height: 1.2;
}
.schedule-item .class-name {
    font-size: 10px;
    color: #6b7280;
    margin-bottom: 4px;
}
.schedule-item .lecturer-name {
    font-size: 10px;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 4px;
}
.schedule-item .lecturer-name i {
    color: #9ca3af;
}
.schedule-item .edit-indicator {
    font-size: 9px;
    color: #10b981;
    margin-top: 4px;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 2px;
}
/* Style untuk format yang diinginkan - center aligned */
.lab-schedule table td {
    vertical-align: middle;
    text-align: center;
}
.lab-schedule .space-y-1 {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
}
.lab-schedule .font-semibold {
    text-align: center;
    margin-bottom: 4px;
}
.lab-schedule .flex.items-center.justify-center {
    display: flex;
    align-items: center;
    justify-content: center;
}
/* Style untuk tabel header */
.lab-schedule table thead th {
    background: linear-gradient(135deg, #1e3a8a 0%, #dc2626 100%);
    color: white;
    font-weight: 600;
    text-align: center;
    padding: 12px 8px;
    border-right: 1px solid rgba(255,255,255,0.2);
    font-size: 12px;
}
.lab-schedule table thead th:first-child {
    border-top-left-radius: 8px;
}
.lab-schedule table thead th:last-child {
    border-top-right-radius: 8px;
    border-right: none;
}
/* Empty state */
.empty-slot {
    color: #9ca3af;
    font-size: 11px;
    font-style: italic;
}
/* Hover effect untuk schedule item */
.schedule-item:not(.editable):hover {
    border-left: 4px solid #e5e7eb;
}
.schedule-item.editable:hover .edit-indicator {
    color: #059669;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔥 === CURRENT USER INFO ===');
    console.log('Current User ID from Auth:', {{ auth()->id() }});
    console.log('Current User Name:', '{{ auth()->user()->name }}');
    console.log('Current User NIM:', '{{ auth()->user()->nim }}');
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

    const editJadwalModal = document.getElementById('edit-jadwal-modal');
    const batalEditJadwal = document.getElementById('batal-edit-jadwal');
    const formEditJadwal = document.getElementById('formEditJadwal');
    const bukaPengambilanBtn = document.getElementById('buka-pengambilan-btn');
    const bukaPengambilanModal = document.getElementById('buka-pengambilan-modal');
    const batalBukaPengambilan = document.getElementById('batal-buka-pengambilan');
    const formBukaPengambilan = document.getElementById('formBukaPengambilan');
    const modalPengambilanTitle = document.getElementById('modal-pengambilan-title');
    const submitPengambilanText = document.getElementById('submit-pengambilan-text');
    const loading = document.getElementById('loading');
    // State
    let currentRoom = null;
    let rooms = [];
    let rooms = [];
    let currentPeriod = null;
    const currentUserId = {{ auth()->id() }};
    console.log('🚀 Page loaded. Current user ID:', currentUserId);
    // Check if there's active semester on page load
    checkActiveSemester();
    // Modal Buka Semester
    if (bukaSemesterBtn) {
        bukaSemesterBtn.addEventListener('click', function() {
            bukaSemesterModal.classList.remove('hidden');
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
            const data = {
                semester_type: formData.get('semester_type'),
                academic_year: formData.get('academic_year'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                schedule_start_date: formData.get('schedule_start_date'),
                schedule_end_date: formData.get('schedule_end_date')
            };
            // API call untuk buka semester
            fetch('{{ route("semester-periods.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Tutup modal dan update UI
                    bukaSemesterModal.classList.add('hidden');
                    showTerbukaState(result.data);
                    // Reset form
                    this.reset();
                    // Show success message
                    showNotification('Semester berhasil dibuka!', 'success');
                } else {
                    showNotification(result.message || 'Gagal membuka semester', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat membuka semester', 'error');
            });
        });
    }
    // Modal Selesaikan Semester
    if (selesaikanSemesterBtn) {
        selesaikanSemesterBtn.addEventListener('click', function() {
            selesaikanSemesterModal.classList.remove('hidden');
        });
    }
    if (batalSelesaikan) {
        batalSelesaikan.addEventListener('click', function() {
            selesaikanSemesterModal.classList.add('hidden');
        });
    }
    if (konfirmasiSelesaikan) {
        konfirmasiSelesaikan.addEventListener('click', function() {
            // API call untuk selesaikan semester
            fetch('{{ route("semester-periods.close") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Tutup modal dan update UI
                    selesaikanSemesterModal.classList.add('hidden');
                    showTerkunciState();
                    // Show success message
                    showNotification('Semester berhasil diselesaikan!', 'success');
                } else {
                    showNotification(result.message || 'Gagal menyelesaikan semester', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyelesaikan semester', 'error');
            });
        });
    }

    // Modal Edit Jadwal
    if (batalEditJadwal) {
        batalEditJadwal.addEventListener('click', function() {
            editJadwalModal.classList.add('hidden');
        });
    }
    // Modal Buka Pengambilan
    if (bukaPengambilanBtn) {
        bukaPengambilanBtn.addEventListener('click', function() {
            loadAslabList();
            bukaPengambilanModal.classList.remove('hidden');
        });
    }
    if (batalBukaPengambilan) {
        batalBukaPengambilan.addEventListener('click', function() {
            bukaPengambilanModal.classList.add('hidden');
        });
    }
    // Close modals when clicking outside
    [bukaSemesterModal, selesaikanSemesterModal, editJadwalModal, bukaPengambilanModal].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    });
    // Form Edit Jadwal
    if (formEditJadwal) {
        formEditJadwal.addEventListener('submit', function(e) {
            e.preventDefault();
            updateSchedule();
        });
    }
    // Form Buka Pengambilan
    if (formBukaPengambilan) {
        formBukaPengambilan.addEventListener('submit', function(e) {
            e.preventDefault();
            toggleScheduleTaking();
        });
    }
    // Functions
    function checkActiveSemester() {
        fetch('{{ route("semester-periods.active") }}')
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    showTerbukaState(result.data);
                } else {
                    showTerkunciState();
                }
            })
            .catch(error => {
                console.error('Error checking active semester:', error);
                showTerkunciState();
            });
    }
    function showTerkunciState() {
        statusTerkunci.classList.remove('hidden');
        statusTerbuka.classList.add('hidden');
    }
    function showTerbukaState(semesterData) {
        statusTerkunci.classList.add('hidden');
        statusTerbuka.classList.remove('hidden');
        currentPeriod = semesterData;
        // Update semester info
        const remainingDays = Math.ceil(semesterData.remaining_days);
        document.getElementById('nama-semester').textContent = 
            `${semesterData.semester_type} ${semesterData.academic_year}`;
        document.getElementById('periode-semester').textContent = 
            `Periode: ${semesterData.date_range}`;
        document.getElementById('periode-pengambilan').textContent = 
            `Pengambilan Jadwal: ${semesterData.schedule_date_range || 'Belum diatur'}`;
        document.getElementById('sisa-waktu').textContent = 
            `${remainingDays} hari`;
        // Update status pengambilan jadwal
        const statusElement = document.getElementById('status-pengambilan');
        if (semesterData.is_schedule_open) {
            statusElement.textContent = 'Status: Dibuka Manual';
            statusElement.className = 'text-xs text-green-600 font-semibold';
            bukaPengambilanBtn.innerHTML = '<i class="fas fa-lock mr-2"></i><span class="text-sm md:text-base">Tutup Pengambilan Jadwal</span>';
            document.getElementById('pengambilan_action').value = 'close';
            modalPengambilanTitle.textContent = 'Tutup Pengambilan Jadwal';
            submitPengambilanText.textContent = 'Tutup Pengambilan';
            bukaPengambilanBtn.disabled = false;
            bukaPengambilanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else if (semesterData.is_schedule_taking_open) {
            statusElement.textContent = 'Status: Terbuka';
            statusElement.className = 'text-xs text-green-600 font-semibold';
            bukaPengambilanBtn.innerHTML = '<i class="fas fa-lock-open mr-2"></i><span class="text-sm md:text-base">Buka Pengambilan Jadwal</span>';
            document.getElementById('pengambilan_action').value = 'open';
            modalPengambilanTitle.textContent = 'Buka Pengambilan Jadwal';
            submitPengambilanText.textContent = 'Buka Pengambilan';
            bukaPengambilanBtn.disabled = true;
            bukaPengambilanBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            statusElement.textContent = 'Status: Tertutup';
            statusElement.className = 'text-xs text-red-600 font-semibold';
            bukaPengambilanBtn.innerHTML = '<i class="fas fa-lock-open mr-2"></i><span class="text-sm md:text-base">Buka Pengambilan Jadwal</span>';
            document.getElementById('pengambilan_action').value = 'open';
            modalPengambilanTitle.textContent = 'Buka Pengambilan Jadwal';
            submitPengambilanText.textContent = 'Buka Pengambilan';
            bukaPengambilanBtn.disabled = false;
            bukaPengambilanBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        // Load rooms directly
        loadRooms();
    }

    function loadRooms() {
        fetch('/api/rooms')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    rooms = result.data;
                    populateRoomButtons();
                    // Load schedule for first room if exists
                    if (rooms.length > 0) {
                        loadScheduleForRoom(rooms[0].room_id);
                    } else {
                        // Show no schedule message
                        document.getElementById('lab-schedules').innerHTML = `
                            <div id="no-schedule" class="text-center py-12">
                                <div class="text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                                    <p class="text-lg">Tidak ada ruangan tersedia</p>
                                </div>
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
            });
    }
    function populateRoomButtons() {
        const labButtons = document.getElementById('lab-buttons');
        if (labButtons) {
            labButtons.innerHTML = '';
            rooms.forEach((room, index) => {
                const button = document.createElement('button');
                button.className = `lab-btn px-4 py-2 rounded-lg font-medium transition-all duration-200 text-sm ${index === 0 ? 'active' : ''}`;
                button.textContent = room.room_name;
                button.dataset.roomId = room.room_id;
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.lab-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    // Add active class to clicked button
                    this.classList.add('active');
                    // Load schedule for this room
                    loadScheduleForRoom(room.room_id);
                });
                labButtons.appendChild(button);
            });
        }
    }
    function loadScheduleForRoom(roomId) {
        currentRoom = roomId;
        console.log('🔄 === Loading schedule for room ===', roomId);
        fetch(`/api/rooms/${roomId}/schedules`)
            .then(response => response.json())
            .then(result => {
                console.log('📦 === API RESPONSE RAW ===', result);
                if (result.success) {
                    console.log('📊 === SCHEDULES DATA DETAIL ===');
                    result.data.forEach((schedule, index) => {
                        console.log(`Schedule ${index + 1}:`, {
                            id: schedule.schedule_id,
                            course: schedule.course_name,
                            lecturer: schedule.lecturer_name,
                            user_id: schedule.user_id,
                            lecturer_user_id: schedule.lecturer_user_id,
                            can_edit: schedule.can_edit,
                            can_edit_type: typeof schedule.can_edit
                        });
                    });
                    displaySchedule(result.data, roomId);
                } else {
                    console.error('❌ API returned success=false');
                }
            })
            .catch(error => {
                console.error('❌ Error loading schedule:', error);
            });
    }
    function displaySchedule(schedules, roomId) {
        const labSchedules = document.getElementById('lab-schedules');
        console.log('🎨 === Displaying schedule ===');
        console.log('Schedules count:', schedules.length);
        console.log('Room ID:', roomId);
        console.log('Current User ID from Auth:', currentUserId);
        console.log('All schedules ', schedules);
        // Generate schedule table
        const scheduleHTML = generateScheduleTable(schedules, roomId);
        labSchedules.innerHTML = scheduleHTML;
        console.log('⏳ Waiting for DOM update...');
        // Attach listeners
        setTimeout(() => {
            attachScheduleItemListeners();
        }, 100);
    }
    function generateScheduleTable(schedules, roomId) {
        // Get current room name
        const room = rooms.find(r => r.room_id == roomId);
        const roomName = room ? room.room_name : 'Unknown Room';
        // Days and times
        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        const times = [
            '08.00 - 08:50', '08:50 - 09:40', '09:40 - 10:30',
            '10:30 - 11:20', '11:20 - 12:10', '12:10 - 13:00', 
            '13:00 - 13:50', '13:50 - 14:40', '14:40 - 15:30', '15:30 - 16:20'
        ];
        let html = `
            <div class="overflow-x-auto">
                <div class="min-w-[800px]">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-900 to-red-700 text-white">
                                <th class="w-[120px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">Waktu/Hari</th>
        `;
        // Add day headers
        days.forEach(day => {
            html += `<th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">${day}</th>`;
        });
        html += `</tr></thead><tbody>`;
        // Add time slots
        times.forEach(time => {
            html += `<tr class="border-b border-gray-200 hover:bg-gray-50">`;
            html += `<td class="p-3 md:p-4 font-semibold text-gray-700 bg-gray-50 border-r border-gray-200 text-center text-xs md:text-sm">${time}</td>`;
            days.forEach(day => {
                const daySchedules = schedules.filter(s => 
                    s.day_of_week === day && s.time_slot === time
                );
                html += `<td class="p-3 md:p-4 text-center border-r border-gray-200 schedule-cell">`;
                if (daySchedules.length > 0) {
                    daySchedules.forEach(schedule => {
                        // Pastikan schedule.user_id dan schedule.lecturer_name tidak null
                        const scheduleUserId = schedule.user_id !== null ? parseInt(schedule.user_id) : null;
                        const isOwner = scheduleUserId !== null && scheduleUserId === currentUserId;
                        const canEdit = schedule.can_edit === true; // Convert to boolean

                        console.log('🔧 Rendering schedule item:', {
                            schedule_id: schedule.schedule_id,
                            course: schedule.course_name,
                            lecturer: schedule.lecturer_name,
                            schedule_user_id: scheduleUserId,
                            current_user_id: currentUserId,
                            can_edit: canEdit,
                            is_editable: canEdit ? 'YES ✅' : 'NO ❌'
                        });

                        const scheduleClass = canEdit ? 'schedule-item editable' : 'schedule-item';
                        const dataCanEdit = canEdit ? '1' : '0';

                        // Buat elemen HTML untuk jadwal
                        html += `
                            <div class="${scheduleClass}" 
                                data-schedule-id="${schedule.schedule_id}" 
                                data-can-edit="${dataCanEdit}"
                                data-lecturer-id="${scheduleUserId}"
                                data-lecturer-name="${schedule.lecturer_name || 'Unknown Lecturer'}"
                                style="${canEdit ? 'background-color: #ecfdf5 !important; border-left: 6px solid #10b981 !important; cursor: pointer;' : 'cursor: not-allowed;'}">
                                <div class="course-name">${schedule.course_name || 'Unknown Course'}</div>
                                <div class="class-name">${schedule.class_name || ''}</div>
                                <div class="lecturer-name">
                                    <i class="fas fa-user"></i>
                                    ${schedule.lecturer_name || 'Unknown Lecturer'}
                                </div>
                                ${canEdit ? 
                                    '<div class="edit-indicator" style="color: #10b981; font-weight: bold;"><i class="fas fa-edit"></i> KLIK UNTUK EDIT</div>' : 
                                    '<div class="edit-indicator" style="color: #ef4444; font-size: 10px;">Tidak dapat diedit</div>'
                                }
                                <!-- DEBUG INFO -->
                                <div style="font-size: 8px; color: #6b7280; margin-top: 2px;">
                                    Schedule User: ${scheduleUserId || 'N/A'} | Current User: ${currentUserId}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += `<div class="empty-slot">-</div>`;
                }
                html += `</td>`;
            });
            html += `</tr>`;
        });
        html += `</tbody></table></div></div>`;
        return html;
    }

    function attachScheduleItemListeners() {
        console.log('🔗 === Attaching event listeners ===');
        const allItems = document.querySelectorAll('.schedule-item');
        console.log(`📊 Total schedule items found: ${allItems.length}`);
        let editableCount = 0;
        allItems.forEach((item, index) => {
            const scheduleId = item.getAttribute('data-schedule-id');
            const canEdit = item.getAttribute('data-can-edit');
            const lecturerId = item.getAttribute('data-lecturer-id');
            const lecturerName = item.getAttribute('data-lecturer-name');
            const isEditable = canEdit === '1';
            if (isEditable) editableCount++;

            console.log(`Item ${index + 1}:`, {
                scheduleId: scheduleId,
                lecturerId: lecturerId,
                lecturerName: lecturerName,
                canEdit: canEdit,
                isEditable: isEditable,
                currentUserId: currentUserId
            });

            // Remove old listeners
            const newItem = item.cloneNode(true);
            item.parentNode.replaceChild(newItem, item);

            if (isEditable) {
                // EDITABLE - Add click listener
                newItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('✅ CLICKED EDITABLE SCHEDULE!', {
                        scheduleId: this.getAttribute('data-schedule-id'),
                        lecturer: this.getAttribute('data-lecturer-name'),
                        currentUser: currentUserId
                    });
                    openEditModal(parseInt(this.getAttribute('data-schedule-id')));
                });
                newItem.style.cursor = 'pointer';
                newItem.style.borderLeft = '6px solid #10b981';
                newItem.style.backgroundColor = '#ecfdf5';
            } else {
                // NON-EDITABLE - Show clear message
                newItem.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const scheduleLecturer = this.getAttribute('data-lecturer-name');
                    console.log('❌ CLICKED NON-EDITABLE SCHEDULE!', {
                        scheduleId: this.getAttribute('data-schedule-id'),
                        lecturer: scheduleLecturer,
                        currentUser: currentUserId
                    });
                    showNotification(`Ini jadwal ${scheduleLecturer}. Anda hanya dapat mengedit jadwal Anda sendiri.`, 'error');
                });
                newItem.style.cursor = 'not-allowed';
                newItem.style.opacity = '0.8';
            }
        });
        console.log(`✅ Event listeners attached. Editable items: ${editableCount} / ${allItems.length}`);
    }


    function openEditModal(scheduleId) {
        console.log('🔓 === Opening edit modal ===');
        console.log('Schedule ID:', scheduleId);
        // Show loading
        loading.classList.remove('hidden');
        fetch(`/api/schedules/${scheduleId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                loading.classList.add('hidden');
                if (result.success) {
                    const schedule = result.data;
                    console.log('📄 Schedule data loaded:', schedule);
                    // Isi form dengan data jadwal
                    document.getElementById('edit_schedule_id').value = schedule.schedule_id;
                    document.getElementById('edit_course_name').value = `${schedule.course_code} - ${schedule.course_name}`;
                    document.getElementById('edit_class_name').value = schedule.class_name;
                    document.getElementById('edit_building_name').value = schedule.building_name;
                    document.getElementById('edit_day_of_week').value = schedule.day_of_week;
                    document.getElementById('edit_time_slot').value = schedule.time_slot;
                    // Load rooms untuk dropdown
                    loadRoomsForEdit(schedule.room_id);
                } else {
                    console.error('❌ Failed to load schedule:', result.message);
                    showNotification('Gagal memuat data jadwal: ' + result.message, 'error');
                }
            })
            .catch(error => {
                loading.classList.add('hidden');
                console.error('❌ Error loading schedule:', error);
                showNotification('Terjadi kesalahan saat memuat data jadwal', 'error');
            });
    }
    function loadRoomsForEdit(currentRoomId) {
        console.log('⚙️ Loading rooms for edit');
        fetch('/api/rooms')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const roomSelect = document.getElementById('edit_room_id');
                    roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
                    result.data.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.room_id;
                        option.textContent = room.room_name;
                        if (room.room_id == currentRoomId) {
                            option.selected = true;
                        }
                        roomSelect.appendChild(option);
                    });
                    editJadwalModal.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('❌ Error loading rooms:', error);
                editJadwalModal.classList.remove('hidden');
            });
    }
    function updateSchedule() {
        const formData = new FormData(formEditJadwal);
        const data = Object.fromEntries(formData.entries());
        const scheduleId = data.schedule_id;
        // Convert time slot to start_time and end_time
        const timeSlot = data.time_slot;
        if (timeSlot) {
            const [startTime, endTime] = timeSlot.split(' - ');
            data.start_time = startTime.replace('.', ':');
            data.end_time = endTime;
        }
        // Show loading
        loading.classList.remove('hidden');
        fetch(`/api/schedules/${scheduleId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            loading.classList.add('hidden');
            if (result.success) {
                editJadwalModal.classList.add('hidden');
                showNotification('Jadwal berhasil diupdate!', 'success');
                // Reload schedule
                if (currentRoom) {
                    loadScheduleForRoom(currentRoom);
                }
            } else {
                showNotification(result.message || 'Gagal mengupdate jadwal', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loading.classList.add('hidden');
            showNotification('Terjadi kesalahan saat mengupdate jadwal', 'error');
        });
    }
    function loadAslabList() {
        fetch('/api/users?role=aslab')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const aslabList = document.getElementById('aslab-list');
                    aslabList.innerHTML = '';
                    result.data.forEach(user => {
                        const checkbox = document.createElement('div');
                        checkbox.className = 'flex items-center space-x-2 p-2 hover:bg-gray-50 rounded';
                        checkbox.innerHTML = `
                            <input type="checkbox" name="allowed_users[]" value="${user.user_id}" id="user-${user.user_id}" class="rounded text-blue-600 focus:ring-blue-500">
                            <label for="user-${user.user_id}" class="text-sm text-gray-700">${user.name} (${user.nim})</label>
                        `;
                        aslabList.appendChild(checkbox);
                    });
                    // Pre-select users if already allowed
                    if (currentPeriod && currentPeriod.allowed_users) {
                        currentPeriod.allowed_users.forEach(userId => {
                            const checkbox = document.querySelector(`input[value="${userId}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error loading aslab list:', error);
                showNotification('Gagal memuat daftar aslab', 'error');
            });
    }
    function toggleScheduleTaking() {
        const formData = new FormData(formBukaPengambilan);
        const allowedUsers = [];
        formData.getAll('allowed_users[]').forEach(userId => {
            allowedUsers.push(parseInt(userId));
        });
        const action = document.getElementById('pengambilan_action').value;
        const url = action === 'open' ? '/api/semester-periods/open-schedule-taking' : '/api/semester-periods/close-schedule-taking';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ allowed_users: allowedUsers })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                bukaPengambilanModal.classList.add('hidden');
                showNotification(result.message, 'success');
                // Update button text and refresh data
                checkActiveSemester();
            } else {
                showNotification(result.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan', 'error');
        });
    }
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 p-4 rounded-lg shadow-2xl transition-all duration-300 transform translate-x-0 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.style.zIndex = '99999';
        notification.style.maxWidth = '400px';
        notification.style.fontSize = '14px';
        notification.style.fontWeight = '600';
        notification.textContent = message;
        document.body.appendChild(notification);
        // Remove notification after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(150%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
});
</script>
@endsection