@extends('layouts.main')

@section('title', 'Profil Pengguna')

@section('content')

    <div class="space-y-6">

        {{-- KARTU INFORMASI PROFIL --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Foto Profil --}}
                <div class="flex-shrink-0">
                    <div
                        class="h-24 w-24 rounded-full bg-gradient-to-r from-blue-900 to-red-700 flex items-center justify-center border-4 border-gray-200 text-white text-2xl font-bold">
                        {{ strtoupper(collect(explode(' ', Auth::user()->name))->map(fn($word) => $word[0])->join('')) }}
                    </div>
                </div>

                {{-- Detail Teks (Data Statis) --}}
                <div class="flex-grow text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800 uppercase">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600">NIM: {{ Auth::user()->nim ?? '-' }}</p>
                    <p class="text-gray-600">Email: {{ Auth::user()->email }}</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex-shrink-0 mt-4 md:mt-0">
                    <button onclick="bukaModalGantiSandi()"
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
            <h2 class="text-xl font-bold text-gray-800 mb-4" id="jadwal-title">Jadwal Praktikum</h2>

            {{-- Tab Hari dengan Tanggal Real-Time --}}
            <div id="schedule-tabs" class="flex space-x-1 overflow-x-auto pb-2 mb-6">
                <!-- Tabs akan di-generate oleh JavaScript -->
            </div>

            {{-- Container untuk konten jadwal per hari --}}
            <div id="schedule-content">
                <!-- Konten jadwal akan di-generate oleh JavaScript -->
            </div>
        </div>

        {{-- Tombol Logout --}}
        <div class="flex justify-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md transition">
                    Logout
                </button>
            </form>
        </div>
    </div>


    {{-- Modal Konfirmasi Aksi --}}
    <div id="modal-konfirmasi" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4" id="modal-title">Konfirmasi</h3>
            <p class="text-gray-600 mb-6" id="modal-message">Apakah Anda yakin?</p>
            <div class="flex justify-end gap-3">
                <button id="modal-batal" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Batal
            </button>
            <button id="modal-ya" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition">
                Ya
            </button>
        </div>
    </div>
</div>

{{-- Modal Ganti Kata Sandi --}}
<div id="modal-ganti-sandi" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Ganti Kata Sandi</h2>
            <p class="text-sm text-gray-600 mt-2">
                Kata sandi harus memiliki minimal 6 karakter dan harus mengandung huruf, angka, dan karakter khusus (!$@%).
            </p>
        </div>

        {{-- Form --}}
        <form id="form-ganti-sandi" class="space-y-4">
            @csrf
            @method('PUT')
            {{-- Kata Sandi Lama --}}
            <div>
                <label for="sandi-lama" class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi Lama
                </label>
                <input type="password" id="sandi-lama" name="current_password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Masukkan kata sandi lama"
                    required
                    autocomplete="current-password">
                <div id="error-sandi-lama" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            {{-- Kata Sandi Baru --}}
            <div>
                <label for="sandi-baru" class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi Baru
                </label>
                <input type="password" id="sandi-baru" name="new_password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Masukkan kata sandi baru"
                    required
                    autocomplete="new-password">
                
                {{-- Indikator Kekuatan Kata Sandi --}}
                <div class="mt-2 space-y-1">
                    <div class="flex items-center text-xs">
                        <span id="indicator-length" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span id="text-length" class="text-gray-600">Minimal 6 karakter</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <span id="indicator-letter" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span id="text-letter" class="text-gray-600">Mengandung huruf</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <span id="indicator-number" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span id="text-number" class="text-gray-600">Mengandung angka</span>
                    </div>
                    <div class="flex items-center text-xs">
                        <span id="indicator-special" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                        <span id="text-special" class="text-gray-600">Mengandung karakter khusus (!$@%)</span>
                    </div>
                </div>
                
                <div id="error-sandi-baru" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            {{-- Konfirmasi Kata Sandi Baru --}}
            <div>
                <label for="konfirmasi-sandi" class="block text-sm font-medium text-gray-700 mb-2">
                    Tulis Ulang Kata Sandi Baru
                </label>
                <input type="password" id="konfirmasi-sandi" name="new_password_confirmation" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Tulis ulang kata sandi baru"
                    required
                    autocomplete="new-password">
                <div id="error-konfirmasi-sandi" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="tutupModalGantiSandi()" 
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" id="submit-ganti-sandi"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg cursor-not-allowed transition"
                    disabled>
                    Ganti Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sukses --}}
<div id="modal-sukses" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center" onclick="event.stopPropagation()">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2" id="sukses-title">Berhasil!</h3>
        <p class="text-gray-600 mb-6" id="sukses-message">Kata sandi berhasil diganti.</p>
        <button onclick="tutupModalSukses()"
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition w-full">
            Tutup
        </button>
    </div>
</div>

{{-- Modal Error --}}
<div id="modal-error" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center" onclick="event.stopPropagation()">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2" id="error-title">Gagal!</h3>
        <p class="text-gray-600 mb-6" id="error-message">Terjadi kesalahan saat mengganti kata sandi.</p>
        <button onclick="tutupModalError()"
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition w-full">
            Tutup
        </button>
    </div>
</div>

