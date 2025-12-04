@extends('layouts.main')

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
            
            <!-- Nama Lengkap (Auto-filled & Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nama_lengkap"
                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm sm:text-base"
                       value="{{ auth()->user()->name }}"
                       disabled>
            </div>

            <!-- NIM (Auto-filled & Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    NIM <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nim"
                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm sm:text-base"
                       value="{{ auth()->user()->nim }}"
                       disabled>
            </div>

            <!-- Mata Kuliah -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select name="course_id" 
                        id="mata_kuliah"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                        required>
                    <option value="">Pilih Mata Kuliah</option>
                    <!-- Mata kuliah akan dimuat via JavaScript -->
                </select>
            </div>

            <!-- Kelas -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="class_id" 
                        id="kelas"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                        disabled
                        required>
                    <option value="">Pilih Kelas</option>
                </select>
            </div>

            <!-- Gedung (Auto-filled & Disabled) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Gedung <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="gedung"
                       class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 text-sm sm:text-base"
                       value="Gedung C"
                       disabled>
                <input type="hidden" name="building_id" value="C">
            </div>

            <!-- Ruangan -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Ruangan <span class="text-red-500">*</span>
                </label>
                <select name="room_id" 
                        id="ruangan"
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                        required>
                    <option value="">Pilih Ruangan</option>
                    <!-- Ruangan dari Gedung C akan dimuat via JavaScript -->
                </select>
            </div>

            <!-- Hari -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Hari <span class="text-red-500">*</span>
                </label>
                <select name="day_of_week" 
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                        required>
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                </select>
            </div>

            <!-- Waktu (Single Dropdown) -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    Waktu <span class="text-red-500">*</span>
                </label>
                <select name="time_slot" 
                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                        required>
                    <option value="">Pilih Waktu</option>
                    <option value="08.00 - 09:40">08.00 - 09:40</option>
                    <option value="09:40 - 11:20">09:40 - 11:20</option>
                    <option value="11:20 - 13:00">11:20 - 13:00</option>
                    <option value="13:00 - 14:40">13:00 - 14:40</option>
                    <option value="14:40 - 16:20">14:40 - 16:20</option>
                </select>
            </div>

            <!-- Button Group -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6">
                @php
                    $user = auth()->user();
                    $isAdmin = $user->roles->contains('status', 'admin') || $user->roles->contains('status', 'bph');
                    $backRoute = $isAdmin ? route('ambil-jadwal-admin') : route('ambil-jadwal');
                @endphp
                <a href="{{ $backRoute }}" 
                   class="flex-1 px-4 py-3 sm:px-6 sm:py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold text-center transition-all duration-200 text-sm sm:text-base">
                    Kembali
                </a>
                <button type="submit" 
                        class="flex-1 px-4 py-3 sm:px-6 sm:py-3 bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white rounded-lg font-semibold transition-all duration-200 text-sm sm:text-base">
                    Ambil Jadwal
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

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto">
        <div class="p-6 text-center">
            <!-- Success Icon -->
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-green-500 text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h3>
            <p class="text-gray-600 mb-6 text-base">
                Jadwal berhasil diambil. Anda akan diarahkan ke halaman jadwal.
            </p>
            
            <button id="successOk" 
                    class="w-full bg-gradient-to-r from-blue-900 to-red-700 hover:from-red-600 hover:to-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-auto">
        <div class="p-6 text-center">
            <!-- Error Icon -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Gagal!</h3>
            <p id="errorMessage" class="text-gray-600 mb-6 text-base">
                Terjadi kesalahan saat mengambil jadwal.
            </p>
            
            <button id="errorOk" 
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200">
                OK
            </button>
        </div>
    </div>
</div>

<!-- Tambahkan Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* Responsive adjustments */
@media (max-width: 640px) {
    .max-w-4xl {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}

/* Form styling improvements */
input:disabled, select:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

/* Focus states */
input:focus, select:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button hover effects */
button:hover, a:hover {
    transform: translateY(-1px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const form = document.getElementById('formAmbilJadwal');
    const mataKuliahSelect = document.getElementById('mata_kuliah');
    const kelasSelect = document.getElementById('kelas');
    const ruanganSelect = document.getElementById('ruangan');
    const loading = document.getElementById('loading');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    const successOk = document.getElementById('successOk');
    const errorOk = document.getElementById('errorOk');

    // Load initial data
    loadUserCourses();
    loadRoomsForBuildingC();

    // Event: Mata Kuliah change
    mataKuliahSelect.addEventListener('change', function() {
        const courseId = this.value;
        
        if (courseId) {
            loadClassesForCourse(courseId);
        } else {
            kelasSelect.disabled = true;
            kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
        }
    });

    // Event: Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });

    // Event: Modal buttons
    let bookedRoomId = null; // Store room_id from booking
    
    successOk.addEventListener('click', function() {
        successModal.classList.add('hidden');
        // Redirect based on user role (using backRoute from PHP)
        const redirectUrl = '{{ $backRoute }}' + (bookedRoomId ? `?room=${bookedRoomId}` : '');
        window.location.href = redirectUrl;
    });

    errorOk.addEventListener('click', function() {
        errorModal.classList.add('hidden');
    });

    // Functions
    let coursesData = []; // Store full course data including classes
    
    function loadUserCourses() {
        // API call to get ALL courses for active semester (Ganjil/Genap)
        // Not user-specific - gets all available courses for the current semester
        fetch('/api/schedules/active-courses')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    coursesData = result.data; // Store full data
                    populateCourseSelect(result.data);
                    
                    // Log semester info
                    if (result.meta) {
                        console.log('📚 Loaded courses for:', result.meta.current_semester, result.meta.period);
                    }
                } else {
                    showError('Gagal memuat data mata kuliah');
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                showError('Terjadi kesalahan saat memuat mata kuliah');
            });
    }

    function populateCourseSelect(courses) {
        mataKuliahSelect.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
        
        courses.forEach(course => {
            const option = document.createElement('option');
            option.value = course.course_id;
            option.textContent = `${course.course_code} - ${course.course_name}`;
            mataKuliahSelect.appendChild(option);
        });
    }

    function loadRoomsForBuildingC() {
        // Directly load all rooms (all in Gedung C based on location column)
        fetch('/api/rooms')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    populateRoomSelect(result.data);
                } else {
                    showError('Gagal memuat data ruangan');
                }
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
                showError('Terjadi kesalahan saat memuat ruangan');
            });
    }

    function populateRoomSelect(rooms) {
        ruanganSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        rooms.forEach(room => {
            const option = document.createElement('option');
            option.value = room.room_id;
            option.textContent = room.room_name;
            ruanganSelect.appendChild(option);
        });
    }

    async function loadClassesForCourse(courseId) {
        try {
            // Get classes from stored data
            const course = coursesData.find(c => c.course_id == courseId);
            
            if (!course || !course.classes) {
                kelasSelect.innerHTML = '<option value="">Tidak ada kelas tersedia</option>';
                kelasSelect.disabled = true;
                return;
            }

            // Fetch user's existing schedules for this course to filter out already-booked classes
            const response = await fetch(`/api/schedules/user-booked-classes/${courseId}`);
            const result = await response.json();
            
            let bookedClassIds = [];
            if (result.success && result.data) {
                bookedClassIds = result.data.booked_class_ids || [];
            }
            
            console.log(`Course ${courseId} - Booked class IDs:`, bookedClassIds);
            
            // Filter out classes that are already booked by this user
            const availableClasses = course.classes.filter(classItem => {
                return !bookedClassIds.includes(classItem.class_id);
            });
            
            if (availableClasses.length === 0) {
                kelasSelect.innerHTML = '<option value="">Semua kelas sudah diambil</option>';
                kelasSelect.disabled = true;
            } else {
                populateClassSelect(availableClasses);
            }
        } catch (error) {
            console.error('Error loading classes:', error);
            kelasSelect.innerHTML = '<option value="">Error loading classes</option>';
            kelasSelect.disabled = true;
        }
    }

    function populateClassSelect(classes) {
        kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
        
        classes.forEach(classItem => {
            const option = document.createElement('option');
            option.value = classItem.class_id;
            option.textContent = classItem.class_name;
            kelasSelect.appendChild(option);
        });
        
        kelasSelect.disabled = false;
    }

    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                highlightError(field);
            } else {
                removeErrorHighlight(field);
            }
        });

        if (!isValid) {
            showError('Harap lengkapi semua field yang wajib diisi');
        }

        return isValid;
    }

    function highlightError(field) {
        field.classList.add('border-red-500', 'bg-red-50');
    }

    function removeErrorHighlight(field) {
        field.classList.remove('border-red-500', 'bg-red-50');
    }

    function submitForm() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Save room_id for redirect
        bookedRoomId = data.room_id;

        // Convert time slot to start_time and end_time
        const timeSlot = data.time_slot;
        if (timeSlot) {
            const [startTime, endTime] = timeSlot.split(' - ');
            data.start_time = startTime.replace('.', ':');
            data.end_time = endTime;
        }

        // Show loading
        loading.classList.remove('hidden');

        // API call to submit schedule
        fetch('/api/schedules', {
            method: 'POST',
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
                showSuccess();
            } else {
                showError(result.message || 'Gagal mengambil jadwal');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loading.classList.add('hidden');
            showError('Terjadi kesalahan saat mengambil jadwal');
        });
    }

    function showSuccess() {
        successModal.classList.remove('hidden');
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        errorModal.classList.remove('hidden');
    }
});
</script>
@endsection
