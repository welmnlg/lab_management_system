@extends('layouts.main')

@section('title', 'Tambah Pengguna - ITLG Lab Management System')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Tambah Pengguna</h1>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('kelola-pengguna.store') }}" id="formTambahPengguna" autocomplete="off">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="name"
                           id="namaInput"
                           placeholder="Masukkan nama lengkap" 
                           required
                           autocomplete="off"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIM -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="nim"
                           id="nimInput"
                           placeholder="Masukkan NIM" 
                           required
                           autocomplete="off"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    @error('nim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Program Studi -->
                @php
                    $user = auth()->user();
                @endphp

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Program Studi <span class="text-red-500">*</span></label>

                    @if($user->roles->contains('status', 'bph'))
                        <!-- Untuk BPH: tampilan readonly dan hidden input -->
                        <input type="hidden" name="program_studi" value="{{ $user->program_studi }}">
                        <input type="text" readonly
                            value="{{ $user->program_studi }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:px-4 sm:py-3">
                    @else
                        <!-- Untuk admin: dropdown pilih program studi -->
                        <select name="program_studi" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:px-4 sm:py-3">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                        </select>
                    @endif
                    @error('program_studi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" 
                           name="email"
                           id="emailInput"
                           placeholder="Masukkan alamat email" 
                           required
                           autocomplete="off"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kata Sandi dengan Toggle Show/Hide -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" 
                               id="passwordInput"
                               name="password"
                               placeholder="Masukkan kata sandi" 
                               required
                               autocomplete="new-password"
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                        <button type="button" 
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Garis Pemisah -->
                <div class="border-t border-gray-200 my-4 sm:my-6"></div>

                <!-- Mata Kuliah - Struktur Baru -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Mata Kuliah</h3>
                    
                    <!-- Baris 1 - Mata Kuliah 1 dan KOM MK 1 -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Mata Kuliah 1 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Mata Kuliah 1 <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="mata_kuliah_1" required id="mataKuliah1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                    <option value="">Pilih mata kuliah...</option>
                                    @php
                                        $authUser = auth()->user();
                                        $courses = \App\Models\Course::where('program_id', $authUser->program_studi)->get();
                                    @endphp
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                            </div>
                            @error('mata_kuliah_1')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- KOM MK 1 -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">KOM MK 1 <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <button type="button" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base dropdown-kom-toggle" data-target="kom1">
                                    <span class="selected-kom-text text-gray-400">Pilih kelas</span>
                                    <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                </button>
                                <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden dropdown-kom-menu" data-target="kom1">
                                    <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                        <!-- Opsi KOM Manual Terpisah -->
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="A1">
                                            <span class="text-sm text-gray-700">KOM A1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="A2">
                                            <span class="text-sm text-gray-700">KOM A2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="B1">
                                            <span class="text-sm text-gray-700">KOM B1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="B2">
                                            <span class="text-sm text-gray-700">KOM B2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="C1">
                                            <span class="text-sm text-gray-700">KOM C1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom1" data-pair="C2">
                                            <span class="text-sm text-gray-700">KOM C2</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Hidden inputs untuk menyimpan kelas yang dipilih -->
                                <div id="hiddenInputsKom1"></div>
                            </div>
                            @error('kelas_1')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Baris 2 - Mata Kuliah 2 -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Mata Kuliah 2</label>
                            <div class="relative">
                                <select name="mata_kuliah_2" id="mataKuliah2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                    <option value="">Pilih mata kuliah...</option>
                                    @foreach(\App\Models\Course::where('program_id', $user->program_studi)->get() as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Dropdown KOM MK2 --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">KOM MK 2 <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <button type="button" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base dropdown-kom-toggle" data-target="kom2">
                                    <span class="selected-kom-text text-gray-400">Pilih kelas</span>
                                    <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                </button>
                                <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden dropdown-kom-menu" data-target="kom2">
                                    <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                        <!-- Opsi KOM Manual Terpisah -->
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="A1">
                                            <span class="text-sm text-gray-700">KOM A1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="A2">
                                            <span class="text-sm text-gray-700">KOM A2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="B1">
                                            <span class="text-sm text-gray-700">KOM B1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="B2">
                                            <span class="text-sm text-gray-700">KOM B2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="C1">
                                            <span class="text-sm text-gray-700">KOM C1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom2" data-pair="C2">
                                            <span class="text-sm text-gray-700">KOM C2</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Hidden inputs untuk menyimpan kelas yang dipilih -->
                                <div id="hiddenInputsKom2"></div>
                            </div>
                            @error('kelas_1')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Baris 3 - Mata Kuliah 3 -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Mata Kuliah 3</label>
                            <div class="relative">
                                <select name="mata_kuliah_3" id="mataKuliah3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3">
                                    <option value="">Pilih mata kuliah...</option>
                                    @foreach(\App\Models\Course::where('program_id', $user->program_studi)->get() as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Dropdown KOM MK3 --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">KOM MK 3 <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <button type="button" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base dropdown-kom-toggle" data-target="kom3">
                                    <span class="selected-kom-text text-gray-400">Pilih kelas</span>
                                    <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                </button>
                                <div class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden dropdown-kom-menu" data-target="kom3">
                                    <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                        <!-- Opsi KOM Manual Terpisah -->
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="A1">
                                            <span class="text-sm text-gray-700">KOM A1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="A2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="A2">
                                            <span class="text-sm text-gray-700">KOM A2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="B1">
                                            <span class="text-sm text-gray-700">KOM B1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="B2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="B2">
                                            <span class="text-sm text-gray-700">KOM B2</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C1" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="C1">
                                            <span class="text-sm text-gray-700">KOM C1</span>
                                        </label>
                                        <label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                            <input type="checkbox" value="C2" class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded kom-checkbox" data-target="kom3" data-pair="C2">
                                            <span class="text-sm text-gray-700">KOM C2</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Hidden inputs untuk menyimpan kelas yang dipilih -->
                                <div id="hiddenInputsKom3"></div>
                            </div>
                            @error('kelas_1')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Garis Pemisah -->
                <div class="border-t border-gray-200 my-4 sm:my-6"></div>

                <!-- Peran -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Peran <span class="text-red-500">*</span></h3>
                    <div class="space-y-3">
                        @foreach($roles as $role)
                            <label class="flex items-center space-x-3">
                                <input type="radio" 
                                       name="role" 
                                       value="{{ $role->id }}" 
                                       required 
                                       class="peranRadio w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ ucfirst($role->status) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Garis Pemisah -->
                <div class="border-t border-gray-200 my-4 sm:my-6"></div>

                <!-- Button Simpan dan Batal -->
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <!-- Button Batal -->
                    <a href="{{ route('kelola-pengguna.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                        <i class="bi bi-x-lg mr-2"></i>
                        Batal
                    </a>
                    <!-- Button Simpan -->
                    <button type="submit" id="simpanButton" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                        <i class="bi bi-check-lg mr-2"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all form elements
    const form = document.getElementById('formTambahPengguna');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput');
    const nimInput = document.getElementById('nimInput');
    const namaInput = document.getElementById('namaInput');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const simpanButton = document.getElementById('simpanButton');
    const mataKuliah1 = document.getElementById('mataKuliah1');
    const mataKuliah2 = document.getElementById('mataKuliah2');
    const mataKuliah3 = document.getElementById('mataKuliah3');
    const peranRadio = document.querySelectorAll('.peranRadio');
    
    // FORCE CLEAR all inputs on page load
    setTimeout(function() {
        if (emailInput) {
            emailInput.value = '';
            emailInput.defaultValue = '';
        }
        if (passwordInput) {
            passwordInput.value = '';
            passwordInput.defaultValue = '';
        }
        if (nimInput) {
            nimInput.value = '';
            nimInput.defaultValue = '';
        }
        if (namaInput) {
            namaInput.value = '';
            namaInput.defaultValue = '';
        }
    }, 50);

    // Prevent browser autofill
    if (emailInput) {
        emailInput.addEventListener('focus', function() {
            if (this.value && (this.value.includes('221402') || this.value.includes('@'))) {
                if (!this.dataset.userTyped) this.value = '';
            }
        });
        emailInput.addEventListener('input', function() {
            this.dataset.userTyped = 'true';
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('focus', function() {
            if (this.value && this.value.length > 0) {
                if (!this.dataset.userTyped) this.value = '';
            }
        });
        passwordInput.addEventListener('input', function() {
            this.dataset.userTyped = 'true';
        });
    }

    // Toggle Password Visibility
    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'password') {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            } else {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }
        });
    }

    // Initialize KOM Dropdowns
    initializeKomDropdowns();

    // Function to initialize KOM dropdowns
    function initializeKomDropdowns() {
        const dropdownToggles = document.querySelectorAll('.dropdown-kom-toggle');
        const dropdownMenus = document.querySelectorAll('.dropdown-kom-menu');
        
        dropdownToggles.forEach(toggle => {
            const target = toggle.getAttribute('data-target');
            const menu = document.querySelector(`.dropdown-kom-menu[data-target="${target}"]`);
            const chevronIcon = toggle.querySelector('i');
            
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isShowing = !menu.classList.contains('hidden');
                
                // Close all dropdowns
                dropdownMenus.forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('.dropdown-kom-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
                
                if (!isShowing) {
                    menu.classList.remove('hidden');
                    chevronIcon.classList.add('rotate-180');
                }
            });
        });
        
        // Add event listeners to all checkboxes
        const allCheckboxes = document.querySelectorAll('.kom-checkbox');
        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const target = this.getAttribute('data-target');
                updateSelectedKomText(target);
                updateHiddenInputs(target);
                updateSimpanButton();
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            dropdownMenus.forEach(menu => menu.classList.add('hidden'));
            document.querySelectorAll('.dropdown-kom-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
        });
        
        // Prevent dropdown from closing when clicking inside
        dropdownMenus.forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    }

    // Function to update selected KOM text - SEMUA KOM SAMA
    function updateSelectedKomText(komTarget) {
        const checkboxes = document.querySelectorAll(`.kom-checkbox[data-target="${komTarget}"]:checked`);
        const toggle = document.querySelector(`.dropdown-kom-toggle[data-target="${komTarget}"]`);
        const selectedText = toggle.querySelector('.selected-kom-text');
        
        const selectedPairs = Array.from(checkboxes).map(cb => {
            const pair = cb.getAttribute('data-pair');
            return `KOM ${pair}`; // Semua tampil sebagai "KOM A1", "KOM A2", dst
        });
        
        if (selectedPairs.length === 0) {
            selectedText.textContent = 'Pilih kelas';
            selectedText.className = 'selected-kom-text text-gray-400';
        } else {
            selectedText.textContent = selectedPairs.join(', ');
            selectedText.className = 'selected-kom-text text-gray-900';
        }
    }

    // Function to update hidden inputs for form submission - SEMUA KOM SAMA
    function updateHiddenInputs(komTarget) {
        const checkboxes = document.querySelectorAll(`.kom-checkbox[data-target="${komTarget}"]:checked`);
        const hiddenInputsDiv = document.getElementById(`hiddenInputs${komTarget.charAt(0).toUpperCase() + komTarget.slice(1)}`);
        
        if (!hiddenInputsDiv) return;
        
        hiddenInputsDiv.innerHTML = '';
        
        const mkNumber = komTarget.slice(-1); // ambil angka 1, 2, atau 3
        
        // SEMUA KOM (1, 2, 3) sekarang menggunakan logika yang sama
        checkboxes.forEach(checkbox => {
            const pair = checkbox.getAttribute('data-pair'); // A1, A2, B1, B2, C1, C2
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `kelas_${mkNumber}[]`;
            input.value = pair;
            hiddenInputsDiv.appendChild(input);
        });
    }

    // Form validation
    function validateForm() {
        const namaFilled = namaInput && namaInput.value.trim() !== '';
        const nimFilled = nimInput && nimInput.value.trim() !== '';
        const emailFilled = emailInput && emailInput.value.trim() !== '';
        const passwordFilled = passwordInput && passwordInput.value.trim() !== '';
        const mataKuliah1Filled = mataKuliah1 && mataKuliah1.value !== '';
        const kom1Filled = document.querySelectorAll('.kom-checkbox[data-target="kom1"]:checked').length > 0;
        const peranSelected = Array.from(peranRadio).some(radio => radio.checked);
        
        return namaFilled && nimFilled && emailFilled && passwordFilled && 
               mataKuliah1Filled && kom1Filled && peranSelected;
    }
    
    function updateSimpanButton() {
        if (simpanButton) {
            if (validateForm()) {
                simpanButton.disabled = false;
                simpanButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                simpanButton.disabled = true;
                simpanButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }
    
    // Add event listeners
    const requiredInputs = [namaInput, nimInput, emailInput, passwordInput, mataKuliah1];
    requiredInputs.forEach(input => {
        if (input) {
            input.addEventListener('change', updateSimpanButton);
            input.addEventListener('input', updateSimpanButton);
        }
    });
    
    peranRadio.forEach(radio => {
        radio.addEventListener('change', updateSimpanButton);
    });
    
    updateSimpanButton();

    // Handle form submit
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
                return false;
            }
            return true;
        });
    }
});
</script>
@endpush