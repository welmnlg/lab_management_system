@extends('layouts.main')

@section('title', 'Kelola Akun Pengguna - ITLG Lab Management System')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header dengan Tombol Tambah dan Hapus -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4 md:py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Kelola Akun Pengguna</h1>
                <div class="flex items-center justify-center space-x-3">
                    <!-- Button Hapus Multiple -->
                    <button id="hapusButton" class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg text-sm md:text-base">
                        <i class="bi bi-trash mr-2 text-xs md:text-sm"></i>
                        Hapus
                    </button>
                    <!-- Button Tambah Pengguna -->
                    <a href="{{ route('kelola-pengguna.create') }}" class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg text-sm md:text-base">
                        <i class="bi bi-plus-lg mr-2 text-xs md:text-sm"></i>
                        Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mx-4 mt-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mx-4 mt-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] md:min-w-0">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-2 py-2 md:px-4 md:py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                        <span class="sr-only">Pilih</span>
                    </th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kata Sandi</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-3 py-2 md:px-6 md:py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $key => $user)
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-center">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors" data-user-id="{{ $user->user_id }}">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $key+1 }}</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->nim }}</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">
                        {{ str_repeat('•', min(strlen($user->password), 15)) }}
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                {{ $role->status === 'bph' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} 
                                mb-1 mr-1">
                                {{ ucfirst($role->status) }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">
                        @if($user->userCourses && $user->userCourses->count() > 0)
                            @foreach($user->userCourses as $userCourse)
                                @if($userCourse->courseClass && $userCourse->courseClass->course)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-1">
                                        {{ $userCourse->courseClass->course->course_name }}
                                    </span>
                                    <br>
                                @endif
                            @endforeach
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">
                        @if($user->userCourses && $user->userCourses->count() > 0)
                            @foreach($user->userCourses as $userCourse)
                                @if($userCourse->courseClass)
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mb-1">
                                        {{ $userCourse->courseClass->class_name }}
                                    </span>
                                    <br>
                                @endif
                            @endforeach
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Tombol Edit -->
                            <button
                                class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg flex-shrink-0"
                                title="Edit"
                                data-user-id="{{ $user->user_id }}"
                                data-user-name="{{ $user->name }}"
                                data-user-nim="{{ $user->nim }}"
                                data-user-email="{{ $user->email }}"
                                data-user-password="{{ $user->password }}"
                                data-user-peran="{{ $user->roles->contains('status', 'bph') ? 'bph' : 'aslab' }}"
                                data-user-role-id="{{ $user->roles->first()?->id ?? '' }}"
                                data-user-courses="{{ json_encode($user->userCourses->groupBy(function($uc) {
                                    return $uc->courseClass->course->course_id ?? null;
                                })->map(function($group) {
                                    return [
                                        'courseId' => $group->first()->courseClass->course->course_id ?? null,
                                        'courseName' => $group->first()->courseClass->course->course_name ?? '',
                                        'kelasIds' => $group->map(function($uc) {
                                            $className = $uc->courseClass->class_name ?? '';
                                            return str_replace('Kom ', '', $className);
                                        })->values()->toArray()
                                    ];
                                })->values()->toArray()) }}">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <button type="button"
                                class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg flex-shrink-0"
                                title="Hapus"
                                data-user-id="{{ $user->user_id }}"
                                data-user-name="{{ $user->name }}"
                                data-action="{{ route('kelola-pengguna.destroy', $user->user_id) }}">
                                <i class="bi bi-trash3 text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
<script>
  window.coursesMaster = @json($coursesMaster);
  window.csrfToken = '{{ csrf_token() }}';
