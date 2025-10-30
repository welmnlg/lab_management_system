@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    {{-- Denah dan Jadwal Ruangan --}}
    <div class="flex flex-col xl:flex-row gap-6">
        <div class="flex-1">
            {{-- Grid Denah Ruangan --}}
            <div class="grid grid-cols-2 gap-4 lg:gap-6 mb-6">
                {{-- Card Ruang Lab Jaringan 1 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 1</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab1', true)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab1-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 2 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 2</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab2', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab2-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 3 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 3</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab3', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab3-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 4 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 4</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab4', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab4-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 rounded-t-lg -mx-4 -mt-4 mb-4">
                    <h3 class="font-semibold">Keterangan:</h3>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-y-3 sm:gap-y-0 sm:gap-x-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Ruangan Digunakan</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-gray-400 rounded flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Ruangan Kosong</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Penggunaan Ruangan --}}
        <div class="w-full xl:w-96">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-blue-800 text-white text-center py-3 rounded-t-lg">
                    <h3 class="font-semibold text-sm lg:text-base" id="schedule-title">Jadwal Penggunaan Ruang Lab Jaringan 1</h3>
                </div>
                <div class="p-4 space-y-4 max-h-96 overflow-y-auto" id="schedule-content">
                    {{-- Konten jadwal akan diisi oleh JavaScript --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Kalender Mingguan --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-y-4 sm:gap-y-0">
                <div class="flex flex-wrap gap-2">
                    <button onclick="selectLab(1)" class="px-4 py-2 rounded-full text-sm font-medium bg-blue-900 text-white" id="tab-lab1">Lab Jaringan 1</button>
                    <button onclick="selectLab(2)" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab2">Lab Jaringan 2</button>
                    <button onclick="selectLab(3)" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab3">Lab Jaringan 3</button>
                    <button onclick="selectLab(4)" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab4">Lab Jaringan 4</button>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button onclick="previousWeek()" class="p-2 rounded-full bg-blue-900 text-white hover:bg-blue-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <span class="text-sm font-medium text-gray-700" id="date-range">22 September 2025 - 26 September 2025</span>
                    <button onclick="nextWeek()" class="p-2 rounded-full bg-blue-900 text-white hover:bg-blue-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button onclick="addClass()" class="px-3 py-2 sm:px-4 text-xs sm:text-sm bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-lg font-medium hover:opacity-90">
                        + Kelas Ganti
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-2 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200">Hari/Jam</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">SENIN</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">SELASA</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">RABU</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">KAMIS</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 min-w-32">JUMAT</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    {{-- Konten kalender akan diisi oleh JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- ### KODE MODAL YANG HILANG SEBELUMNYA, DITARUH DI SINI ### --}}
    <div id="kelas-ganti-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 text-center flex-shrink-0">
                <h2 class="text-2xl font-bold text-white">Kelas Ganti</h2>
            </div>
            <div class="overflow-y-auto p-6 md:p-8">
                <form id="kelas-ganti-form" action="#" method="POST">
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- <div>
                                <label for="nama-lengkap" class="block text-sm font-medium text-gray-800 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama-lengkap" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-800 mb-2">NIM</label>
                                <input type="text" name="nim" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan NIM Anda">
                            </div> -->
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="mata-kuliah" class="block text-sm font-medium text-gray-800 mb-2">Mata Kuliah</label>
                                <select name="course_id" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Mata Kuliah</option>
                                </select>
                            </div>
                            <div>
                                <label for="ruangan" class="block text-sm font-medium text-gray-800 mb-2">Ruangan yang Digunakan</label>
                                <select name="room_id" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Ruangan</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-800 mb-2">Kelas</label>
                                <select name="class_id" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Kelas</option>
                                </select>
                            </div>
                            <div>
                                <label for="hari" class="block text-sm font-medium text-gray-800 mb-2">Hari</label>
                                <select name="day" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Hari</option>
                                </select>
                            </div>
                            <div>
                                <label for="waktu" class="block text-sm font-medium text-gray-800 mb-2">Waktu</label>
                                <select name="time_slot" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Waktu</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-4 flex-shrink-0 bg-white">
                <button type="button" onclick="closeKelasGantiModal()" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" form="kelas-ganti-form" class="px-8 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const API_BASE = '/api/dashboard';

    // Global variables
    let formData = {
        courses: [],
        course_classes: [],
        rooms: [],
        days: [],
        time_slots: []
    };

    let currentRoomId = 1;
    let currentLab = 1; // Default Lab Jaringan 1 (room_id)
    let currentWeekStart = '2025-10-27'; // Senin, 27 Oktober 2025
    
    // Map lab string to room_id
    const labToRoomId = {
        'lab1': 1,
        'lab2': 2,
        'lab3': 3,
        'lab4': 4
    };

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Load initial data
        showSchedule('lab1');
        selectLab(1);
        updateDateRange();
        
        // Load room statuses to set colors correctly
        updateRoomColors();
    });

    // Load dropdown options
    async function loadFormData() {
        try {
            const response = await fetch('/api/dashboard/form-data');
            const data = await response.json();
            
            if (data.success) {
                formData = data.data;
                populateDropdowns();
            }
        } catch (error) {
            console.error('Error loading form data:', error);
        }
    }

    // Populate all dropdowns
    function populateDropdowns() {
        console.log('Populating dropdowns with data:', formData); // Debug
        
        // ✅ FIX: Mata Kuliah
        const courseSelect = document.querySelector('select[name="course_id"]'); // FIXED: underscore
        if (courseSelect) {
            courseSelect.innerHTML = `<option selected disabled>Pilih Mata Kuliah</option>`;
            formData.courses.forEach(course => {
                // FIXED: property names with underscore
                courseSelect.innerHTML += `<option value="${course.course_id}">${course.course_name} (${course.course_code})</option>`;
            });
            
            courseSelect.addEventListener('change', function() {
                populateClassesDropdown(this.value);
            });
        }

        // ✅ FIX: Ruangan
        const roomSelect = document.querySelector('select[name="room_id"]'); // FIXED: underscore
        if (roomSelect) {
            roomSelect.innerHTML = `<option selected disabled>Pilih Ruangan</option>`;
            formData.rooms.forEach(room => {
                // FIXED: property names with underscore
                roomSelect.innerHTML += `<option value="${room.room_id}">${room.room_name}</option>`;
            });
        }

        // ✅ FIX: Hari
        const daySelect = document.querySelector('select[name="day"]');
        if (daySelect) {
            daySelect.innerHTML = `<option selected disabled>Pilih Hari</option>`;
            formData.days.forEach(day => {
                daySelect.innerHTML += `<option value="${day.value}">${day.label}</option>`;
            });
        }

        // ✅ FIX: Waktu
        const timeSelect = document.querySelector('select[name="time_slot"]'); // FIXED: underscore
        if (timeSelect) {
            timeSelect.innerHTML = `<option selected disabled>Pilih Waktu</option>`;
            formData.time_slots.forEach(slot => { // FIXED: time_slots with underscore
                timeSelect.innerHTML += `<option value="${slot.value}" data-start="${slot.start}" data-end="${slot.end}">${slot.label}</option>`;
            });
        }
    }

    // Populate kelas based on selected course
    function populateClassesDropdown(courseId) {
        const classSelect = document.querySelector('select[name="class_id"]'); // FIXED: underscore
        if (!classSelect) return;

        const filteredClasses = formData.course_classes.filter(c => c.course_id == courseId); // FIXED: property names
        
        classSelect.innerHTML = `<option selected disabled>Pilih Kelas</option>`;
        filteredClasses.forEach(cls => {
            classSelect.innerHTML += `<option value="${cls.class_id}">${cls.class_name}</option>`; // FIXED: property names
        });
    }

    // Handle form submit
    const kelasGantiForm = document.getElementById('kelas-ganti-form');
    if (kelasGantiForm) {
        kelasGantiForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Get form data
            const formDataObj = new FormData(this);
            const timeSelect = document.querySelector('select[name="time_slot"]');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            
            const payload = {
                // name: formDataObj.get('nama-lengkap'),
                // nim: formDataObj.get('nim'),
                class_id: formDataObj.get('class_id'),
                room_id: formDataObj.get('room_id'),
                day: formDataObj.get('day'),
                start_time: selectedOption.dataset.start,
                end_time: selectedOption.dataset.end,
                week_start: currentWeekStart
            };

            // Validation
            if (!payload.class_id || !payload.room_id || !payload.day || !payload.start_time) {
                alert('Mohon lengkapi semua field!');
                return;
            }
            // if (!payload.name || !payload.nim || !payload.class_id || !payload.room_id || !payload.day || !payload.start_time) {
            //     alert('Mohon lengkapi semua field!');
            //     return;
            // }

            console.log('Paylooad:', payload)

            try {
                const response = await fetch('/api/dashboard/schedule-override', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    alert('Kelas ganti berhasil dibuat!');
                    closeKelasGantiModal();
                    this.reset();
                    // Refresh jadwal dan kalender
                    if (typeof loadRoomSchedule === 'function') {
                        loadRoomSchedule(currentRoomId);
                    }
                    if (typeof loadWeeklyCalendar === 'function') {
                        loadWeeklyCalendar(currentRoomId);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error creating schedule override:', error);
                alert('Terjadi kesalahan saat membuat kelas ganti');
            }
        });
    }

    // Modal functions
    function openKelasGantiModal() {
        const modal = document.getElementById('kelas-ganti-modal');
        if (modal) {
            modal.classList.remove('hidden');
            loadFormData();
        }
    }

    function closeKelasGantiModal() {
        const modal = document.getElementById('kelas-ganti-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.getElementById('kelas-ganti-form').reset();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadFormData();
        // Get current week start (Monday)
        const today = new Date();
        const dayOfWeek = today.getDay();
        const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
        currentWeekStart = new Date(today.setDate(diff)).toISOString().split('T')[0];
    });

    async function updateRoomColors() {
        try {
            const response = await fetch(API_BASE + '/rooms/status');
            
            // Check content type
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                console.error('API returned non-JSON response');
                return;
            }
            
            const data = await response.json();
            
            if (data.success && data.data) {
                data.data.forEach(room => {
                    const roomElement = document.getElementById('lab' + room.room_id + '-room');
                    if (roomElement) {
                        roomElement.classList.remove('bg-green-500', 'bg-gray-400');
                        
                        if (room.status === 'occupied') {
                            roomElement.classList.add('bg-green-500');
                        } else {
                            roomElement.classList.add('bg-gray-400');
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error updating room colors:', error);
        }
    }
    
    async function showSchedule(labId) {
        const roomId = labToRoomId[labId];
        const scheduleTitle = document.getElementById('schedule-title');
        const scheduleContent = document.getElementById('schedule-content');
        
        scheduleTitle.textContent = 'Jadwal Penggunaan Ruang Lab Jaringan ' + roomId;
        scheduleContent.innerHTML = '<p class="text-gray-500 text-center py-8">Memuat jadwal...</p>';
        
        try {
            const response = await fetch(API_BASE + '/rooms/' + roomId + '/schedules');
            
            // CRITICAL FIX: Check if response is JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error('Server returned HTML instead of JSON. Check if API route exists.');
            }
            
            const data = await response.json();
            
            if (data.success) {
                displayScheduleContent(data.data);
            } else {
                scheduleContent.innerHTML = '<p class="text-red-500 text-center py-4">Gagal memuat jadwal: ' + (data.message || 'Unknown error') + '</p>';
            }
        } catch (error) {
            console.error('Error fetching schedule:', error);
            scheduleContent.innerHTML = '<p class="text-red-500 text-center py-4">Error: ' + error.message + '</p>';
        }
    }

    function displayScheduleContent(data) {
        const scheduleContent = document.getElementById('schedule-content');
        
        if (!data.schedules || data.schedules.length === 0) {
            let html = '<div class="border-l-4 border-gray-400 bg-gray-50 p-3 lg:p-4 rounded-lg">';
            html += '<h4 class="font-semibold text-gray-900 mb-2 text-sm lg:text-base">Tidak ada jadwal hari ini</h4>';
            html += '<div class="flex items-center text-xs lg:text-sm text-gray-600">';
            html += '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">';
            html += '<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>';
            html += '</svg>';
            html += '<span>' + data.date + '</span>';
            html += '<span class="ml-auto bg-gray-500 text-white px-2 py-1 rounded-full text-xs">Kosong</span>';
            html += '</div></div>';
            
            scheduleContent.innerHTML = html;
            return;
        }
        
        let contentHTML = '';
        
        data.schedules.forEach(function(schedule) {
            let borderColor, bgColor, statusBadge, statusText;
            
            if (schedule.status === 'ongoing') {
                borderColor = 'border-green-500';
                bgColor = 'bg-green-50';
                statusBadge = 'bg-green-500';
                statusText = 'Sedang Berlangsung';
            } else if (schedule.status === 'cancelled') {
                borderColor = 'border-red-500';
                bgColor = 'bg-red-50';
                statusBadge = 'bg-red-500';
                statusText = 'Batal';
            } else if (schedule.status === 'completed') {
                borderColor = 'border-gray-400';
                bgColor = 'bg-gray-50';
                statusBadge = 'bg-gray-500';
                statusText = 'Selesai';
            } else {
                borderColor = 'border-gray-400';
                bgColor = 'bg-gray-50';
                statusBadge = 'bg-gray-500';
                statusText = 'Akan Berlangsung';
            }
            
            contentHTML += '<div class="border-l-4 ' + borderColor + ' ' + bgColor + ' p-3 lg:p-4 rounded-lg">';
            contentHTML += '<h4 class="font-semibold text-gray-900 mb-2 text-sm lg:text-base">Mata Kuliah: ' + schedule.course_name + '</h4>';
            contentHTML += `<div class="text-xs lg:text-sm text-gray-700 font-medium mb-2">${schedule.class_name}</div>`;
            contentHTML += '<div class="flex items-center text-xs lg:text-sm text-gray-600 mb-2">';
            contentHTML += '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">';
            contentHTML += '<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>';
            contentHTML += '</svg>';
            contentHTML += '<span>' + schedule.instructor + '</span>';
            contentHTML += '<svg class="w-4 h-4 ml-4 mr-2" fill="currentColor" viewBox="0 0 20 20">';
            contentHTML += '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>';
            contentHTML += '</svg>';
            contentHTML += '<span>' + schedule.start_time + ' - ' + schedule.end_time + '</span>';
            contentHTML += '</div>';
            contentHTML += '<div class="flex items-center text-xs lg:text-sm text-gray-600">';
            contentHTML += '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">';
            contentHTML += '<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>';
            contentHTML += '</svg>';
            contentHTML += '<span>' + data.date + '</span>';
            contentHTML += '<span class="ml-auto ' + statusBadge + ' text-white px-2 py-1 rounded-full text-xs">' + statusText + '</span>';
            contentHTML += '</div>';
            contentHTML += '</div>';
        });
        
        scheduleContent.innerHTML = contentHTML;
    }

   
    async function selectLab(labId) {
        currentLab = labId;
        
        for (let i = 1; i <= 4; i++) {
            const tab = document.getElementById('tab-lab' + i);
            if (tab) {
                if (i === labId) {
                    tab.className = 'px-4 py-2 rounded-full text-sm font-medium bg-blue-900 text-white';
                } else {
                    tab.className = 'px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400';
                }
            }
        }
        
        await loadWeeklyCalendar(labId, currentWeekStart);
    }

    async function loadWeeklyCalendar(roomId, weekStart) {
        const calendarBody = document.getElementById('calendar-body');
        
        calendarBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500"><div class="flex justify-center items-center"><svg class="animate-spin h-6 w-6 text-blue-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="ml-2">Memuat kalender...</span></div></td></tr>';
        
        try {
            const response = await fetch(API_BASE + '/rooms/' + roomId + '/calendar?week_start=' + weekStart);
            
            // CRITICAL FIX: Check content type
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error('Server returned HTML instead of JSON. Check if API route exists.');
            }
            
            const data = await response.json();
            
            if (data.success) {
                displayWeeklyCalendar(data.data);
            } else {
                calendarBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-red-500">Gagal memuat kalender: ' + (data.message || 'Unknown error') + '</td></tr>';
            }
        } catch (error) {
            console.error('Error loading calendar:', error);
            calendarBody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-red-500">Error: ' + error.message + '</td></tr>';
        }
    }

/**
 * Display weekly calendar - FIXED: No template literals in HTML attributes
 */
    function displayWeeklyCalendar(data) {
        const calendarBody = document.getElementById('calendar-body');
        const timeSlots = ["08:00 - 09:40", "09:40 - 11:20", "11:20 - 13:00", "13:00 - 14:40", "14:40 - 16:20"];
        const days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];
        
        let calendarHTML = '';
        
        for (let t = 0; t < timeSlots.length; t++) {
            const timeSlot = timeSlots[t];
            
            calendarHTML += '<tr class="border-t border-gray-200">';
            calendarHTML += '<td class="px-2 py-4 sm:px-4 sm:py-6 text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 bg-gray-50">' + timeSlot + '</td>';
            
            for (let d = 0; d < days.length; d++) {
                const day = days[d];
                const dayData = data.calendar[day];
                
                if (!dayData) {
                    calendarHTML += '<td class="px-2 py-6 border-r border-gray-200"></td>';
                    continue;
                }
                
                let schedule = null;
                for (let s = 0; s < dayData.schedules.length; s++) {
                    if (dayData.schedules[s].time_slot === timeSlot) {
                        schedule = dayData.schedules[s];
                        break;
                    }
                }
                
                if (schedule) {
                    const overrideBadge = schedule.is_override ? '<span class="inline-block mt-1 px-1 py-0.5 bg-yellow-400 text-yellow-900 rounded text-xs">Ganti</span>' : '';
                    
                    calendarHTML += '<td class="px-2 py-2 border-r border-gray-200 align-top">';
                    calendarHTML += '<div class="bg-blue-100 rounded-lg p-1.5 text-xs">';
                    calendarHTML += '<div class="font-bold text-blue-900 mb-1">' + schedule.course_name + ' (' + schedule.class_name + ')' + '</div>';
                    calendarHTML += '<div class="text-blue-700">' + schedule.instructor + '</div>';
                    calendarHTML += overrideBadge;
                    calendarHTML += '</div>';
                    calendarHTML += '</td>';
                } else {
                    calendarHTML += '<td class="px-2 py-6 border-r border-gray-200"></td>';
                }
            }
            
            calendarHTML += '</tr>'; // FIXED: Added closing >
        }
        
        calendarBody.innerHTML = calendarHTML;
    }


    function previousWeek() { 
        const current = new Date(currentWeekStart);
        current.setDate(current.getDate() - 7);
        currentWeekStart = current.toISOString().split('T')[0];
        
        updateDateRange();
        loadWeeklyCalendar(currentLab, currentWeekStart);
    }

    function nextWeek() {
        const current = new Date(currentWeekStart);
        current.setDate(current.getDate() + 7);
        currentWeekStart = current.toISOString().split('T')[0];
    
        updateDateRange();
        loadWeeklyCalendar(currentLab, currentWeekStart); 
    }

    function updateDateRange() {
            const start = new Date(currentWeekStart);
            const end = new Date(start);
            end.setDate(end.getDate() + 4); // Friday
            
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const startStr = start.toLocaleDateString('id-ID', options);
            const endStr = end.toLocaleDateString('id-ID', options);
            
            document.getElementById('date-range').textContent = `${startStr} - ${endStr}`;
        }

    function addClass() {
        const modal = document.getElementById('kelas-ganti-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

/**
 * Close kelas ganti modal
 */
    function closeKelasGantiModal() {
        const modal = document.getElementById('kelas-ganti-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

// Refresh room colors every 30 seconds
setInterval(updateRoomColors, 30000);
</script>
@endpush    