@extends('layouts.main')

@section('title', 'Ambil Jadwal - ITLG Lab Management System')

@section('content')
<div class="space-y-4 md:space-y-6">
    
    <!-- Periode Ambil Jadwal Info -->
    <div id="periode-info" class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
        <h2 class="flex items-center text-base md:text-lg font-semibold text-gray-700 mb-3 md:mb-4">
            <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full mr-2 md:mr-3"></div>
            Periode Ambil Jadwal Sedang Berjalan
        </h2>
        
        <!-- Info Container -->
        <div id="periode-content" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 md:p-6">
            <!-- Loading State -->
            <div id="loading-periode" class="text-center py-4">
                <div class="inline-block w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-sm text-gray-600 mt-2">Memuat informasi periode...</p>
            </div>
            
            <!-- Content State (Hidden by default) -->
            <div id="periode-data" class="hidden">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-3 md:space-y-0">
                    <div class="flex-1">
                        <h3 id="nama-semester" class="text-lg md:text-2xl font-bold text-gray-800 mb-2">-</h3>
                        <p id="periode-semester" class="text-xs md:text-sm text-gray-600 mb-1">Periode: -</p>
                        <p id="periode-pengambilan" class="text-xs md:text-sm text-green-600 font-semibold">Pengambilan Jadwal: -</p>
                        <p id="status-pengambilan" class="text-xs text-gray-500 mt-1">Status: -</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xs md:text-sm text-gray-600 mb-1">Sisa waktu:</p>
                        <p id="sisa-waktu" class="text-2xl md:text-3xl font-bold text-green-600">-</p>
                        <p id="status-badge" class="text-xs font-semibold mt-1 inline-block px-2 py-1 rounded-full bg-green-100 text-green-700">Status: Terbuka</p>
                    </div>
                </div>
            </div>
            
            <!-- No Active Period State -->
            <div id="no-periode" class="text-center py-4 hidden">
                <i class="fas fa-info-circle text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-600">Belum ada periode pengambilan jadwal aktif</p>
            </div>
        </div>
    </div>

    <!-- Header dengan tombol lab dan button ambil jadwal -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 p-4 md:p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div id="room-buttons-container" class="flex flex-wrap gap-2">
                <!-- Room buttons will be loaded here dynamically -->
                <div class="text-sm text-gray-500">Memuat ruangan...</div>
            </div>
            <a href="{{ route('form-ambil-jadwal') }}" id="ambil-jadwal-btn" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white px-4 md:px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg flex items-center justify-center space-x-2 w-full md:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-calendar-plus"></i>
                <span>Ambil Jadwal Baru</span>
            </a>
        </div>
    </div>

    <!-- Tabel Jadwal -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div id="lab-schedules">
            <!-- Schedule will be loaded dynamically -->
            <div class="text-center py-12">
                <div class="inline-block w-8 h-8 border-3 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-gray-600">Memuat jadwal...</p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Jadwal Modal -->
<div id="edit-jadwal-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        <!-- Header Modal -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4 rounded-t-2xl">
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
                    <option value="08.00 - 09.40">08.00 - 09.40</option>
                    <option value="09.40 - 11.20">09.40 - 11.20</option>
                    <option value="11.20 - 13.00">11.20 - 13.00</option>
                    <option value="13.00 - 14.40">13.00 - 14.40</option>
                    <option value="14.40 - 16.20">14.40 - 16.20</option>
                </select>
            </div>
            
            <!-- Delete Button -->
            <div class="pt-2">
                <button type="button" id="delete-schedule-btn" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition-all duration-200">
                    <i class="fas fa-trash mr-2"></i>Hapus Jadwal
                </button>
            </div>
            
            <!-- Footer Modal -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 mt-6 space-y-2 space-y-reverse sm:space-y-0">
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
.room-btn.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #dc2626 100%);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
}

.room-btn:not(.active) {
    background-color: #f3f4f6;
    color: #374151;
}

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

@media (max-width: 768px) {
    .min-w-\[800px\] {
        min-width: 800px;
    }
    
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}

/* Schedule Item Styles */
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

/* Editable Schedule Items - GREEN */
.schedule-item.editable {
    border-left: 4px solid #10b981;
    cursor: pointer !important;
    position: relative;
    background-color: #ecfdf5 !important;
}