<script>
        // ==========================================
        // GLOBAL VARIABLES
        // ==========================================
        let isSandiLamaValid = false;
        let isSandiBaruValid = false;
        let isKonfirmasiValid = false;
        let hariAktif = 'Senin';
        let currentScheduleData = {};

        // ==========================================
        // HELPER FUNCTIONS - DATE & TIME
        // ==========================================

        document.addEventListener('DOMContentLoaded', function() {
            //  Load schedules HANYA SEKALI saat page load
            loadUserSchedules();
        });

        /**
         * Mendapatkan tanggal untuk hari Senin dari minggu ini
         */
        function getMondayOfWeek() {
            const today = new Date();
            const dayOfWeek = today.getDay();
            const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            const monday = new Date(today.setDate(diff));
            return monday;
        }

        /**
         * Format tanggal menjadi "dd MMM" (contoh: "10 Okt")
         */
        function formatDate(date) {
            const day = date.getDate();
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const month = monthNames[date.getMonth()];
            return `${day} ${month}`;
        }

        /**
         * Mendapatkan array tanggal untuk Senin-Jumat minggu ini
         */
        function getWeekDates() {
            const monday = getMondayOfWeek();
            const weekDates = [];

            //Testing sabtu Minggu
            for (let i = 0; i < 5; i++) {
                const date = new Date(monday);
                date.setDate(monday.getDate() + i);
                weekDates.push(formatDate(date));
            }

            return weekDates;
        }

        /**
         * Cek apakah button konfirmasi/batal harus ditampilkan
         * Button muncul 1 jam sebelum jadwal dan 15 menit setelah waktu mulai
         */
        function shouldShowConfirmationButtons(timeSlot, dayName) {
            const now = new Date();
            const currentDay = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now.getDay()];

            // Hanya tampilkan button di hari yang sama
            if (currentDay !== dayName) {
                return false;
            }

            // Parse waktu mulai dari time slot (contoh: "08.00 - 08:50")
            const startTimeStr = timeSlot.split(' - ')[0];
            const [hours, minutes] = startTimeStr.replace('.', ':').split(':').map(Number);

            // Buat Date object untuk waktu jadwal hari ini
            const scheduleTime = new Date();
            scheduleTime.setHours(hours, minutes, 0, 0);

            // Waktu batas button muncul: 1 jam sebelum jadwal
            const oneHourBefore = new Date(scheduleTime);
            oneHourBefore.setHours(oneHourBefore.getHours() - 1);

            // Waktu batas button hilang: 15 menit setelah waktu mulai
            const fifteenMinutesAfter = new Date(scheduleTime);
            fifteenMinutesAfter.setMinutes(fifteenMinutesAfter.getMinutes() + 15);

            // Button muncul jika waktu sekarang antara oneHourBefore dan fifteenMinutesAfter
            return now >= oneHourBefore && now <= fifteenMinutesAfter;
        }

        /**
         * Check if the schedule time has passed (more than 15 mins after start)
         */
        function checkIsPast(timeSlot, dayName) {
            const now = new Date();
            const currentDay = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now.getDay()];

            // If not the same day, check if it's a past or future day
            if (currentDay !== dayName) {
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const todayIdx = now.getDay();
                const scheduleDayIdx = days.indexOf(dayName);

                // If schedule day index is less than today (e.g., Monday < Wednesday), it's past
                // But we need to handle week wraparound (e.g., Sunday is 0, but could be future)
                // For simplicity: if scheduleDayIdx < todayIdx, it's past in this week
                if (scheduleDayIdx < todayIdx) {
                    return true; // Past day this week
                } else {
                    return false; // Future day this week
                }
            }

            // Same day - check time
            const startTimeStr = timeSlot.split(' - ')[0];
            const [hours, minutes] = startTimeStr.replace('.', ':').split(':').map(Number);

            const scheduleTime = new Date();
            scheduleTime.setHours(hours, minutes, 0, 0);

            const fifteenMinutesAfter = new Date(scheduleTime);
            fifteenMinutesAfter.setMinutes(fifteenMinutesAfter.getMinutes() + 15);

            return now > fifteenMinutesAfter;
        }

        /**
         * Get HTML badge for status
         */
        function getStatusBadge(status) {
            let colorClass = 'bg-gray-200 text-gray-800';
            let label = formatStatus(status);

            switch (status) {
                case 'terjadwal':
                    colorClass = 'bg-gray-200 text-gray-800';
                    break;
                case 'dikonfirmasi':
                    colorClass = 'bg-blue-100 text-blue-800';
                    break;
                case 'sedang_berlangsung':
                    colorClass = 'bg-yellow-100 text-yellow-800';
                    break;
                case 'selesai':
                    colorClass = 'bg-green-100 text-green-800';
                    break;
                case 'dibatalkan':
                    colorClass = 'bg-red-100 text-red-800';
                    break;
                case 'pindah_ruangan':
                    colorClass = 'bg-purple-100 text-purple-800';
                    break;
            }

            return `<span class="px-3 py-1 text-xs font-semibold rounded-full ${colorClass}">${label}</span>`;
        }

        /**
         * Format status string to readable text
         */
        function formatStatus(status) {
            if (!status) return 'N/A';
            if (status === 'active') return 'Terjadwal';
            return status.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        // ==========================================
        // SCHEDULE LOADING FUNCTIONS
        // ==========================================

        /**
         * Load jadwal user dari server
         */
        async function loadUserSchedules() {
            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                const bearerToken = localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token');

                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                };

                if (bearerToken) {
                    headers['Authorization'] = `Bearer ${bearerToken}`;
                }

                const response = await fetch('/api/schedules/my-schedules', {
                    method: 'GET',
                    headers: headers,
                    credentials: 'include'
                });

                if (response.status === 401) {
                    console.error('Unauthorized');
                    showEmptyScheduleState();
                    return;
                }

                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const result = await response.json();

                if (result.success && result.data) {
                    currentScheduleData = result.data;
                    updateScheduleTitle(result.data.semester, result.data.academic_year);
                    renderWeeklySchedules(result.data.schedules);
                } else {
                    showEmptyScheduleState();
                }
            } catch (error) {
                console.error('Error loading schedules:', error);
                showEmptyScheduleState();
            }
        }

        //  NEW: API Call untuk confirm jadwal
        async function confirmScheduleAPI(scheduleId, isOverride = false) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            let url = `/api/schedules/${scheduleId}/confirm`;
            if (isOverride) {
                url = `/api/schedules/override/${scheduleId}/confirm`;
            }
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'include' // ← SESSION AUTH
            });
            return await response.json();
        }

        async function cancelScheduleAPI(scheduleId, isOverride = false) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            let url = `/api/schedules/${scheduleId}/cancel`;
            if (isOverride) {
                url = `/api/schedules/override/${scheduleId}/cancel`;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'include'
            });
            return await response.json();
        }

        async function moveToRoomAPI(scheduleId, isOverride = false) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            
            let finalId = scheduleId;
            if (isOverride) {
                finalId = `override-${scheduleId}`;
            }
            
            const response = await fetch(`/api/schedules/${finalId}/move-room`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    reason: 'Pindah Ruangan via Web'
                }),
                credentials: 'include'
            });
            return await response.json();
        }

        async function completeScheduleAPI(scheduleId) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const response = await fetch(`/api/schedules/${scheduleId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'include'
            });
            return await response.json();
        }


        /**
         * Update judul jadwal dengan info semester
         */
        function updateScheduleTitle(semester, academicYear) {
            const titleElement = document.getElementById('jadwal-title');
            if (titleElement) {
                titleElement.textContent = `Jadwal Praktikum Semester ${semester} T.A. ${academicYear}`;
            }
        }

        /**
         * Render jadwal mingguan dengan tab hari
         */
    function renderWeeklySchedules(schedules) {
        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        const dayShort = ['SEN', 'SEL', 'RAB', 'KAM', 'JUM'];
        const scheduleTabsContainer = document.getElementById('schedule-tabs');
        const scheduleContentContainer = document.getElementById('schedule-content');

        if (!scheduleTabsContainer || !scheduleContentContainer) return;
        scheduleTabsContainer.innerHTML = '';
        scheduleContentContainer.innerHTML = '';

        const weekDates = getWeekDates();
        
        // Determine default active day
        const today = new Date().getDay(); // 0=Sun, 1=Mon, ..., 6=Sat
        let defaultActiveIndex = 0; // Default Monday
        
        if (today >= 1 && today <= 5) {
            // Monday (1) to Friday (5) -> map to index 0-4
            defaultActiveIndex = today - 1;
        } else if (today === 6 || today === 0) {
            // Saturday (6) or Sunday (0) -> default to Friday (index 4)
            defaultActiveIndex = 4;
        }

        days.forEach((day, index) => {
            const daySchedules = schedules[day] || [];
            const date = weekDates[index];

            const tabButton = document.createElement('button');
            const isActive = index === defaultActiveIndex;
            
            // Set initial active day global variable
            if (isActive) {
                hariAktif = day;
            }

            tabButton.id = `tab-${day.toLowerCase()}`;
            tabButton.className = `flex-shrink-0 px-3 py-2 rounded-lg font-semibold text-xs min-w-[60px] transition-all duration-200 ${
                isActive 
                    ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' 
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            }`;
                    tabButton.innerHTML = `
                <div class="text-center">
                    <div>${dayShort[index]}</div>
                    <div class="text-xs ${isActive ? 'opacity-90' : 'opacity-70'}">${date}</div>
                </div>
            `;
                    tabButton.onclick = () => ubahHari(day);
                    scheduleTabsContainer.appendChild(tabButton);


                    const contentPanel = document.createElement('div');
                    contentPanel.id = `jadwal-${day.toLowerCase()}`;
                    contentPanel.className = `space-y-4 ${isActive ? '' : 'hidden'}`;

                    if (daySchedules.length > 0) {
                        contentPanel.innerHTML = `
                    <div class="space-y-3">
                        ${daySchedules.map((schedule, idx) => {
                            const showButtons = shouldShowConfirmationButtons(schedule.time_slot, day);
                            // Fix: Use override_id for overrides, schedule_id for regular
                            const scheduleId = schedule.is_override ? schedule.override_id : schedule.schedule_id;
                            const isOverride = schedule.is_override || false;
                            const status = schedule.status;
                            
                            // Determine what to show in the action area
                            let actionContent = '';
                            let borderColor = 'border-gray-400';
                            
                            const isPast = checkIsPast(schedule.time_slot, day);

                            if (status === 'terjadwal') {
                                if (showButtons) {
                                    actionContent = `
                                                    <button onclick="konfirmasiBatal(${scheduleId})"
                                                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-600 to-red-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                                        Batal
                                                    </button>
                                                    <button onclick="konfirmasiAjar(${scheduleId})"
                                                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-green-600 to-green-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                                        Konfirmasi
                                                    </button>
                                                `;
                                } else if (isPast) {
                                    actionContent = getStatusBadge(status);
                                } else {
                                    actionContent = ` < span class = "text-xs text-gray-500 text-center py-2" >
                            Belum waktunya konfirmasi < /span>`;
                }
            } else if (status === 'dikonfirmasi') {
                // After confirmation - waiting for QR scan
                borderColor = 'border-green-500';
                actionContent = `
                                                <a href="/scan-qr" class="flex items-center gap-2 text-sm px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition cursor-pointer">
                                                    <svg class="w-5 h-5 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                                    </svg>
                                                    <span class="text-blue-600 font-medium">Menunggu Scan QR →</span>
                                                </a>
                                            `;
            } else if (status === 'sedang_berlangsung') {
                // After QR scan - show Pindah Ruangan & Selesai buttons
                borderColor = 'border-yellow-500';

                actionContent = '';

                if (!isOverride) {
                    actionContent += `
                                                <button onclick="pindahRuangan(${scheduleId})"
                                                    class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-600 to-red-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                                    Pindah Ruangan
                                                </button>`;
                }



                if (status !== 'pindah_ruangan') {
                    actionContent += `
                                            <button onclick="selesaiKelas(${scheduleId}, ${isOverride})"
                                                class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                                Selesai
                                            </button>`;
                }
            } else {
                if (status === 'selesai') {
                    borderColor = 'border-green-500';
                    actionContent = getStatusBadge(status);
                } else if (status === 'dibatalkan') {
                    borderColor = 'border-red-500';
                    actionContent = getStatusBadge(status);
                } else if (status === 'pindah_ruangan') {
                    borderColor = 'border-purple-500';
                    actionContent = `
                        <a href="/scan-qr" class="flex items-center gap-2 text-sm px-4 py-2 bg-purple-50 hover:bg-purple-100 rounded-lg transition cursor-pointer">
                            <svg class="w-5 h-5 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            <span class="text-purple-600 font-medium">Menunggu Scan QR (Ruangan Baru) →</span>
                        </a>
                    `;
                } else {
                    actionContent = getStatusBadge(status);
                }
            }

            const domId = schedule.is_override ? `override-${schedule.override_id}` : schedule.schedule_id;

            return `
                <div id="jadwal-${domId}" 
                    class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 ${borderColor} shadow-sm">
                    {{-- Bagian Kiri (Info) --}}
                    <div class="flex-grow w-full">
                        <p class="font-bold text-gray-800">
                            Mata Kuliah: ${schedule.course_name}
                            ${schedule.is_substitute ? '<span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded-full">Kelas Pengganti</span>' : ''}
                            ${schedule.is_override && !schedule.is_substitute ? '<span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Pindah Ruangan</span>' : ''}
                        </p>
                        <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mt-2">
                            <span class="flex items-center gap-1.5 bg-gray-200 px-2 py-1 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg> 
                                ${schedule.time_slot}
                            </span>
                            <span class="bg-gray-200 px-2 py-1 rounded-md">${schedule.class_name || 'N/A'}</span>
                            <span class="bg-gray-200 px-2 py-1 rounded-md">${schedule.room_name}</span>
                        </div>
                        <div class="mt-3">
                            <span id="status-jadwal-${domId}" 
                                class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                ${formatStatus(status)}
                            </span>
                        </div>
                    </div>
                    {{-- Bagian Kanan (Tombol) - Button akan berubah sesuai status --}}
                    <div id="tombol-jadwal-${domId}" 
                        class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto items-center justify-center">
                        ${actionContent}
                    </div>
                </div>
            `;
        }).join('')
    }
                    </div>
                `;
                        } else {
                            contentPanel.innerHTML = `
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <p class="text-gray-600 text-lg">Tidak ada kelas mengajar hari ini</p>
                    </div>
                `;
                        }

                        scheduleContentContainer.appendChild(contentPanel);
                    });
                }
            

    /**
     * Show empty state ketika tidak ada jadwal
     */
    function showEmptyScheduleState() {
        const scheduleTabsContainer = document.getElementById('schedule-tabs');
        const scheduleContentContainer = document.getElementById('schedule-content');
        
        if (scheduleTabsContainer) {
            scheduleTabsContainer.innerHTML = '';
        }
        
        if (scheduleContentContainer) {
            scheduleContentContainer.innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-lg font-medium">Belum ada jadwal</p>
                    <p class="text-sm">Silakan ambil jadwal terlebih dahulu</p>
                </div>
            `;
        }
    }

    // ==========================================
    // TAB SWITCHING FUNCTION
    // ==========================================
    
    /**
     * Fungsi untuk mengubah hari yang aktif
     */
                function ubahHari(hari) {
                    
                    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; //Testing sabtu Minggu
                    
                    days.forEach(h => {
                        const jadwalEl = document.getElementById(`jadwal-${h.toLowerCase()}`);
                        if (jadwalEl) {
                            jadwalEl.classList.add('hidden');
                        }
                    });
                    
                    days.forEach(h => {
                        const tabEl = document.getElementById(`tab-${h.toLowerCase()}`);
                        if (tabEl) {
                            tabEl.className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300 transition-all duration-200';
                        }
                    });
                    
                    const activeJadwal = document.getElementById(`jadwal-${hari.toLowerCase()}`);
                    if (activeJadwal) {
                        activeJadwal.classList.remove('hidden');
                    }
                    
                    const activeTab = document.getElementById(`tab-${hari.toLowerCase()}`);
                    if (activeTab) {
                        activeTab.className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold text-xs min-w-[60px] transition-all duration-200';
                    }
                    
                    hariAktif = hari;
                    
                    // REFRESH: Update status dan tombol untuk hari yang dipilih
                    refreshDaySchedules(hari);
                }
                /**
                 * Refresh schedule buttons and status for a specific day
                 */
                function refreshDaySchedules(dayName) {
                    // Get schedules for this day from currentScheduleData
                    if (!currentScheduleData || !currentScheduleData.schedules) return;
                    
                    const daySchedules = currentScheduleData.schedules[dayName] || [];
                    
                    daySchedules.forEach(schedule => {
                        const isOverride = schedule.is_override || false;
                        // Fix: Use override_id for overrides, schedule_id for regular
                        const scheduleId = isOverride ? schedule.override_id : schedule.schedule_id;
                        const status = schedule.status;
                        const showButtons = shouldShowConfirmationButtons(schedule.time_slot, dayName);
                        const isPast = checkIsPast(schedule.time_slot, dayName);
                        
                        const domId = isOverride ? `override-${schedule.override_id}` : schedule.schedule_id;
                        
                        const buttonDiv = document.getElementById(`tombol-jadwal-${domId}`);
                        const scheduleDiv = document.getElementById(`jadwal-${domId}`);
                        if (!buttonDiv || !scheduleDiv) return;
                        
                        let actionContent = '';
                        let borderColor = 'border-gray-400';
                        
                        if (status === 'terjadwal') {
                            if (showButtons) {
                                actionContent = `
                                    <button onclick="konfirmasiBatal(${scheduleId})"
                                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-600 to-red-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                        Batal
                                    </button>
                                    <button onclick="konfirmasiAjar(${scheduleId})"
                                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-green-600 to-green-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                        Konfirmasi
                                    </button>
                                `;
                            } else if (isPast) {
                                actionContent = getStatusBadge(status);
                            } else {
                                actionContent = `<span class="text-xs text-gray-500 text-center py-2">Belum waktunya konfirmasi</span>`;
        }
        }
        else if (status === 'dikonfirmasi') {
            borderColor = 'border-green-500';
            actionContent = `
                    <a href="/scan-qr" class="flex items-center gap-2 text-sm px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition cursor-pointer">
                        <svg class="w-5 h-5 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-blue-600 font-medium">Menunggu Scan QR →</span>
                    </a>
                `;
        } else if (status === 'sedang_berlangsung') {
            borderColor = 'border-yellow-500';

            actionContent = '';
            if (!isOverride) {
                actionContent += `
                    <button onclick="pindahRuangan(${scheduleId})"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-600 to-red-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Pindah Ruangan
                    </button>`;
            }

            actionContent += `
                <button onclick="selesaiKelas(${scheduleId}, ${isOverride})"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                    Selesai
                </button>
                `;
        } else {
            if (status === 'selesai') {
                borderColor = 'border-green-500';
                actionContent = getStatusBadge(status);
            } else if (status === 'dibatalkan') {
                borderColor = 'border-red-500';
                actionContent = getStatusBadge(status);
            } else if (status === 'pindah_ruangan') {
                borderColor = 'border-purple-500';
                actionContent = `<span class="text-xs text-purple-600 font-medium">Dipindahkan ke Ruangan Lain</span>`;
            } else if (status === 'moved_out') {
                borderColor = 'border-gray-400';
                actionContent = getStatusBadge(status);
            } else {
                actionContent = getStatusBadge(status);
            }
        }

        buttonDiv.innerHTML = actionContent;

        // Update border color
        scheduleDiv.className = scheduleDiv.className.replace(/border-(gray|green|red|yellow|purple)-\d+/, borderColor);
        });
        }

        // ==========================================
        // MODAL FUNCTIONS
        // ==========================================

        /**
         * Tampilkan modal konfirmasi
         */
        function tampilkanModal(title, message, callback) {
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-message').textContent = message;
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            const modalYa = document.getElementById('modal-ya');
            const modalBatal = document.getElementById('modal-batal');

            const newModalYa = modalYa.cloneNode(true);
            const newModalBatal = modalBatal.cloneNode(true);

            modalYa.parentNode.replaceChild(newModalYa, modalYa);
            modalBatal.parentNode.replaceChild(newModalBatal, modalBatal);

            document.getElementById('modal-ya').addEventListener('click', function() {
                callback();
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            document.getElementById('modal-batal').addEventListener('click', function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }

        /**
         * Tampilkan modal sukses
         */
        function tampilkanModalSukses(title, message) {
            document.getElementById('sukses-title').textContent = title;
            document.getElementById('sukses-message').textContent = message;
            const modal = document.getElementById('modal-sukses');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        /**
         * Tutup modal sukses
         */
        function tutupModalSukses() {
            const modal = document.getElementById('modal-sukses');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        /**
         * Tampilkan modal error
         */
        function tampilkanModalError(title, message) {
            document.getElementById('error-title').textContent = title;
            document.getElementById('error-message').textContent = message;
            const modal = document.getElementById('modal-error');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        /**
         * Tutup modal error
         */
        function tutupModalError() {
            const modal = document.getElementById('modal-error');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

    /**
     * Tampilkan modal sukses
     */
    function tampilkanModalSukses(title, message) {
        document.getElementById('sukses-title').textContent = title;
        document.getElementById('sukses-message').textContent = message;
        const modal = document.getElementById('modal-sukses');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /**
     * Tutup modal sukses
     */
    function tutupModalSukses() {
        const modal = document.getElementById('modal-sukses');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /**
     * Tampilkan modal error
     */
    function tampilkanModalError(title, message) {
        document.getElementById('error-title').textContent = title;
        document.getElementById('error-message').textContent = message;
        const modal = document.getElementById('modal-error');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /**
     * Tutup modal error
     */
    function tutupModalError() {
        const modal = document.getElementById('modal-error');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /**
     * Buka modal ganti kata sandi
     */
    function bukaModalGantiSandi() {
        const modal = document.getElementById('modal-ganti-sandi');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        resetValidasi();
        
        // Focus ke input pertama
        setTimeout(() => {
            document.getElementById('sandi-lama').focus();
        }, 100);
    }

    /**
     * Tutup modal ganti kata sandi
     */
    function tutupModalGantiSandi() {
        const modal = document.getElementById('modal-ganti-sandi');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('form-ganti-sandi').reset();
        resetValidasi();
    }

    // ==========================================
    // PASSWORD VALIDATION FUNCTIONS
    // ==========================================
    
    /**
     * Reset semua validasi
     */
    function resetValidasi() {
        isSandiLamaValid = false;
        isSandiBaruValid = false;
        isKonfirmasiValid = false;
        updateSubmitButton();
        
        // Reset semua indikator
        const indicators = document.querySelectorAll('[id^="indicator-"]');
        indicators.forEach(indicator => {
            indicator.className = 'w-3 h-3 rounded-full bg-gray-300 mr-2';
        });
        
        const texts = document.querySelectorAll('[id^="text-"]');
        texts.forEach(text => {
            if (text.id === 'text-length' || text.id === 'text-letter' || 
                text.id === 'text-number' || text.id === 'text-special') {
                text.className = 'text-gray-600';
            }
        });
        
        // Reset error messages
        const errorElements = document.querySelectorAll('[id^="error-"]');
        errorElements.forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });
    }

    /**
     * Validasi kata sandi lama
     */
    function validateSandiLama(sandi) {
        const errorElement = document.getElementById('error-sandi-lama');
        
        if (sandi.length === 0) {
            showError(errorElement, 'Kata sandi lama harus diisi');
            isSandiLamaValid = false;
        } else if (sandi.length < 3) {
            showError(errorElement, 'Kata sandi lama terlalu pendek');
            isSandiLamaValid = false;
        } else {
            hideError(errorElement);
            isSandiLamaValid = true;
        }
        
        updateSubmitButton();
    }

    /**
     * Validasi kata sandi baru
     */
    function validateSandiBaru(sandi) {
        const errorElement = document.getElementById('error-sandi-baru');
        
        // Validasi panjang
        const isLengthValid = sandi.length >= 6;
        updateIndicator('length', isLengthValid, 'Minimal 6 karakter');
        
        // Validasi huruf
        const hasLetter = /[a-zA-Z]/.test(sandi);
        updateIndicator('letter', hasLetter, 'Mengandung huruf');
        
        // Validasi angka
        const hasNumber = /[0-9]/.test(sandi);
        updateIndicator('number', hasNumber, 'Mengandung angka');
        
        // Validasi karakter khusus (!$@%)
        const hasSpecial = /[!$@%]/.test(sandi);
        updateIndicator('special', hasSpecial, 'Mengandung karakter khusus (!$@%)');
        
        // Overall validation
        const isValid = isLengthValid && hasLetter && hasNumber && hasSpecial;
        
        if (sandi.length === 0) {
            hideError(errorElement);
            isSandiBaruValid = false;
        } else if (!isValid) {
            showError(errorElement, 'Kata sandi tidak memenuhi semua persyaratan');
            isSandiBaruValid = false;
        } else {
            hideError(errorElement);
            isSandiBaruValid = true;
        }
        
        updateSubmitButton();
    }

    /**
     * Validasi konfirmasi kata sandi
     */
    function validateKonfirmasiSandi() {
        const sandiBaru = document.getElementById('sandi-baru').value;
        const konfirmasi = document.getElementById('konfirmasi-sandi').value;
        const errorElement = document.getElementById('error-konfirmasi-sandi');
        
        if (konfirmasi.length === 0) {
            hideError(errorElement);
            isKonfirmasiValid = false;
            updateSubmitButton();

            // Reset semua indikator
            const indicators = document.querySelectorAll('[id^="indicator-"]');
            indicators.forEach(indicator => {
                indicator.className = 'w-3 h-3 rounded-full bg-gray-300 mr-2';
            });

            const texts = document.querySelectorAll('[id^="text-"]');
            texts.forEach(text => {
                if (text.id === 'text-length' || text.id === 'text-letter' ||
                    text.id === 'text-number' || text.id === 'text-special') {
                    text.className = 'text-gray-600';
                }
            });

            // Reset error messages
            const errorElements = document.querySelectorAll('[id^="error-"]');
            errorElements.forEach(error => {
                error.classList.add('hidden');
                error.textContent = '';
            });
        }
    }

    /**
     * Update indikator visual untuk validasi password
     */
    function updateIndicator(type, isValid, text) {
        const indicator = document.getElementById(`indicator-${type}`);
        const textElement = document.getElementById(`text-${type}`);
        
        if (isValid) {
            indicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
            textElement.className = 'text-green-600';
        } else {
            indicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
            textElement.className = 'text-red-600';
        }
    }

        /**
         * Validasi kata sandi baru
         */
        function validateSandiBaru(sandi) {
            const errorElement = document.getElementById('error-sandi-baru');

            // Validasi panjang
            const isLengthValid = sandi.length >= 6;
            updateIndicator('length', isLengthValid, 'Minimal 6 karakter');

            // Validasi huruf
            const hasLetter = /[a-zA-Z]/.test(sandi);
            updateIndicator('letter', hasLetter, 'Mengandung huruf');

            // Validasi angka
            const hasNumber = /[0-9]/.test(sandi);
            updateIndicator('number', hasNumber, 'Mengandung angka');

            // Validasi karakter khusus (!$@%)
            const hasSpecial = /[!$@%]/.test(sandi);
            updateIndicator('special', hasSpecial, 'Mengandung karakter khusus (!$@%)');

            // Overall validation
            const isValid = isLengthValid && hasLetter && hasNumber && hasSpecial;

            if (sandi.length === 0) {
                hideError(errorElement);
                isSandiBaruValid = false;
            } else if (!isValid) {
                showError(errorElement, 'Kata sandi tidak memenuhi semua persyaratan');
                isSandiBaruValid = false;
            } else {
                hideError(errorElement);
                isSandiBaruValid = true;
            }

            updateSubmitButton();
        }

        /**
         * Validasi konfirmasi kata sandi
         */
        function validateKonfirmasiSandi() {
            const sandiBaru = document.getElementById('sandi-baru').value;
            const konfirmasi = document.getElementById('konfirmasi-sandi').value;
            const errorElement = document.getElementById('error-konfirmasi-sandi');

            if (konfirmasi.length === 0) {
                hideError(errorElement);
                isKonfirmasiValid = false;
            } else if (konfirmasi !== sandiBaru) {
                showError(errorElement, 'Kata sandi tidak cocok');
                isKonfirmasiValid = false;
            } else {
                hideError(errorElement);
                isKonfirmasiValid = true;
            }

            updateSubmitButton();
        }

        /**
         * Update indikator visual untuk validasi password
         */
        function updateIndicator(type, isValid, text) {
            const indicator = document.getElementById(`indicator-${type}`);
            const textElement = document.getElementById(`text-${type}`);

            if (isValid) {
                indicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
                textElement.className = 'text-green-600';
            } else {
                indicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
                textElement.className = 'text-red-600';
            }
        }

        /**
         * Tampilkan error message
         */
        function showError(errorElement, message) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        /**
         * Sembunyikan error message
         */
        function hideError(errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }

        /**
         * Update status tombol submit
         */
        function updateSubmitButton() {
            const submitButton = document.getElementById('submit-ganti-sandi');
            const isValid = isSandiLamaValid && isSandiBaruValid && isKonfirmasiValid;

            if (isValid) {
                submitButton.disabled = false;
                submitButton.className =
                    'px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition';
            } else {
                submitButton.disabled = true;
                submitButton.className =
                    'px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg cursor-not-allowed transition';
            }
        }

        // ==========================================
        // SCHEDULE ACTION FUNCTIONS
        // ==========================================

        /**
         * Konfirmasi batal - Ketika user membatalkan jadwal mengajar
         */
        async function konfirmasiBatal(scheduleId) {
            if (!confirm('Batalkan jadwal ini?')) return;

            const result = await cancelScheduleAPI(scheduleId);

            if (result.success) {
                alert(' Jadwal berhasil dibatalkan');

                //  UPDATE UI
                const statusBadge = document.getElementById(`status-jadwal-${scheduleId}`);
                const buttonDiv = document.getElementById(`tombol-jadwal-${scheduleId}`);

                if (statusBadge) {
                    statusBadge.textContent = 'Dibatalkan';
                    statusBadge.className = 'px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full';
                }

                if (buttonDiv) {
                    buttonDiv.innerHTML =
                        `<span class="text-xs text-gray-500 text-center py-2">❌ Jadwal Dibatalkan</span>`;
                }

                if (scheduleDiv) {
                    scheduleDiv.style.opacity = '0.6';
                    scheduleDiv.style.pointerEvents = 'none';
                }

            } else {
                alert('❌ ' + result.message);
            }
        }


        /**
         * Konfirmasi ajar - Ketika user mengkonfirmasi akan mengajar
         * Setelah konfirmasi, button berubah menjadi Pindah Ruangan & Selesai (setelah scan QR)
         */
        async function konfirmasiAjar(scheduleId) {
            if (!confirm('Anda yakin akan mengajar?')) return;

            const result = await confirmScheduleAPI(scheduleId);

            if (result.success) {
                alert(' Jadwal berhasil dikonfirmasi! Silakan scan QR di ruangan.');

                //  UPDATE UI IMMEDIATELY
                const statusBadge = document.getElementById(`status-jadwal-${scheduleId}`);
                const buttonDiv = document.getElementById(`tombol-jadwal-${scheduleId}`);
                const scheduleDiv = document.getElementById(`jadwal-${scheduleId}`);

                if (statusBadge) {
                    statusBadge.textContent = 'Dikonfirmasi';
                    statusBadge.className = 'px-3 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full';
                }

                //  UPDATE BORDER to green
                if (scheduleDiv) {
                    scheduleDiv.className = scheduleDiv.className.replace(/border-(gray|green|red|yellow|purple)-\d+/,
                        'border-green-500');
                }

                //  UPDATE BUTTONS to "Menunggu Scan QR"
                if (buttonDiv) {
                    buttonDiv.innerHTML = `
                    <a href="/scan-qr" class="flex items-center gap-2 text-sm px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition cursor-pointer">
                        <svg class="w-5 h-5 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-blue-600 font-medium">Menunggu Scan QR →</span>
                    </a>
                `;
                }

            } else {
                alert('❌ ' + result.message);
            }
        }

        /**
         * Pindah Ruangan - Ketika user ingin pindah ruangan saat sedang berlangsung
         */
        async function pindahRuangan(scheduleId) {
            if (!confirm('Pindah ke ruangan lain? Status akan berubah menjadi "Pindah Ruangan".')) return;

            const result = await moveToRoomAPI(scheduleId);

            if (result.success) {
                alert(' Status berubah menjadi Pindah Ruangan. Silakan scan QR di ruangan baru.');

                // Update UI
                const statusBadge = document.getElementById(`status-jadwal-${scheduleId}`);
                const scheduleDiv = document.getElementById(`jadwal-${scheduleId}`);
                const buttonDiv = document.getElementById(`tombol-jadwal-${scheduleId}`);

                if (statusBadge) {
                    statusBadge.textContent = 'Pindah Ruangan';
                    statusBadge.className = 'px-3 py-1 text-xs font-semibold text-white bg-purple-500 rounded-full';
                }

                if (scheduleDiv) {
                    scheduleDiv.className = scheduleDiv.className.replace(/border-(gray|green|red|yellow|purple)-\d+/,
                        'border-purple-500');
                }

                if (buttonDiv) {
                    buttonDiv.innerHTML = `
                    <a href="/scan-qr" class="flex items-center gap-2 text-sm px-4 py-2 bg-purple-50 hover:bg-purple-100 rounded-lg transition cursor-pointer">
                        <svg class="w-5 h-5 text-purple-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-purple-600 font-medium">Menunggu Scan QR (Ruangan Baru) →</span>
                    </a>
                `;
                }
            } else {
                alert('❌ ' + result.message);
            }
        }

        /**
         * Selesai Kelas - Ketika user menyelesaikan kelas
         */
        async function selesaiKelas(scheduleId, isOverride = false) {
            if (!confirm('Tandai jadwal ini sebagai selesai?')) return;

            let url = `/api/schedules/${scheduleId}/complete`;
            if (isOverride) {
                url = `/api/schedules/override/${scheduleId}/complete`;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ Jadwal berhasil diselesaikan!');
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    alert('❌ ' + (result.message || 'Gagal menyelesaikan jadwal'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan sistem');
            }
        }

        /**
         * Update status jadwal dan tampilan button
         */
        function updateStatusJadwal(id, aksi) {
            const statusElement = document.getElementById(`status-jadwal-${id}`);
            const jadwalElement = document.getElementById(`jadwal-${id}`);
            const tombolElement = document.getElementById(`tombol-jadwal-${id}`);

            if (!statusElement || !jadwalElement || !tombolElement) return;

            // Update status badge dan border warna
            if (aksi === 'dibatalkan') {
                statusElement.textContent = 'Dibatalkan';
                statusElement.className = 'px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full';
                jadwalElement.classList.remove('border-gray-400', 'border-green-500', 'border-blue-500');
                jadwalElement.classList.add('border-red-500');

                // Nonaktifkan semua button setelah dibatalkan
                tombolElement.innerHTML = '<span class="text-xs text-gray-500 text-center py-2">Jadwal dibatalkan</span>';

            } else if (aksi === 'sudah-dikonfirmasi') {
                statusElement.textContent = 'Menunggu Scan QR';
                statusElement.className = 'px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-200 rounded-full';
                jadwalElement.classList.remove('border-red-500', 'border-blue-500', 'border-green-500');
                jadwalElement.classList.add('border-yellow-500');

                // Setelah konfirmasi, button berubah menjadi info scan QR
                // Button Pindah Ruangan & Selesai akan muncul setelah scan QR (handled by backend)
                tombolElement.innerHTML = `
                <div class="text-center w-full">
                    <p class="text-xs text-gray-600 mb-2">Silakan scan QR di ruangan untuk memulai</p>
                    <button onclick="window.location.href='/scan-qr'" 
                        class="w-full px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition">
                        Scan QR Sekarang
                    </button>
                </div>
            `;

            } else if (aksi === 'setelah-scan-qr') {
                // Status setelah scan QR berhasil - Button berubah jadi Pindah Ruangan & Selesai
                statusElement.textContent = 'Sedang Berlangsung';
                statusElement.className = 'px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full';
                jadwalElement.classList.remove('border-gray-400', 'border-red-500', 'border-yellow-500',
                    'border-green-500');
                jadwalElement.classList.add('border-blue-500');

                tombolElement.innerHTML = `
                <button onclick="konfirmasiPindahRuangan('${id}')"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90 transition min-w-[135px]">
                    Pindah Ruangan
                </button>
                <button onclick="konfirmasiSelesai('${id}')"
                    class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-green-600 to-green-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                    Selesai
                </button>
            `;

            } else if (aksi === 'pindah-ruangan') {
                statusElement.textContent = 'Pindah Ruangan';
                statusElement.className = 'px-3 py-1 text-xs font-semibold text-purple-800 bg-purple-200 rounded-full';
                jadwalElement.classList.remove('border-green-500', 'border-gray-400', 'border-blue-500');
                jadwalElement.classList.add('border-purple-500');

                // Setelah klik pindah ruangan, suruh scan QR lagi
                tombolElement.innerHTML = `
                <div class="text-center w-full">
                    <p class="text-xs text-gray-600 mb-2">Scan QR ruangan baru</p>
                    <button onclick="window.location.href='/scan-qr'" 
                        class="w-full px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-purple-400 rounded-lg hover:opacity-90 transition">
                        Scan QR Ruangan Baru
                    </button>
                </div>
            `;

            } else if (aksi === 'selesai') {
                statusElement.textContent = 'Kelas Selesai';
                statusElement.className = 'px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full';
                jadwalElement.classList.remove('border-blue-500', 'border-gray-400', 'border-red-500');
                jadwalElement.classList.add('border-green-500');

                // Setelah selesai, tidak ada button lagi
                tombolElement.innerHTML =
                    '<span class="text-xs text-green-600 text-center py-2">✓ Kelas telah selesai</span>';
            }
        }

        // ==========================================
        // EVENT LISTENERS
        // ==========================================

        // Real-time validation untuk kata sandi baru
        document.getElementById('sandi-baru').addEventListener('input', function(e) {
            const sandi = e.target.value;
            validateSandiBaru(sandi);
            validateKonfirmasiSandi();
        });

        // Real-time validation untuk konfirmasi sandi
        document.getElementById('konfirmasi-sandi').addEventListener('input', function(e) {
            validateKonfirmasiSandi();
        });

        // Real-time validation untuk sandi lama
        document.getElementById('sandi-lama').addEventListener('input', function(e) {
            validateSandiLama(e.target.value);
        });

        // Handle form ganti kata sandi
        document.getElementById('form-ganti-sandi').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Final validation sebelum submit
            const sandiLama = document.getElementById('sandi-lama').value;
            const sandiBaru = document.getElementById('sandi-baru').value;
            const konfirmasiSandi = document.getElementById('konfirmasi-sandi').value;

            validateSandiLama(sandiLama);
            validateSandiBaru(sandiBaru);
            validateKonfirmasiSandi();

            if (!isSandiLamaValid || !isSandiBaruValid || !isKonfirmasiValid) {
                tampilkanModalError('Validasi Gagal', 'Harap lengkapi semua field dengan benar.');
                return;
            }

            // Disable submit button
            const submitButton = document.getElementById('submit-ganti-sandi');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Memproses...';

            try {
                console.log('Mengirim request ganti password...');

                const response = await fetch(`{{ route('profile.password.update') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        current_password: sandiLama,
                        new_password: sandiBaru,
                        new_password_confirmation: konfirmasiSandi,
                        _method: 'PUT'
                    })
                });

                const result = await response.json();
                console.log('Response dari server:', result);

                if (response.ok && result.success) {
                    console.log('Password berhasil diupdate');
                    tutupModalGantiSandi();
                    tampilkanModalSukses('Berhasil!', 'Kata sandi berhasil diganti.');
                    document.getElementById('form-ganti-sandi').reset();
                    resetValidasi();
                } else {
                    console.log('Password update gagal:', result);

                    // Reset semua error terlebih dahulu
                    const errorElements = document.querySelectorAll('[id^="error-"]');
                    errorElements.forEach(error => {
                        error.classList.add('hidden');
                        error.textContent = '';
                    });

                    // Handle validation errors dari server
                    if (result.errors) {
                        if (result.errors.current_password) {
                            showError(document.getElementById('error-sandi-lama'), result.errors
                                .current_password[0]);
                        }
                        if (result.errors.new_password) {
                            showError(document.getElementById('error-sandi-baru'), result.errors.new_password[
                                0]);
                        }
                        if (result.errors.new_password_confirmation) {
                            showError(document.getElementById('error-konfirmasi-sandi'), result.errors
                                .new_password_confirmation[0]);
                        }

                        // Jika ada error umum
                        if (result.message && !result.errors.current_password && !result.errors.new_password &&
                            !result.errors.new_password_confirmation) {
                            tampilkanModalError('Gagal!', result.message);
                        } else {
                            tampilkanModalError('Validasi Gagal', 'Harap perbaiki error di form.');
                        }
                    } else {
                        tampilkanModalError('Gagal!', result.message || 'Gagal mengganti kata sandi.');
                    }
                }
            } catch (error) {
                console.error('Error updating password:', error);
                tampilkanModalError('Koneksi Error', 'Gagal menghubungi server. Silakan coba lagi.');
            } finally {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                updateSubmitButton();
            }
        });

        // Tutup modal ketika klik di luar (hanya untuk modal konfirmasi)
        document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
            if (e.target.id === 'modal-konfirmasi') {
                const modal = document.getElementById('modal-konfirmasi');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });

        // Modal ganti sandi tidak bisa ditutup dengan klik di luar
        document.getElementById('modal-ganti-sandi').addEventListener('click', function(e) {
            if (e.target.id === 'modal-ganti-sandi') {
                // Tidak melakukan apa-apa, modal tidak bisa ditutup dengan klik di luar
            }
        });

        // Modal sukses dan error tidak bisa ditutup dengan klik di luar
        document.getElementById('modal-sukses').addEventListener('click', function(e) {
            if (e.target.id === 'modal-sukses') {
                // Tidak melakukan apa-apa, modal tidak bisa ditutup dengan klik di luar
            }
        });

        document.getElementById('modal-error').addEventListener('click', function(e) {
            if (e.target.id === 'modal-error') {
                // Tidak melakukan apa-apa, modal tidak bisa ditutup dengan klik di luar
            }
        });

        // ==========================================
        // INITIALIZATION
        // ==========================================
    </script>

@endsection
