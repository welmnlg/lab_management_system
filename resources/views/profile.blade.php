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
        <h2 class="text-xl font-bold text-gray-800 mb-4">Jadwal Praktikum Semester Ganjil T.A. 2025/2026</h2>

        {{-- Tab Hari --}}
        <div class="flex space-x-1 overflow-x-auto pb-2 mb-6">
            <button id="tab-sen" onclick="ubahHari('sen')"
                class="flex-shrink-0 px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold text-xs min-w-[60px]">
                SEN <span class="block text-xs font-normal">27 Okt</span>
            </button>
            <button id="tab-sel" onclick="ubahHari('sel')"
                class="flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300">
                SEL <span class="block text-xs font-normal">28 Okt</span>
            </button>
            <button id="tab-rab" onclick="ubahHari('rab')"
                class="flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300">
                RAB <span class="block text-xs font-normal">29 Okt</span>
            </button>
            <button id="tab-kam" onclick="ubahHari('kam')"
                class="flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300">
                KAM <span class="block text-xs font-normal">30 Okt</span>
            </button>
            <button id="tab-jum" onclick="ubahHari('jum')"
                class="flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300">
                JUM <span class="block text-xs font-normal">31 Okt</span>
            </button>
        </div>

        {{-- Daftar Jadwal untuk SENIN --}}
        <div id="jadwal-sen" class="space-y-4">

            <!-- Jadwal 1: Sedang Berlangsung -->
            <div id="jadwal-1"
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
                        <span id="status-jadwal-1" class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">Sedang Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div id="tombol-jadwal-1" class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="konfirmasiPindahRuangan(1)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Pindah Ruangan
                    </button>
                    <button onclick="konfirmasiSelesai(1)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Selesai
                    </button>
                </div>
            </div>

            <!-- Jadwal 2: Akan Berlangsung -->
            <div id="jadwal-2"
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
                        <span id="status-jadwal-2" class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Akan Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div id="tombol-jadwal-2" class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="konfirmasiBatal(2)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Batal
                    </button>
                    <button onclick="konfirmasiAjar(2)"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Konfirmasi
                    </button>
                </div>
            </div>

        </div>

        {{-- Daftar Jadwal untuk SELASA --}}
        <div id="jadwal-sel" class="space-y-4 hidden">

            <!-- Jadwal Selasa 1: Akan Berlangsung -->
            <div id="jadwal-sel-1"
                class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 border-gray-400 shadow-sm">
                {{-- Bagian Kiri (Info) --}}
                <div class="flex-grow w-full">
                    <p class="font-bold text-gray-800">Mata Kuliah: Praktikum Basis Data</p>
                    <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mt-2">
                        <span class="flex items-center gap-1.5 bg-gray-200 px-2 py-1 rounded-md"><svg class="w-4 h-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> 10:00 - 11:40</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">KOM C1</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">Lab Database</span>
                    </div>
                    <div class="mt-3">
                        <span id="status-jadwal-sel-1" class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Akan Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div id="tombol-jadwal-sel-1" class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="konfirmasiBatal('sel-1')"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Batal
                    </button>
                    <button onclick="konfirmasiAjar('sel-1')"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Konfirmasi
                    </button>
                </div>
            </div>

            <!-- Jadwal Selasa 2: Akan Berlangsung -->
            <div id="jadwal-sel-2"
                class="bg-gray-50 rounded-lg p-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 border-l-4 border-gray-400 shadow-sm">
                {{-- Bagian Kiri (Info) --}}
                <div class="flex-grow w-full">
                    <p class="font-bold text-gray-800">Mata Kuliah: Praktikum Pemrograman Web</p>
                    <div class="flex items-center flex-wrap gap-2 text-sm text-gray-600 mt-2">
                        <span class="flex items-center gap-1.5 bg-gray-200 px-2 py-1 rounded-md"><svg class="w-4 h-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> 13:00 - 14:40</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">KOM D1</span>
                        <span class="bg-gray-200 px-2 py-1 rounded-md">Lab Pemrograman</span>
                    </div>
                    <div class="mt-3">
                        <span id="status-jadwal-sel-2" class="px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">Akan Berlangsung</span>
                    </div>
                </div>
                {{-- Bagian Kanan (Tombol) --}}
                <div id="tombol-jadwal-sel-2" class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="konfirmasiBatal('sel-2')"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Batal
                    </button>
                    <button onclick="konfirmasiAjar('sel-2')"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-semibold text-white text-center bg-gradient-to-r from-blue-900 to-gray-700 rounded-lg hover:opacity-90 transition min-w-[135px]">
                        Konfirmasi
                    </button>
                </div>
            </div>

        </div>

        {{-- Daftar Jadwal untuk RABU --}}
        <div id="jadwal-rab" class="hidden">
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600 text-lg">Tidak ada kelas mengajar hari ini</p>
            </div>
        </div>

        {{-- Daftar Jadwal untuk KAMIS --}}
        <div id="jadwal-kam" class="hidden">
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600 text-lg">Tidak ada kelas mengajar hari ini</p>
            </div>
        </div>

        {{-- Daftar Jadwal untuk JUMAT --}}
        <div id="jadwal-jum" class="hidden">
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <p class="text-gray-600 text-lg">Tidak ada kelas mengajar hari ini</p>
            </div>
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
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        {{-- Header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800">Ganti Kata Sandi</h2>
            <p class="text-sm text-gray-600 mt-2">
                Kata sandi harus memiliki minimal 6 karakter dan harus mengandung huruf, angka, dan karakter khusus (!$@%).
            </p>
        </div>

        {{-- Form --}}
        <form id="form-ganti-sandi" class="space-y-4">
            {{-- Kata Sandi Lama --}}
            <div>
                <label for="sandi-lama" class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi Lama
                </label>
                <input type="password" id="sandi-lama" name="sandi_lama" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Masukkan kata sandi lama"
                    required>
                <div id="error-sandi-lama" class="text-red-500 text-xs mt-1 hidden"></div>
            </div>

            {{-- Kata Sandi Baru --}}
            <div>
                <label for="sandi-baru" class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi Baru
                </label>
                <input type="password" id="sandi-baru" name="sandi_baru" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Masukkan kata sandi baru"
                    required>
                
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
                <input type="password" id="konfirmasi-sandi" name="konfirmasi_sandi" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Tulis ulang kata sandi baru"
                    required>
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
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center">
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

<script>
    // Variabel untuk melacak status validasi
    let isSandiLamaValid = false;
    let isSandiBaruValid = false;
    let isKonfirmasiValid = false;
    let hariAktif = 'sen'; // Default hari aktif

    // ========== FUNGSI UNTUK TAB HARI ==========

    // Fungsi untuk mengubah hari yang aktif
    function ubahHari(hari) {
        // Sembunyikan semua jadwal
        const hariList = ['sen', 'sel', 'rab', 'kam', 'jum'];
        hariList.forEach(h => {
            document.getElementById('jadwal-' + h).classList.add('hidden');
        });
        
        // Reset semua tab menjadi abu-abu
        hariList.forEach(h => {
            document.getElementById('tab-' + h).className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold text-xs min-w-[60px] hover:bg-gray-300';
        });
        
        // Tampilkan jadwal hari yang dipilih
        document.getElementById('jadwal-' + hari).classList.remove('hidden');
        
        // Aktifkan tab yang dipilih
        document.getElementById('tab-' + hari).className = 'flex-shrink-0 px-3 py-2 rounded-lg bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold text-xs min-w-[60px]';
        
        hariAktif = hari;
    }

    // ========== FUNGSI LAINNYA ==========

    // Fungsi untuk modal konfirmasi aksi
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
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
        
        document.getElementById('modal-batal').addEventListener('click', function() {
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    // Fungsi untuk modal sukses
    function tampilkanModalSukses(title, message) {
        document.getElementById('sukses-title').textContent = title;
        document.getElementById('sukses-message').textContent = message;
        const modal = document.getElementById('modal-sukses');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Fungsi untuk modal ganti kata sandi
    function bukaModalGantiSandi() {
        const modal = document.getElementById('modal-ganti-sandi');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        resetValidasi();
    }

    function tutupModalGantiSandi() {
        const modal = document.getElementById('modal-ganti-sandi');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('form-ganti-sandi').reset();
        resetValidasi();
    }

    function tutupModalSukses() {
        const modal = document.getElementById('modal-sukses');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

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
            if (text.id !== 'text-length' && text.id !== 'text-letter' && 
                text.id !== 'text-number' && text.id !== 'text-special') return;
            text.className = 'text-gray-600';
        });
        
        // Reset error messages
        const errorElements = document.querySelectorAll('[id^="error-"]');
        errorElements.forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });
    }

    // Validasi real-time untuk kata sandi baru
    document.getElementById('sandi-baru').addEventListener('input', function(e) {
        const sandi = e.target.value;
        validateSandiBaru(sandi);
        validateKonfirmasiSandi();
    });

    // Validasi real-time untuk konfirmasi sandi
    document.getElementById('konfirmasi-sandi').addEventListener('input', function(e) {
        validateKonfirmasiSandi();
    });

    // Validasi real-time untuk sandi lama
    document.getElementById('sandi-lama').addEventListener('input', function(e) {
        validateSandiLama(e.target.value);
    });

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
        
        // Validasi karakter khusus
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

    function showError(errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    function hideError(errorElement) {
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
    }

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

    // Handle form ganti kata sandi
    document.getElementById('form-ganti-sandi').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Final validation sebelum submit
        validateSandiLama(document.getElementById('sandi-lama').value);
        validateSandiBaru(document.getElementById('sandi-baru').value);
        validateKonfirmasiSandi();
        
        if (isSandiLamaValid && isSandiBaruValid && isKonfirmasiValid) {
            // Simulasi proses ganti kata sandi berhasil
            tutupModalGantiSandi();
            tampilkanModalSukses('Berhasil!', 'Kata sandi berhasil diganti.');
        }
    });

    // ========== FUNGSI UNTUK JADWAL PRAKTIKUM ==========

    // Fungsi konfirmasi pindah ruangan
    function konfirmasiPindahRuangan(id) {
        tampilkanModal(
            'Pindah Ruangan',
            'Apakah Anda yakin ingin pindah ruangan?',
            function() {
                updateStatusJadwal(id, 'pindah-ruangan');
                tampilkanModalSukses('Berhasil!', 'Permintaan pindah ruangan telah dikirim.');
            }
        );
    }

    // Fungsi konfirmasi selesai
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

    // Fungsi konfirmasi batal
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

    // Fungsi konfirmasi ajar
    function konfirmasiAjar(id) {
        tampilkanModal(
            'Konfirmasi Mengajar',
            'Konfirmasi kehadiran Anda untuk praktikum sesi ini.',
            function() {
                updateStatusJadwal(id, 'sudah-dikonfirmasi');
                tampilkanModalSukses('Terkonfirmasi!', 'Kelas telah dikonfirmasi.');
            }
        );
    }

    // Fungsi untuk mengupdate status jadwal
    function updateStatusJadwal(id, aksi) {
        const statusElement = document.getElementById(`status-jadwal-${id}`);
        const jadwalElement = document.getElementById(`jadwal-${id}`);
        const tombolElement = document.getElementById(`tombol-jadwal-${id}`);
        
        if (aksi === 'pindah-ruangan') {
            statusElement.textContent = 'Pindah Ruangan';
            statusElement.className = 'px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full';
            jadwalElement.classList.remove('border-green-500');
            jadwalElement.classList.add('border-blue-500');
        } else if (aksi === 'selesai') {
            statusElement.textContent = 'Kelas Selesai';
            statusElement.className = 'px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full';
            jadwalElement.classList.remove('border-green-500');
            jadwalElement.classList.add('border-gray-400');
        } else if (aksi === 'dibatalkan') {
            statusElement.textContent = 'Dibatalkan';
            statusElement.className = 'px-3 py-1 text-xs font-semibold text-red-800 bg-red-200 rounded-full';
            jadwalElement.classList.remove('border-gray-400');
            jadwalElement.classList.add('border-red-500');
        } else if (aksi === 'sudah-dikonfirmasi') {
            statusElement.textContent = 'Kelas Sudah Dikonfirmasi';
            statusElement.className = 'px-3 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full';
            jadwalElement.classList.remove('border-gray-400');
            jadwalElement.classList.add('border-gray-400');
        }
        
        // Nonaktifkan tombol
        const buttons = tombolElement.querySelectorAll('button');
        buttons.forEach(button => {
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.classList.remove('hover:opacity-90');
        });
    }

    // Tutup modal ketika klik di luar
    document.getElementById('modal-konfirmasi').addEventListener('click', function(e) {
        if (e.target.id === 'modal-konfirmasi') {
            const modal = document.getElementById('modal-konfirmasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    document.getElementById('modal-ganti-sandi').addEventListener('click', function(e) {
        if (e.target.id === 'modal-ganti-sandi') {
            tutupModalGantiSandi();
        }
    });

    document.getElementById('modal-sukses').addEventListener('click', function(e) {
        if (e.target.id === 'modal-sukses') {
            tutupModalSukses();
        }
    });
</script>

@endsection