</script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle buttons untuk select multiple users
    const toggleButtons = document.querySelectorAll('.toggle-btn');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            this.classList.toggle('active');
            if (this.classList.contains('active')) {
                this.classList.remove('bg-gray-200', 'text-gray-400');
                this.classList.add('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
                icon.classList.remove('hidden');
            } else {
                this.classList.remove('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
                this.classList.add('bg-gray-200', 'text-gray-400');
                icon.classList.add('hidden');
            }
            // Update button hapus state
            updateHapusButtonState();
        });
    });
    // Update state button hapus berdasarkan selection
    function updateHapusButtonState() {
        const hapusButton = document.getElementById('hapusButton');
        const selectedUsers = document.querySelectorAll('.toggle-btn.active');
        if (selectedUsers.length > 0) {
            hapusButton.disabled = false;
            hapusButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            hapusButton.disabled = true;
            hapusButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    // Initialize button state
    updateHapusButtonState();
    // Tombol Hapus Multiple
    const hapusButton = document.getElementById('hapusButton');
    if (hapusButton) {
        hapusButton.addEventListener('click', function() {
            showMultipleDeleteConfirmationModal();
        });
    }
    // Event listener untuk tombol edit
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userData = {
                id: this.getAttribute('data-user-id'),
                nama: this.getAttribute('data-user-name'),
                nim: this.getAttribute('data-user-nim'),
                email: this.getAttribute('data-user-email'),
                password: this.getAttribute('data-user-password'),
                peran: this.getAttribute('data-user-peran'), // ← INI YANG PENTING
                roleId: this.getAttribute('data-user-role-id'),
                userCourses: JSON.parse(this.getAttribute('data-user-courses') || '[]')
            };
            console.log('Edit User Data:', userData);
            showEditModal(userData);
        });
    });
    // Event listener untuk tombol hapus individual
    const deleteSingleButtons = document.querySelectorAll('.delete-single-btn');
    deleteSingleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const actionUrl = this.getAttribute('data-action');
            showSingleDeleteConfirmationModal(userId, userName, actionUrl);
        });
    });
    // FUNGSI EDIT MODAL - DENGAN 3 MATA KULIAH DAN VALIDASI PASANGAN KELAS
    function showEditModal(userData) {
        console.log('Opening Edit Modal with data:', userData);
        const userCourses = userData.userCourses || [];
        // Prepare courses data untuk 3 mata kuliah
        const mk1 = userCourses[0] || { courseId: '', courseName: '', kelasIds: [] };
        const mk2 = userCourses[1] || { courseId: '', courseName: '', kelasIds: [] };
        const mk3 = userCourses[2] || { courseId: '', courseName: '', kelasIds: [] };
        // Hitung jumlah karakter password asli
        const passwordLength = userData.password ? userData.password.length : 10;
        const passwordDots = '•'.repeat(Math.min(passwordLength, 15));
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-900 to-red-700 px-6 py-4">
                    <h1 class="text-xl md:text-2xl font-bold text-white">Edit Pengguna</h1>
                </div>
                <!-- Form -->
                <form id="userEditForm" class="p-6 space-y-6">
                    <input type="hidden" name="user_id" value="${userData.id}">
                    <!-- Nama Lengkap -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="${userData.nama || ''}" required placeholder="Masukkan nama lengkap"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    </div>
                    <!-- NIM -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">NIM <span class="text-red-500">*</span></label>
                        <input type="text" name="nim" value="${userData.nim || ''}" required placeholder="Masukkan NIM"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    </div>
                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="${userData.email || ''}" required placeholder="Masukkan alamat email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 sm:px-4 sm:py-3">
                    </div>
                    <!-- Kata Sandi - DISABLED dan SENSOR -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                        <div class="relative">
                            <input type="text" value="${passwordDots}" disabled placeholder="Masukkan kata sandi"
                                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600 transition-colors duration-200 sm:px-4 sm:py-3">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 italic">Password tidak dapat diedit untuk keamanan</p>
                    </div>
                    <!-- Garis Pemisah -->
                    <div class="border-t border-gray-200 my-4 sm:my-6"></div>
                    <!-- Mata Kuliah Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Mata Kuliah</h3>
                        <!-- MATA KULIAH 1 -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Mata Kuliah 1 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah 1 <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="mata_kuliah_1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3 select-no-custom-arrow">
                                        <option value="">Pilih mata kuliah...</option>
                                        ${window.coursesMaster.map(course => 
                                            `<option value="${course.course_id}" ${mk1.courseId == course.course_id ? 'selected' : ''}>${course.course_name}</option>`
                                        ).join('')}
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- KOM MK 1 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM MK 1 <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <button type="button" class="dropdown-kom-toggle w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" data-target="kom1">
                                        <span class="selected-kom-text ${mk1.kelasIds.length > 0 ? 'text-gray-900' : 'text-gray-400'}">
                                            ${mk1.kelasIds.length > 0 ? mk1.kelasIds.map(k => 'KOM ' + k).join(', ') : 'Pilih kelas'}
                                        </span>
                                        <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                    </button>
                                    <div class="dropdown-kom-menu absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden" data-target="kom1">
                                        <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                            ${['A1','A2','B1','B2','C1','C2'].map(cls =>
                                                `<label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                                    <input type="checkbox" value="${cls}" 
                                                        ${mk1.kelasIds && mk1.kelasIds.includes(cls) ? 'checked' : ''}
                                                        class="kom-checkbox mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" data-target="kom1" data-pair="${cls}">
                                                    <span class="text-sm text-gray-700">KOM ${cls}</span>
                                                </label>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <div id="hiddenInputsKom1"></div>
                                </div>
                            </div>
                        </div>
                        <!-- MATA KULIAH 2 -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Mata Kuliah 2 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah 2</label>
                                <div class="relative">
                                    <select name="mata_kuliah_2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3 select-no-custom-arrow">
                                        <option value="">Pilih mata kuliah...</option>
                                        ${window.coursesMaster.map(course => 
                                            `<option value="${course.course_id}" ${mk2.courseId == course.course_id ? 'selected' : ''}>${course.course_name}</option>`
                                        ).join('')}
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- KOM MK 2 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM MK 2</label>
                                <div class="relative">
                                    <button type="button" class="dropdown-kom-toggle w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" data-target="kom2">
                                        <span class="selected-kom-text ${mk2.kelasIds.length > 0 ? 'text-gray-900' : 'text-gray-400'}">
                                            ${mk2.kelasIds.length > 0 ? mk2.kelasIds.map(k => 'KOM ' + k).join(', ') : 'Pilih kelas'}
                                        </span>
                                        <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                    </button>
                                    <div class="dropdown-kom-menu absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden" data-target="kom2">
                                        <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                            ${['A1','A2','B1','B2','C1','C2'].map(cls =>
                                                `<label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                                    <input type="checkbox" value="${cls}" 
                                                        ${mk2.kelasIds && mk2.kelasIds.includes(cls) ? 'checked' : ''}
                                                        class="kom-checkbox mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" data-target="kom2" data-pair="${cls}">
                                                    <span class="text-sm text-gray-700">KOM ${cls}</span>
                                                </label>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <div id="hiddenInputsKom2"></div>
                                </div>
                            </div>
                        </div>
                        <!-- MATA KULIAH 3 -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Mata Kuliah 3 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah 3</label>
                                <div class="relative">
                                    <select name="mata_kuliah_3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors appearance-none bg-white sm:px-4 sm:py-3 select-no-custom-arrow">
                                        <option value="">Pilih mata kuliah...</option>
                                        ${window.coursesMaster.map(course => 
                                            `<option value="${course.course_id}" ${mk3.courseId == course.course_id ? 'selected' : ''}>${course.course_name}</option>`
                                        ).join('')}
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- KOM MK 3 -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM MK 3</label>
                                <div class="relative">
                                    <button type="button" class="dropdown-kom-toggle w-full px-3 py-2 border border-gray-300 rounded-lg bg-white text-left flex justify-between items-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" data-target="kom3">
                                        <span class="selected-kom-text ${mk3.kelasIds.length > 0 ? 'text-gray-900' : 'text-gray-400'}">
                                            ${mk3.kelasIds.length > 0 ? mk3.kelasIds.map(k => 'KOM ' + k).join(', ') : 'Pilih kelas'}
                                        </span>
                                        <i class="bi bi-chevron-down text-gray-400 text-sm transition-transform duration-200"></i>
                                    </button>
                                    <div class="dropdown-kom-menu absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden" data-target="kom3">
                                        <div class="p-2 space-y-1 max-h-60 overflow-y-auto kom-checkbox-container">
                                            ${['A1','A2','B1','B2','C1','C2'].map(cls =>
                                                `<label class="flex items-center px-3 py-2 hover:bg-gray-100 rounded cursor-pointer transition-colors">
                                                    <input type="checkbox" value="${cls}" 
                                                        ${mk3.kelasIds && mk3.kelasIds.includes(cls) ? 'checked' : ''}
                                                        class="kom-checkbox mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" data-target="kom3" data-pair="${cls}">
                                                    <span class="text-sm text-gray-700">KOM ${cls}</span>
                                                </label>`
                                            ).join('')}
                                        </div>
                                    </div>
                                    <div id="hiddenInputsKom3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Garis Pemisah -->
                    <div class="border-t border-gray-200 my-4 sm:my-6"></div>
                    <!-- Peran - PRIORITAS BPH -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Peran <span class="text-red-500">*</span></h3>
                        <div class="space-y-3">
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="peran" value="aslab" ${userData.peran === 'aslab' ? 'checked' : ''} required
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Aslab</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="peran" value="bph" ${userData.peran === 'bph' ? 'checked' : ''} required
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Bph</span>
                                <span class="text-xs text-gray-500 italic">(Otomatis mendapat role Aslab)</span>
                            </label>
                        </div>
                    </div>
                    <!-- Garis Pemisah -->
                    <div class="border-t border-gray-200 my-4 sm:my-6"></div>
                    <!-- Button Simpan dan Batal -->
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <!-- Button Batal -->
                        <button type="button" id="cancelEditButton"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                            <i class="bi bi-x-lg mr-2"></i>
                            Batal
                        </button>
                        <!-- Button Simpan -->
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium sm:px-6 sm:py-3">
                            <i class="bi bi-check-lg mr-2"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);
        // Initialize dropdown KOM functionality
        initializeKomDropdownsInModal(modal);
        // Event: Batal
        modal.querySelector('#cancelEditButton').onclick = () => document.body.removeChild(modal);
        // Event: Submit Form
        modal.querySelector('#userEditForm').onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            // Collect kelas data from checkboxes
            const kelas1 = Array.from(modal.querySelectorAll('.kom-checkbox[data-target="kom1"]:checked')).map(cb => cb.value);
            const kelas2 = Array.from(modal.querySelectorAll('.kom-checkbox[data-target="kom2"]:checked')).map(cb => cb.value);
            const kelas3 = Array.from(modal.querySelectorAll('.kom-checkbox[data-target="kom3"]:checked')).map(cb => cb.value);
            // Validate paired classes (A1-A2, B1-B2, C1-C2)
            const validatePairedClasses = (kelasArray) => {
                const pairs = {
                    'A1': 'A2',
                    'A2': 'A1',
                    'B1': 'B2',
                    'B2': 'B1',
                    'C1': 'C2',
                    'C2': 'C1'
                };
                const selected = new Set(kelasArray);
                for (const cls of selected) {
                    const paired = pairs[cls];
                    if (paired && !selected.has(paired)) {
                        return `Kelas ${cls} harus dipilih bersamaan dengan ${paired}.`;
                    }
                }
                return null;
            };
            const error1 = validatePairedClasses(kelas1);
            const error2 = validatePairedClasses(kelas2);
            const error3 = validatePairedClasses(kelas3);
            if (error1) {
                showAlertModal('Validasi Gagal', error1);
                return;
            }
            if (error2) {
                showAlertModal('Validasi Gagal', error2);
                return;
            }
            if (error3) {
                showAlertModal('Validasi Gagal', error3);
                return;
            }
            // Build data object
            const data = {
                _token: window.csrfToken,
                _method: 'PUT',
                name: formData.get('name'),
                nim: formData.get('nim'),
                email: formData.get('email'),
                peran: formData.get('peran'),
                mata_kuliah_1: formData.get('mata_kuliah_1'),
                kelas_1: kelas1,
                mata_kuliah_2: formData.get('mata_kuliah_2') || '',
                kelas_2: kelas2,
                mata_kuliah_3: formData.get('mata_kuliah_3') || '',
                kelas_3: kelas3
            };
            console.log('Submitting edit data:', data);
            // Kirim data ke server dengan AJAX
            submitEditForm(data, userData.id);
            document.body.removeChild(modal);
        };
        // Klik di luar modal untuk close
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {document.body.removeChild(modal);
            }
        });
    }
    // Initialize KOM Dropdowns dalam Modal
    function initializeKomDropdownsInModal(modal) {
        const dropdownToggles = modal.querySelectorAll('.dropdown-kom-toggle');
        const dropdownMenus = modal.querySelectorAll('.dropdown-kom-menu');
        dropdownToggles.forEach(toggle => {
            const target = toggle.getAttribute('data-target');
            const menu = modal.querySelector(`.dropdown-kom-menu[data-target="${target}"]`);
            const chevronIcon = toggle.querySelector('i');
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isShowing = !menu.classList.contains('hidden');
                // Close all dropdowns in modal
                dropdownMenus.forEach(m => m.classList.add('hidden'));
                modal.querySelectorAll('.dropdown-kom-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
                if (!isShowing) {
                    menu.classList.remove('hidden');
                    chevronIcon.classList.add('rotate-180');
                }
            });
        });
        // Add event listeners to all checkboxes in modal
        const allCheckboxes = modal.querySelectorAll('.kom-checkbox');
        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const target = this.getAttribute('data-target');
                updateSelectedKomTextInModal(modal, target);
            });
        });
        // Close dropdown when clicking outside
        modal.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-kom-toggle') && !e.target.closest('.dropdown-kom-menu')) {
                dropdownMenus.forEach(menu => menu.classList.add('hidden'));
                modal.querySelectorAll('.dropdown-kom-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
            }
        });
        // Prevent dropdown from closing when clicking inside
        dropdownMenus.forEach(menu => {
            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    }
    // Update selected KOM text dalam modal
    function updateSelectedKomTextInModal(modal, komTarget) {
        const checkboxes = modal.querySelectorAll(`.kom-checkbox[data-target="${komTarget}"]:checked`);
        const toggle = modal.querySelector(`.dropdown-kom-toggle[data-target="${komTarget}"]`);
        const selectedText = toggle.querySelector('.selected-kom-text');
        const selectedPairs = Array.from(checkboxes).map(cb => {
            const value = cb.value;
            return `KOM ${value}`;
        });
        if (selectedPairs.length === 0) {
            selectedText.textContent = 'Pilih kelas';
            selectedText.className = 'selected-kom-text text-gray-400';
        } else {
            selectedText.textContent = selectedPairs.join(', ');
            selectedText.className = 'selected-kom-text text-gray-900';
        }
    }
    // Submit Edit Form via AJAX
    function submitEditForm(data, userId) {
        showProcessingEditModal();
        console.log('Sending update request to:', `/kelola-pengguna/${userId}`);
        console.log('Data:', data);
        // Convert arrays to proper format for Laravel
        const formData = new FormData();
        formData.append('_token', data._token);
        formData.append('_method', 'PUT');
        formData.append('name', data.name);
        formData.append('nim', data.nim);
        formData.append('email', data.email);
        formData.append('peran', data.peran);
        formData.append('mata_kuliah_1', data.mata_kuliah_1);
        // Append kelas arrays
        data.kelas_1.forEach(kelas => formData.append('kelas_1[]', kelas));
        if (data.mata_kuliah_2) {
            formData.append('mata_kuliah_2', data.mata_kuliah_2);
            data.kelas_2.forEach(kelas => formData.append('kelas_2[]', kelas));
        }
        if (data.mata_kuliah_3) {
            formData.append('mata_kuliah_3', data.mata_kuliah_3);
            data.kelas_3.forEach(kelas => formData.append('kelas_3[]', kelas));
        }
        fetch(`/kelola-pengguna/${userId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.text();
        })
        .then(result => {
            console.log('Update success:', result);
            setTimeout(() => {
                document.querySelector('.processing-modal')?.remove();
                showSuccessModal('Data pengguna berhasil diperbarui!');
            }, 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.processing-modal')?.remove();
            showAlertModal('Error', 'Terjadi kesalahan saat menyimpan data: ' + error.message);
        });
    }
    // FUNGSI HAPUS SINGLE
    function showSingleDeleteConfirmationModal(userId, userName, actionUrl) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Anda akan menghapus pengguna <strong>${userName}</strong>. Tindakan ini tidak dapat dikembalikan.
                        </p>
                    </div>
                    <div class="mt-6 flex justify-center space-x-3">
                        <button id="cancelSingleDeleteButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            Batal
                        </button>
                        <button id="confirmSingleDeleteButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            <i class="bi bi-trash mr-2"></i>
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        modal.querySelector('#cancelSingleDeleteButton').onclick = () => document.body.removeChild(modal);
        modal.querySelector('#confirmSingleDeleteButton').onclick = () => {
            document.body.removeChild(modal);
            showProcessingModal(1, userName);
            // Submit form delete dengan AJAX
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = window.csrfToken;
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            setTimeout(() => {
                form.submit();
            }, 1500);
        };
        modal.addEventListener('click', function(e) {
            if (e.target === modal) document.body.removeChild(modal);
        });
    }
    // FUNGSI HAPUS MULTIPLE
    function showMultipleDeleteConfirmationModal() {
        const selectedUsers = document.querySelectorAll('.toggle-btn.active');
        if (selectedUsers.length === 0) {
            showAlertModal('Peringatan', 'Tidak ada pengguna yang dipilih untuk dihapus.');
            return;
        }
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Anda akan menghapus <strong>${selectedUsers.length} pengguna</strong> yang dipilih secara permanen. Tindakan ini tidak dapat dikembalikan.
                        </p>
                    </div>
                    <div class="mt-6 flex justify-center space-x-3">
                        <button id="cancelMultipleDeleteButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            Batal
                        </button>
                        <button id="confirmMultipleDeleteButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            <i class="bi bi-trash mr-2"></i>
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        modal.querySelector('#cancelMultipleDeleteButton').onclick = () => document.body.removeChild(modal);
        modal.querySelector('#confirmMultipleDeleteButton').onclick = () => {
            deleteMultipleUsers(selectedUsers);
            document.body.removeChild(modal);
        };
        modal.addEventListener('click', function(e) {
            if (e.target === modal) document.body.removeChild(modal);
        });
    }
    function deleteMultipleUsers(selectedUsers) {
        const userIds = Array.from(selectedUsers).map(btn => btn.getAttribute('data-user-id'));
        console.log('Deleting users:', userIds);
        showProcessingModal(selectedUsers.length);
        fetch('/kelola-pengguna/delete-multiple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ user_ids: userIds })
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.processing-modal')?.remove();
            if (data.success) {
                showSuccessModal(data.message);
            } else {
                showAlertModal('Error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.processing-modal')?.remove();
            showAlertModal('Error', 'Terjadi kesalahan saat menghapus pengguna.');
        });
    }
    function showProcessingEditModal() {
        const modal = document.createElement('div');
        modal.className = 'processing-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <i class="bi bi-pencil text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Menyimpan Perubahan</h3>
                        <p class="text-sm text-gray-500 mb-4">Sedang menyimpan perubahan data pengguna...</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-gradient-to-r from-blue-900 to-red-700 h-2 rounded-full animate-pulse" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    function showProcessingModal(userCount, userName = '') {
        const message = userName ? 
            `Sedang menghapus pengguna ${userName}...` : 
            `Sedang menghapus ${userCount} pengguna yang dipilih...`;
        const modal = document.createElement('div');
        modal.className = 'processing-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <i class="bi bi-trash text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Menghapus Pengguna</h3>
                        <p class="text-sm text-gray-500 mb-4">${message}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-gradient-to-r from-blue-900 to-red-700 h-2 rounded-full animate-pulse" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    function showSuccessModal(message) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="bi bi-check-lg text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Berhasil!</h3>
                        <p class="text-sm text-gray-500 mb-4">${message}</p>
                    </div>
                    <div class="mt-6 flex justify-center">
                        <button id="successOkButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        modal.querySelector('#successOkButton').onclick = () => {
            document.body.removeChild(modal);
            location.reload();
        };
    }
    
    function showAlertModal(title, message) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                            <i class="bi bi-exclamation-circle text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">${title}</h3>
                        <p class="text-sm text-gray-500 mb-4">${message}</p>
                    </div>
                    <div class="mt-6 flex justify-center">
                        <button id="alertOkButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        modal.querySelector('#alertOkButton').onclick = () => document.body.removeChild(modal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal){ document.body.removeChild(modal);
            }
        });
    }
});
</script>
@endpush