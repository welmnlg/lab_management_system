@extends('layouts.pageadmin')

@section('title', 'Form Ambil Jadwal - ITLG Lab Management System')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header Form -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white text-center">Ambil Jadwal</h1>
        </div>

        <!-- Form Content -->
        <form id="formAmbilJadwal" class="p-6 space-y-6">
            @csrf
            
            <!-- Nama Lengkap -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama_lengkap"
                       id="nama_lengkap"
                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                       placeholder="Masukkan nama lengkap Anda"
                       required>
            </div>

            <!-- NIM -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    NIM <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nim"
                       id="nim"
                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base"
                       placeholder="Masukkan NIM Anda"
                       required>
            </div>

            <!-- Mata Kuliah -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="mata_kuliah" 
                        id="mata_kuliah"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base" 
                        required>
                    <option value="">Pilih Mata Kuliah</option>
                    <option value="praktikum-kecerdasan-buatan">Praktikum Kecerdasan Buatan</option>
                    <option value="praktikum-desain-interaksi">Praktikum Desain Interaksi</option>
                    <option value="praktikum-pemrograman-web">Praktikum Pemrograman Web</option>
                    <option value="praktikum-web-semantik">Praktikum Web Semantik</option>
                </select>
            </div>

            <!-- Kelas -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="kelas" 
                        id="kelas"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base" 
                        required>
                    <option value="">Pilih Kelas</option>
                    <option value="kom-a1">KOM A1</option>
                    <option value="kom-a2">KOM A2</option>
                    <option value="kom-b1">KOM B1</option>
                    <option value="kom-b2">KOM B2</option>
                    <option value="kom-c1">KOM C1</option>
                    <option value="kom-c2">KOM C2</option>
                </select>
            </div>

            <!-- Ruangan yang Digunakan -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Ruangan yang Digunakan <span class="text-red-500">*</span>
                </label>
                <select name="ruangan" 
                        id="ruangan"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base" 
                        required>
                    <option value="">Pilih Ruangan</option>
                    <option value="lab-jaringan-1">Lab Jaringan 1</option>
                    <option value="lab-jaringan-2">Lab Jaringan 2</option>
                    <option value="lab-jaringan-3">Lab Jaringan 3</option>
                    <option value="lab-jaringan-4">Lab Jaringan 4</option>
                </select>
            </div>

            <!-- Hari -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Hari <span class="text-red-500">*</span>
                </label>
                <select name="hari" 
                        id="hari"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base" 
                        required>
                    <option value="">Pilih Hari</option>
                    <option value="senin">Senin</option>
                    <option value="selasa">Selasa</option>
                    <option value="rabu">Rabu</option>
                    <option value="kamis">Kamis</option>
                    <option value="jumat">Jumat</option>
                </select>
            </div>

            <!-- Waktu -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Waktu <span class="text-red-500">*</span>
                </label>
                <select name="waktu" 
                        id="waktu"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base" 
                        required>
                    <option value="">Pilih Waktu</option>
                    <option value="08:00-09:40">08:00 - 09:40</option>
                    <option value="09:40-11:20">09:40 - 11:20</option>
                    <option value="11:20-13:00">11:20 - 13:00</option>
                    <option value="13:00-14:40">13:00 - 14:40</option>
                    <option value="14:40-16:20">14:40 - 16:20</option>
                </select>
            </div>

            <!-- Button Simpan dan Batal -->
            <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0 pt-4 sm:pt-6">
                <!-- Button Batal -->
                <a href="{{ route('ambil-jadwal') }}" 
                   class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                    <i class="bi bi-x-lg mr-2"></i>
                    Batal
                </a>
                <!-- Button Simpan -->
                <button type="submit" 
                        id="simpanButton" 
                        disabled
                        class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-check-lg mr-2"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
select:focus, input:focus {
    outline: none;
    ring: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAmbilJadwal');
    const simpanButton = document.getElementById('simpanButton');
    const formInputs = form.querySelectorAll('input, select');

    // Fungsi untuk mengecek apakah semua field sudah terisi
    function checkFormValidity() {
        let allFilled = true;
        
        formInputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                allFilled = false;
            }
        });
        
        // Update status button simpan
        simpanButton.disabled = !allFilled;
    }

    // Tambahkan event listener untuk setiap input
    formInputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
        input.addEventListener('change', checkFormValidity);
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi final sebelum submit
        let allValid = true;
        formInputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                allValid = false;
                input.classList.add('border-red-500');
            } else {
                input.classList.remove('border-red-500');
            }
        });

        if (allValid) {
            // Tampilkan loading state
            simpanButton.disabled = true;
            simpanButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Menyimpan...';
            
            // Simulasi proses penyimpanan (ganti dengan AJAX call ke server)
            setTimeout(() => {
                alert('Jadwal berhasil disimpan!');
                window.location.href = "{{ route('ambil-jadwal') }}";
            }, 1000);
        } else {
            alert('Harap lengkapi semua field yang wajib diisi!');
        }
    });

    // Inisialisasi status button saat pertama kali load
    checkFormValidity();
});
</script>
@endsection