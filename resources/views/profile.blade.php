@extends('layouts.main')

@section('title', 'Profil Pengguna')

@section('content')

<div class="space-y-6">

    {{-- KARTU INFORMASI PROFIL --}}
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex flex-col md:flex-row items-center gap-6">
            {{-- Foto Profil --}}
            <div class="flex-shrink-0">
                <div class="h-24 w-24 rounded-full bg-gradient-to-r from-blue-900 to-red-700 flex items-center justify-center border-4 border-gray-200 text-white text-2xl font-bold">
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
                    class="flex items-center gap-2 px-4 py-2 bg-gr  ient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg shadow-md hover:opacity-90 transition">
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
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold shadow-md transition">
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

    // ==========================================
    // SCHEDULE LOADING FUNCTIONS
    // ==========================================
    
    /**
     * Load jadwal user dari server
     */
    async function loadUserSchedules() {
        try {
            const response = await fetch('/api/schedules/my-schedules');
            const result = await response.json();

            if (result.success && result.data) {
                currentScheduleData = result.data;
                updateScheduleTitle(result.data.semester, result.data.academic_year);
                renderWeeklySchedules(result.data.schedules);
            } else {
                showEmptyScheduleState();
            }
        } catch (error) {
            console.error('Error loading user schedules:', error);
            showEmptyScheduleState();
        }
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

        // Clear existing content
        scheduleTabsContainer.innerHTML = '';
        scheduleContentContainer.innerHTML = '';

        // Get current week dates
        const weekDates = getWeekDates();

        days.forEach((day, index) => {
            const daySchedules = schedules[day] || [];
            const date = weekDates[index];

            // Create tab button dengan tanggal real-time
            const tabButton = document.createElement('button');
            const isActive = index === 0;
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

            // Create content panel
            const contentPanel = document.createElement('div');
            contentPanel.id = `jadwal-${day.toLowerCase()}`;
            contentPanel.className = `space-y-4 ${isActive ? '' : 'hidden'}`;

            if (daySchedules.length > 0) {
                contentPanel.innerHTML = `
                    <div class="space-y-3">
                        ${daySchedules.map((schedule, idx) => {
                            const showButtons = shouldShowConfirmationButtons(schedule.time_slot, day);
                            const scheduleId = `${day.toLowerCase()}-${idx}`;
                            
                            return `
                            <div id="jadwal-${scheduleId}" 
                                class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 border-gray-400 shadow-sm">
                                {{-- Bagian Kiri (Info) --}}
                                <div class="flex-grow w-full">
                                    <p class="font-bold text-gray-800">Mata Kuliah: ${schedule.course_name}</p>
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
                                        <span id="status-jadwal-${scheduleId}" 
                                            class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                            Terjadwal
                                        </span>
                                    </div>
                                </div>
                                {{-- Bagian Kanan (Tombol) - Button akan berubah sesuai status --}}
                                <div id="tombol-jadwal-${scheduleId}" 
                                    class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    ${showButtons ? `
                                        <!-- Default: Batal dan Konfirmasi (sebelum scan QR) -->
                                        <button onclick="konfirmasiBatal('${scheduleId}')"
                                            class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-600 to-red-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                            Batal
                                        </button>
                                        <button onclick="konfirmasiAjar('${scheduleId}')"
                                            class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-green-600 to-green-400 rounded-lg hover:opacity-90 transition min-w-[135px]">
                                            Konfirmasi
                                        </button>
                                    ` : `
                                        <span class="text-xs text-gray-500 text-center py-2">Belum waktunya konfirmasi</span>
                                    `}
                                </div>
                            </div>
                        `;
                        }).join('')}
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

        if (days.length > 0) {
            hariAktif = days[0];
        }
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
        const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        // Sembunyikan semua jadwal
        days.forEach(h => {
            const jadwalEl = document.getElementById(`jadwal-${h.toLowerCase()}`);
            if (jadwalEl) {
                jadwalEl.classList.add('hidden');
            }
        });
        
        // Reset semua tab menjadi abu-abu
        days.forEach(h => {
            const tabEl = document.getElementById(`tab-${h.toLowerCase()}`);
            if (tabEl) {
                tabEl.className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300 transition-all duration-200';
            }
        });
        
        // Tampilkan jadwal hari yang dipilih
        const activeJadwal = document.getElementById(`jadwal-${hari.toLowerCase()}`);
        if (activeJadwal) {
            activeJadwal.classList.remove('hidden');
        }
        
        // Aktifkan tab yang dipilih
        const activeTab = document.getElementById(`tab-${hari.toLowerCase()}`);
        if (activeTab) {
            activeTab.className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold text-xs min-w-[60px] transition-all duration-200';
        }
        
        hariAktif = hari;
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
            submitButton.className = 'px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition';
        } else {
            submitButton.disabled = true;
            submitButton.className = 'px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg cursor-not-allowed transition';
        }
    }

    // ==========================================
    // SCHEDULE ACTION FUNCTIONS
    // ==========================================
    
    /**
     * Konfirmasi batal - Ketika user membatalkan jadwal mengajar
     */
    function konfirmasiBatal(id) {
        tampilkanModal(
            'Batalkan Kelas',
            'Apakah Anda yakin akan membatalkan kelas ini?',
            function() {
                updateStatusJadwal(id, 'dibatalkan');
                tampilkanModalSukses('Telah Dibatalkan', 'Kelas telah dibatalkan.');
            }
        );
    }

    /**
     * Konfirmasi ajar - Ketika user mengkonfirmasi akan mengajar
     * Setelah konfirmasi, button berubah menjadi Pindah Ruangan & Selesai (setelah scan QR)
     */
    function konfirmasiAjar(id) {
        tampilkanModal(
            'Konfirmasi Mengajar',
            'Konfirmasi kehadiran Anda untuk praktikum sesi ini.',
            function() {
                updateStatusJadwal(id, 'sudah-dikonfirmasi');
                tampilkanModalSukses('Terkonfirmasi!', 'Kelas telah dikonfirmasi. Jangan lupa scan QR di ruangan.');
            }
        );
    }

    /**
     * Konfirmasi pindah ruangan - Muncul setelah user scan QR dan ingin pindah ruangan
     */
    function konfirmasiPindahRuangan(id) {
        tampilkanModal(
            'Pindah Ruangan',
            'Apakah Anda yakin ingin pindah ruangan?',
            function() {
                updateStatusJadwal(id, 'pindah-ruangan');
                tampilkanModalSukses('Berhasil!', 'Permintaan pindah ruangan telah dikirim. Silakan scan QR ruangan baru.');
            }
        );
    }

    /**
     * Konfirmasi selesai - Ketika user menyelesaikan kelas
     */
    function konfirmasiSelesai(id) {
        tampilkanModal(
            'Selesai Kelas',
            'Apakah Anda yakin untuk menyelesaikan kelas ini?',
            function() {
                updateStatusJadwal(id, 'selesai');
                tampilkanModalSukses('Berhasil!', 'Kelas telah diselesaikan.');
            }
        );
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
            jadwalElement.classList.remove('border-gray-400', 'border-red-500', 'border-yellow-500', 'border-green-500');
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
            tombolElement.innerHTML = '<span class="text-xs text-green-600 text-center py-2">✓ Kelas telah selesai</span>';
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
            
            const response = await fetch('{{ route("profile.password.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                        showError(document.getElementById('error-sandi-lama'), result.errors.current_password[0]);
                    }
                    if (result.errors.new_password) {
                        showError(document.getElementById('error-sandi-baru'), result.errors.new_password[0]);
                    }
                    if (result.errors.new_password_confirmation) {
                        showError(document.getElementById('error-konfirmasi-sandi'), result.errors.new_password_confirmation[0]);
                    }
                    
                    // Jika ada error umum
                    if (result.message && !result.errors.current_password && !result.errors.new_password && !result.errors.new_password_confirmation) {
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
    
    // Load schedules when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadUserSchedules();
        
        // Refresh schedules every 5 minutes
        setInterval(loadUserSchedules, 300000);
        
        // Refresh button visibility setiap menit untuk update waktu konfirmasi
        setInterval(() => {
            loadUserSchedules();
        }, 60000); // Refresh setiap 1 menit
    });
</script>

@endsection