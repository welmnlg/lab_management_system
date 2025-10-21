@extends('layouts.pageadmin')

@section('title', 'Tambah Pengguna - ITLG Lab Management System')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Tambah Pengguna</h1>
        </div>

        <!-- Form -->
        <div class="p-6 space-y-6">
            <!-- Nama Lengkap -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" 
                       placeholder="Masukkan nama lengkap Anda" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
            </div>

            <!-- NIM -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                <input type="text" 
                       placeholder="Masukkan NIM Anda" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                <input type="email" 
                       placeholder="Masukkan alamat email Anda" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
            </div>

            <!-- Kata Sandi -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Kata Sandi <span class="text-red-500">*</span></label>
                <input type="password" 
                       placeholder="Masukkan kata sandi" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
            </div>

            <!-- Garis Pemisah -->
            <div class="border-t border-gray-200 my-4 sm:my-6"></div>

            <!-- Mata Kuliah - Struktur Baru -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Mata Kuliah</h3>
                
                <!-- Baris 1 - Mata Kuliah 1 dan Kelas MK 1 -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Mata Kuliah 1 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Mata Kuliah 1 <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="mataKuliah1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih mata kuliah...</option>
                                <option value="kecerdasan_buatan">Praktikum Kecerdasan Buatan</option>
                                <option value="desain_interaksi">Praktikum Desain Interaksi</option>
                                <option value="pemrograman_web">Praktikum Pemrograman Web</option>
                                <option value="web_semantik">Praktikum Web Semantik</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kelas MK 1 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kelas MK 1 <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="kelasMK1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih kelas...</option>
                                <option value="KOM A1">KOM A</option>
                                <option value="KOM A2">KOM B</option>
                                <option value="KOM B1">KOM C</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Baris 2 - Mata Kuliah 2 dan Kelas MK 2 -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Mata Kuliah 2 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Mata Kuliah 2</label>
                        <div class="relative">
                            <select id="mataKuliah2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih mata kuliah...</option>
                                <option value="kecerdasan_buatan">Praktikum Kecerdasan Buatan</option>
                                <option value="desain_interaksi">Praktikum Desain Interaksi</option>
                                <option value="pemrograman_web">Praktikum Pemrograman Web</option>
                                <option value="web_semantik">Praktikum Web Semantik</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kelas MK 2 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kelas MK 2</label>
                        <div class="relative">
                            <select id="kelasMK2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih kelas...</option>
                                <option value="KOM A1">KOM A</option>
                                <option value="KOM A2">KOM B</option>
                                <option value="KOM B1">KOM C</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Baris 3 - Mata Kuliah 3 dan Kelas MK 3 -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Mata Kuliah 3 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Mata Kuliah 3</label>
                        <div class="relative">
                            <select id="mataKuliah3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih mata kuliah...</option>
                                <option value="kecerdasan_buatan">Praktikum Kecerdasan Buatan</option>
                                <option value="desain_interaksi">Praktikum Desain Interaksi</option>
                                <option value="pemrograman_web">Praktikum Pemrograman Web</option>
                                <option value="web_semantik">Praktikum Web Semantik</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kelas MK 3 -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kelas MK 3</label>
                        <div class="relative">
                            <select id="kelasMK3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                <option value="">Pilih kelas...</option>
                                <option value="KOM A1">KOM A</option>
                                <option value="KOM A2">KOM B</option>
                                <option value="KOM B1">KOM C</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="bi bi-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Garis Pemisah -->
            <div class="border-t border-gray-200 my-4 sm:my-6"></div>

            <!-- Peran -->
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Peran <span class="text-red-500">*</span></h3>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="peran" value="aslab" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Aslab</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="peran" value="admin" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Admin</span>
                    </label>
                </div>
            </div>

            <!-- Garis Pemisah -->
            <div class="border-t border-gray-200 my-4 sm:my-6"></div>

            <!-- Button Simpan dan Batal -->
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <!-- Button Batal -->
                <a href="{{ route('admin') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                    <i class="bi bi-x-lg mr-2"></i>
                    Batal
                </a>
                <!-- Button Simpan -->
                <button id="simpanButton" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                    <i class="bi bi-check-lg mr-2"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const simpanButton = document.getElementById('simpanButton');
        const namaInput = document.querySelector('input[type="text"]');
        const nimInput = document.querySelectorAll('input[type="text"]')[1];
        const emailInput = document.querySelector('input[type="email"]');
        const passwordInput = document.querySelector('input[type="password"]');
        const mataKuliah1 = document.getElementById('mataKuliah1');
        const kelasMK1 = document.getElementById('kelasMK1');
        const peranRadio = document.querySelectorAll('input[name="peran"]');
        
        // Fungsi untuk memeriksa apakah semua field wajib sudah terisi
        function validateForm() {
            const namaFilled = namaInput.value.trim() !== '';
            const nimFilled = nimInput.value.trim() !== '';
            const emailFilled = emailInput.value.trim() !== '';
            const passwordFilled = passwordInput.value.trim() !== '';
            const mataKuliah1Filled = mataKuliah1.value !== '';
            const kelasMK1Filled = kelasMK1.value !== '';
            const peranSelected = Array.from(peranRadio).some(radio => radio.checked);
            
            return namaFilled && nimFilled && emailFilled && passwordFilled && 
                   mataKuliah1Filled && kelasMK1Filled && peranSelected;
        }
        
        // Fungsi untuk update status tombol simpan
        function updateSimpanButton() {
            if (validateForm()) {
                simpanButton.disabled = false;
                simpanButton.classList.remove('opacity-50', 'cursor-not-allowed');
                simpanButton.classList.add('hover:from-blue-800', 'hover:to-red-600');
            } else {
                simpanButton.disabled = true;
                simpanButton.classList.add('opacity-50', 'cursor-not-allowed');
                simpanButton.classList.remove('hover:from-blue-800', 'hover:to-red-600');
            }
        }
        
        // Tambahkan event listener untuk semua input wajib
        const requiredInputs = [
            namaInput, nimInput, emailInput, passwordInput, 
            mataKuliah1, kelasMK1
        ];
        
        requiredInputs.forEach(input => {
            input.addEventListener('change', updateSimpanButton);
            input.addEventListener('input', updateSimpanButton);
        });
        
        peranRadio.forEach(radio => {
            radio.addEventListener('change', updateSimpanButton);
        });
        
        // Event listener untuk tombol simpan
        simpanButton.addEventListener('click', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                // Tetap gunakan alert biasa untuk error
                alert('Harap isi semua field yang wajib diisi!');
                return;
            }
            
            // Jika validasi berhasil, tampilkan modal custom
            e.preventDefault();
            showSuccessModal();
        });
        
        // Fungsi untuk menampilkan modal sukses
        function showSuccessModal() {
            // Buat elemen modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                <i class="bi bi-check-lg text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Data pengguna berhasil disimpan!</h3>
                        </div>
                        <div class="mt-6 flex justify-center">
                            <button id="successOkButton" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Tambahkan modal ke body
            document.body.appendChild(modal);
            
            // Event listener untuk tombol OK
            const okButton = document.getElementById('successOkButton');
            okButton.addEventListener('click', function() {
                // Hapus modal
                document.body.removeChild(modal);
                // Redirect ke halaman admin
                window.location.href = "{{ route('admin') }}";
            });
            
            // Event listener untuk klik di luar modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                    window.location.href = "{{ route('admin') }}";
                }
            });
        }
        
        // Inisialisasi status tombol simpan
        updateSimpanButton();
    });
</script>
@endpush