.schedule-item.editable:hover {
    background-color: #d1fae5 !important;
    border-left-color: #059669;
    border-left-width: 6px;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

/* Non-Editable Schedule Items */
.schedule-item:not(.editable) {
    opacity: 0.85;
}

.schedule-item:not(.editable):hover {
    border-left: 4px solid #ef4444;
    opacity: 1;
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
    margin-top: 4px;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 2px;
    transition: all 0.2s ease;
}

.schedule-item.editable:hover .edit-indicator {
    color: #059669;
    font-weight: 700;
    transform: scale(1.05);
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rooms = [];
    let currentRoom = null;
    let currentPeriod = null;
    
    // Check active period first
    checkActivePeriod();
    
    function checkActivePeriod() {
        fetch('/api/semester-periods/active')
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    currentPeriod = result.data;
                    showPeriodeInfo(result.data);
                    loadRooms(); // Load rooms after period is confirmed
                } else {
                    showNoPeriode();
                }
            })
            .catch(error => {
                console.error('Error checking period:', error);
                showNoPeriode();
            });
    }
    
    function showPeriodeInfo(periodData) {
        document.getElementById('loading-periode').classList.add('hidden');
        document.getElementById('no-periode').classList.add('hidden');
        document.getElementById('periode-data').classList.remove('hidden');
        
        // Calculate remaining days
        let remainingDays = 0;
        if (periodData.schedule_end_date) {
            const today = new Date();
            const endDate = new Date(periodData.schedule_end_date);
            const diffTime = endDate - today;
            remainingDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        }
        
        // Update UI
        document.getElementById('nama-semester').textContent = 
            `${periodData.semester_type} ${periodData.academic_year}`;
        document.getElementById('periode-semester').textContent = 
            `Periode: ${periodData.date_range}`;
        document.getElementById('periode-pengambilan').textContent = 
            `Pengambilan Jadwal: ${periodData.schedule_date_range || 'Belum diatur'}`;
        document.getElementById('sisa-waktu').textContent = 
            remainingDays > 0 ? `${remainingDays} hari` : 'Berakhir';
            
        // Status
        const statusElement = document.getElementById('status-pengambilan');
        const badgeElement = document.getElementById('status-badge');
        const ambilJadwalBtn = document.getElementById('ambil-jadwal-btn');
        
        if (periodData.is_schedule_open) {
            statusElement.textContent = 'Status: Dibuka Manual';
            statusElement.className = 'text-xs text-green-600 font-semibold mt-1';
            badgeElement.textContent = 'Terbuka (Manual)';
            badgeElement.className = 'text-xs font-semibold mt-1 inline-block px-3 py-1 rounded-full bg-green-100 text-green-700';
            ambilJadwalBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else if (periodData.is_schedule_taking_open) {
            statusElement.textContent = 'Status: Terbuka';
            statusElement.className = 'text-xs text-green-600 font-semibold mt-1';
            badgeElement.textContent = 'Terbuka';
            badgeElement.className = 'text-xs font-semibold mt-1 inline-block px-3 py-1 rounded-full bg-green-100 text-green-700';
            ambilJadwalBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            statusElement.textContent = 'Status: Tertutup';
            statusElement.className = 'text-xs text-red-600 font-semibold mt-1';
            badgeElement.textContent = 'Tertutup';
            badgeElement.className = 'text-xs font-semibold mt-1 inline-block px-3 py-1 rounded-full bg-red-100 text-red-700';
            ambilJadwalBtn.classList.add('opacity-50', 'cursor-not-allowed');
            ambilJadwalBtn.addEventListener('click', (e) => {
                if (!periodData.is_schedule_taking_open && !periodData.is_schedule_open) {
                    e.preventDefault();
                    alert('Periode pengambilan jadwal sedang ditutup. Silakan hubungi admin.');
                }
            });
        }
    }
    
    function showNoPeriode() {
        document.getElementById('loading-periode').classList.add('hidden');
        document.getElementById('periode-data').classList.add('hidden');
        document.getElementById('no-periode').classList.remove('hidden');
        
        // Disable ambil jadwal button
        const ambilJadwalBtn = document.getElementById('ambil-jadwal-btn');
        ambilJadwalBtn.classList.add('opacity-50', 'cursor-not-allowed');
        ambilJadwalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            alert('Belum ada periode semester aktif');
        });
    }
    
    function loadRooms() {
        fetch('/api/rooms')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    rooms = result.data;
                    populateRoomButtons();
                    
                    // Load first room schedule
                    if (rooms.length > 0) {
                        loadScheduleForRoom(rooms[0].room_id);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
            });
    }
    
    function populateRoomButtons() {
        const container = document.getElementById('room-buttons-container');
        container.innerHTML = '';
        
        rooms.forEach((room, index) => {
            const button = document.createElement('button');
            button.className = `room-btn px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm ${index === 0 ? 'active bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}`;
            button.textContent = room.room_name;
            button.setAttribute('data-room-id', room.room_id);
            
            button.addEventListener('click', function() {
                // Update button states
                document.querySelectorAll('.room-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
                
                this.classList.remove('bg-gray-200', 'text-gray-700');
                this.classList.add('active', 'bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white');
                
                // Load schedule
                loadScheduleForRoom(room.room_id);
            });
            
            container.appendChild(button);
        });
    }
    
    function loadScheduleForRoom(roomId) {
        currentRoom = roomId;
        const container = document.getElementById('lab-schedules');
        
        // Show loading
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="inline-block w-8 h-8 border-3 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-gray-600">Memuat jadwal...</p>
            </div>
        `;
        
        fetch(`/api/rooms/${roomId}/schedules`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    displaySchedule(result.data, roomId);
                } else {
                    container.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-circle text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600">Gagal memuat jadwal</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading schedule:', error);
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-circle text-4xl text-red-400 mb-2"></i>
                        <p class="text-gray-600">Terjadi kesalahan saat memuat jadwal</p>
                    </div>
                `;
            });
    }
    
    function displaySchedule(schedules, roomId) {
        const room = rooms.find(r => r.room_id == roomId);
        const roomName = room ? room.room_name : 'Unknown Room';
        const scheduleHTML = generateScheduleTable(schedules, roomName);
        document.getElementById('lab-schedules').innerHTML = scheduleHTML;
        
        // Attach click listeners after rendering
        setTimeout(() => {
            attachScheduleListeners();
        }, 100);
    }
    
    function generateScheduleTable(schedules, roomName) {
        const currentUserId = {{ auth()->id() }}; // Get current user ID
        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        const times = [
            '08.00 - 09.40', '09.40 - 11.20', 
            '11.20 - 13.00', '13.00 - 14.40', 
            '14.40 - 16.20'
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
            html += `<th class="w-[140px] md:w-1/6 p-3 md:p-4 font-semibold text-center border-r border-white/20 text-xs md:text-sm">${day.toUpperCase()}</th>`;
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
                
                html += `<td class="p-3 md:p-4 text-center border-r border-gray-200">`;
                
                if (daySchedules.length > 0) {
                    daySchedules.forEach(schedule => {
                        // Check permission
                        const scheduleUserId = schedule.user_id !== null ? parseInt(schedule.user_id) : null;
                        const canEdit = schedule.can_edit === true || scheduleUserId === currentUserId;
                        
                        // Style based on permission
                        const cardStyle = canEdit 
                            ? 'background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 8px; border-radius: 6px; cursor: pointer;' 
                            : 'background-color: #f9fafb; border-left: 4px solid #ef4444; padding: 8px; border-radius: 6px; cursor: not-allowed;';
                        
                        const scheduleClass = canEdit ? 'schedule-item editable' : 'schedule-item';
                        const dataCanEdit = canEdit ? '1' : '0';
                        
                        html += `
                            <div class="${scheduleClass}" 
                                 style="${cardStyle}" 
                                 data-schedule-id="${schedule.schedule_id}"
                                 data-can-edit="${dataCanEdit}"
                                 data-course-name="${schedule.course_name}"
                                 data-class-name="${schedule.class_name || ''}"
                                 data-lecturer-name="${schedule.lecturer_name || 'Unknown'}"
                                 data-room-id="${schedule.room_id || ''}"
                                 data-day="${schedule.day_of_week}"
                                 data-time-slot="${schedule.time_slot}">
                                <div class="font-semibold text-gray-800 text-xs md:text-sm mb-1">${schedule.course_name}</div>
                                <div class="text-xs text-gray-600 mb-1">${schedule.class_name || ''}</div>
                                <div class="flex items-center justify-center space-x-1 text-xs text-gray-600 mb-2">
                                    <i class="fas fa-user text-xs"></i>
                                    <span>${schedule.lecturer_name || 'Unknown'}</span>
                                </div>
                                ${canEdit ? 
                                    (currentPeriod && (currentPeriod.is_schedule_taking_open || currentPeriod.is_schedule_open) ?
                                        '<div style="color: #10b981; font-weight: bold; font-size: 10px;"><i class="fas fa-edit"></i> KLIK UNTUK EDIT</div>' :
                                        '<div style="color: #ef4444; font-weight: bold; font-size: 10px;"><i class="fas fa-lock"></i> Pengambilan jadwal ditutup</div>'
                                    ) :
                                    '<div style="color: #ef4444; font-style: italic; font-size: 10px;">Tidak dapat diedit</div>'
                                }
                                <!-- Debug Info -->
                                <div style="font-size: 8px; color: #6b7280; margin-top: 4px;">
                                    Schedule User: ${scheduleUserId || 'N/A'} | Current User: ${currentUserId}
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html += `<div class="text-xs md:text-sm text-gray-400">-</div>`;
                }
                
                html += `</td>`;
            });
            
            html += `</tr>`;
        });
        
        html += `</tbody></table></div></div>`;
        
        return html;
    }
    
    function attachScheduleListeners() {
        const scheduleItems = document.querySelectorAll('.schedule-item');
        console.log('📌 Attaching listeners to', scheduleItems.length, 'items');
        
        scheduleItems.forEach(item => {
            const canEdit = item.getAttribute('data-can-edit') === '1';
            const scheduleId = item.getAttribute('data-schedule-id');
            
            if (canEdit) {
                // EDITABLE - Check period then open modal
                item.addEventListener('click', function() {
                    console.log('🎯 Clicked editable schedule:', scheduleId);
                    
                    // ✅ NEW: Check if schedule taking is open before allowing edit
                    if (currentPeriod && !currentPeriod.is_schedule_taking_open && !currentPeriod.is_schedule_open) {
                        showNotification('Pengambilan jadwal sedang ditutup. Anda tidak dapat mengedit jadwal saat ini.', 'error');
                        return;
                    }
                    
                    openEditModal(parseInt(scheduleId));
                });
            } else {
                // NON-EDITABLE - Show notification
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const scheduleLecturer = this.getAttribute('data-lecturer-name') || 'pengguna lain';
                    console.log('❌ Clicked non-editable schedule');
                    showNotification(`Ini jadwal ${scheduleLecturer}. Anda hanya dapat mengedit jadwal Anda sendiri.`, 'error');
                });
                item.style.cursor = 'not-allowed';
            }
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
    
    function openEditModal(scheduleId) {
        console.log('🔓 Opening edit modal for schedule:', scheduleId);
        const modal = document.getElementById('edit-jadwal-modal');
        const loading = document.getElementById('loading');
        
        loading.classList.remove('hidden');
        
        // Fetch schedule details
        fetch(`/api/schedules/${scheduleId}`)
            .then(response => response.json())
            .then(result => {
                loading.classList.add('hidden');
                
                if (result.success) {
                    const schedule = result.data;
                    
                    // Fill form
                    document.getElementById('edit_schedule_id').value = schedule.schedule_id;
                    document.getElementById('edit_course_name').value = `${schedule.course_code} - ${schedule.course_name}`;
                    document.getElementById('edit_class_name').value = schedule.class_name;
                    document.getElementById('edit_building_name').value = schedule.building_name || 'Gedung C';
                    document.getElementById('edit_day_of_week').value = schedule.day_of_week;
                    document.getElementById('edit_time_slot').value = schedule.time_slot;
                    
                    // Load rooms and select current
                    loadRoomsForEdit(schedule.room_id);
                    
                    // Show modal
                    modal.classList.remove('hidden');
                } else {
                    alert('Gagal mengambil data jadwal');
                }
            })
            .catch(error => {
                loading.classList.add('hidden');
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
    }
    
    function loadRoomsForEdit(selectedRoomId) {
        const roomSelect = document.getElementById('edit_room_id');
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        rooms.forEach(room => {
            const option = document.createElement('option');
            option.value = room.room_id;
            option.textContent = room.room_name;
            if (room.room_id == selectedRoomId) {
                option.selected = true;
            }
            roomSelect.appendChild(option);
        });
    }
    
    // Modal close handlers
    const editModal = document.getElementById('edit-jadwal-modal');
    const batalEditBtn = document.getElementById('batal-edit-jadwal');
    const formEditJadwal = document.getElementById('formEditJadwal');
    const deleteBtn = document.getElementById('delete-schedule-btn');
    
    batalEditBtn.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });
    
    // Form submit handler
    formEditJadwal.addEventListener('submit', function(e) {
        e.preventDefault();
        updateSchedule();
    });
    
    // Delete handler
    deleteBtn.addEventListener('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            deleteSchedule();
        }
    });
    
    function updateSchedule() {
        const scheduleId = document.getElementById('edit_schedule_id').value;
        const formData = new FormData(formEditJadwal);
        const data = Object.fromEntries(formData.entries());
        
        const loading = document.getElementById('loading');
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
                alert('Jadwal berhasil diupdate!');
                editModal.classList.add('hidden');
                // Reload schedules
                loadScheduleForRoom(currentRoom);
            } else {
                alert('Gagal mengupdate jadwal: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(error => {
            loading.classList.add('hidden');
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate jadwal');
        });
    }
    
    function deleteSchedule() {
        const scheduleId = document.getElementById('edit_schedule_id').value;
        const loading = document.getElementById('loading');
        loading.classList.remove('hidden');
        
        fetch(`/api/schedules/${scheduleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(result => {
            loading.classList.add('hidden');
            
            if (result.success) {
                alert('Jadwal berhasil dihapus!');
                editModal.classList.add('hidden');
                // Reload schedules
                loadScheduleForRoom(currentRoom);
            } else {
                alert('Gagal menghapus jadwal: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(error => {
            loading.classList.add('hidden');
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus jadwal');
        });
    }
});
</script>
@endsection