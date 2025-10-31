@extends('layouts.main')

@section('title', 'Kelola Mata Kuliah - ITLG Lab Management System')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Filter Semester -->
    <div class="px-4 md:px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-gray-900 mb-3 sm:mb-0">Kelola Mata Kuliah</h2>
            <div class="flex space-x-2">
                <button id="semesterGanjil" class="semester-btn inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-xs md:text-sm">
                    Semester Ganjil
                </button>
                <button id="semesterGenap" class="semester-btn inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-all duration-200 font-medium text-xs md:text-sm">
                    Semester Genap
                </button>
            </div>
        </div>
    </div>

    <!-- Header dengan Tombol Tambah Matkul -->
    <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <h3 id="semesterTitle" class="text-sm md:text-md font-medium text-gray-900">Mata Kuliah Semester Ganjil</h3>
        <button id="tambahMatkulBtn" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm w-full sm:w-auto justify-center">
            <i class="bi bi-plus-circle mr-2 text-sm"></i>
            Tambah Matkul
        </button>
    </div>

    <!-- Table untuk Semua Device -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] md:min-w-0">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-12 md:w-16 px-2 py-2 md:px-2 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Matkul</th>
                    <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KOM</th>
                    <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                    <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dosen</th>
                    <th class="w-28 md:w-32 px-3 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="matkulTableBody">
                {{-- Semester Ganjil --}}
                @php $noGanjil = 1; @endphp
                @forelse($coursesGanjil as $course)
                    @foreach($course->courseClasses as $class)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="ganjil">
                        <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">{{ $noGanjil++ }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->course_code }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->course_name }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $class->class_name }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $course->semester }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $class->lecturer }}</td>
                        <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-start space-x-2">
                                <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-class-id="{{ $class->class_id }}">
                                    <i class="bi bi-pencil text-xs md:text-sm"></i>
                                </button>
                                <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-class-id="{{ $class->class_id }}" data-matkul-nama="{{ $course->course_name }} - {{ $class->class_name }}">
                                    <i class="bi bi-trash text-xs md:text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @empty
                    <tr data-semester="ganjil">
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data mata kuliah semester ganjil
                        </td>
                    </tr>
                @endforelse

                {{-- Semester Genap --}}
                @php $noGenap = 1; @endphp
                @forelse($coursesGenap as $course)
                    @foreach($course->courseClasses as $class)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="genap" style="display: none;">
                        <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">{{ $noGenap++ }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->course_code }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->course_name }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $class->class_name }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $course->semester }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">{{ $class->lecturer }}</td>
                        <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-start space-x-2">
                                <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-class-id="{{ $class->class_id }}">
                                    <i class="bi bi-pencil text-xs md:text-sm"></i>
                                </button>
                                <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-class-id="{{ $class->class_id }}" data-matkul-nama="{{ $course->course_name }} - {{ $class->class_name }}">
                                    <i class="bi bi-trash text-xs md:text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @empty
                    <tr data-semester="genap" style="display: none;">
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada data mata kuliah semester genap
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tombol Submit untuk Semua Device -->
    <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex justify-center sm:justify-end">
        <button id="submitButton" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto justify-center">
            <i class="bi bi-check-lg mr-2"></i>
            Submit
        </button>
    </div>
</div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const semesterGanjilBtn = document.getElementById('semesterGanjil');
    const semesterGenapBtn = document.getElementById('semesterGenap');
    const matkulTableBody = document.getElementById('matkulTableBody');
    const matkulRows = matkulTableBody.querySelectorAll('tr');
    const semesterTitle = document.getElementById('semesterTitle');
    const tambahMatkulBtn = document.getElementById('tambahMatkulBtn');
    
    let activeSemester = 'ganjil';
    filterMatkulBySemester(activeSemester);
    
    semesterGanjilBtn.addEventListener('click', function() {
        activeSemester = 'ganjil';
        filterMatkulBySemester(activeSemester);
        updateSemesterButtons();
        updateSemesterTitle();
    });
    
    semesterGenapBtn.addEventListener('click', function() {
        activeSemester = 'genap';
        filterMatkulBySemester(activeSemester);
        updateSemesterButtons();
        updateSemesterTitle();
    });
    
    tambahMatkulBtn.addEventListener('click', function() {
        showTambahMatkulModal();
    });
    
    function filterMatkulBySemester(semester) {
        matkulRows.forEach(row => {
            if (row.getAttribute('data-semester') === semester) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function updateSemesterButtons() {
        if (activeSemester === 'ganjil') {
            semesterGanjilBtn.classList.remove('bg-gray-200', 'text-gray-700');
            semesterGanjilBtn.classList.add('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
            semesterGenapBtn.classList.remove('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
            semesterGenapBtn.classList.add('bg-gray-200', 'text-gray-700');
        } else {
            semesterGenapBtn.classList.remove('bg-gray-200', 'text-gray-700');
            semesterGenapBtn.classList.add('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
            semesterGanjilBtn.classList.remove('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
            semesterGanjilBtn.classList.add('bg-gray-200', 'text-gray-700');
        }
    }
    
    function updateSemesterTitle() {
        if (activeSemester === 'ganjil') {
            semesterTitle.textContent = 'Mata Kuliah Semester Ganjil';
        } else {
            semesterTitle.textContent = 'Mata Kuliah Semester Genap';
        }
    }

    const submitButton = document.getElementById('submitButton');
    submitButton.addEventListener('click', function() {
        showSubmitConfirmationModal();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const button = e.target.closest('.edit-btn');
            const classId = button.getAttribute('data-class-id');
            loadEditModal(classId);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-single-btn')) {
            const button = e.target.closest('.delete-single-btn');
            const classId = button.getAttribute('data-class-id');
            const matkulNama = button.getAttribute('data-matkul-nama');
            showDeleteConfirmationModal(classId, matkulNama);
        }
    });

    function loadEditModal(classId) {
        const processingModal = showProcessingModal('Memuat data...');
        
        fetch(`/kelola-matkul/${classId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.body.removeChild(processingModal);
                if (data.success) {
                    showEditModal(data.data);
                } else {
                    showAlertModal('Error', 'Gagal memuat data');
                }
            })
            .catch(error => {
                document.body.removeChild(processingModal);
                showAlertModal('Error', 'Terjadi kesalahan saat memuat data');
                console.error('Error:', error);
            });
    }

    function showTambahMatkulModal() {
        const modal = createModal(`
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-4 md:p-6">
                    <div class="text-center mb-4 md:mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Tambah Mata Kuliah</h3>
                    </div>
                    <form id="formTambahMatkul">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                                <input type="text" name="course_code" placeholder="Masukkan kode mata kuliah" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <input type="text" name="course_name" placeholder="Masukkan nama mata kuliah" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM</label>
                                <div class="relative">
                                    <select name="kom" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg bg-white appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                                        <option value="">Pilih KOM</option>
                                        <option value="Kom A1">Kom A1</option>
                                        <option value="Kom A2">Kom A2</option>
                                        <option value="Kom B1">Kom B1</option>
                                        <option value="Kom B2">Kom B2</option>
                                        <option value="Kom C1">Kom C1</option>
                                        <option value="Kom C2">Kom C2</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Semester</label>
                                <div class="relative">
                                    <select name="semester" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg bg-white appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                                        <option value="Ganjil" ${activeSemester === 'ganjil' ? 'selected' : ''}>Ganjil</option>
                                        <option value="Genap" ${activeSemester === 'genap' ? 'selected' : ''}>Genap</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Dosen</label>
                                <input type="text" name="lecturer" placeholder="Masukkan nama dosen" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button type="button" id="cancelTambahButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">Batal</button>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">Tambah Matkul</button>
                        </div>
                    </form>
                </div>
            </div>
        `);
        
        document.getElementById('cancelTambahButton').addEventListener('click', () => document.body.removeChild(modal));
        
        document.getElementById('formTambahMatkul').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            document.body.removeChild(modal);
            const processingModal = showProcessingModal('Sedang menambahkan mata kuliah...');
            
            fetch('/kelola-matkul', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            })
            .then(response => response.json())
            .then(data => {
                document.body.removeChild(processingModal);
                if (data.success) {
                    showSuccessModal(data.message, true);
                } else {
                    showAlertModal('Error', data.message);
                }
            })
            .catch(error => {
                document.body.removeChild(processingModal);
                showAlertModal('Error', 'Terjadi kesalahan saat menambahkan mata kuliah');
                console.error('Error:', error);
            });
        });
    }

    function showEditModal(matkulData) {
        const modal = createModal(`
            <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-4 md:p-6">
                    <div class="text-center mb-4 md:mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Mata Kuliah</h3>
                    </div>
                    <form id="formEditMatkul">
                        <input type="hidden" name="class_id" value="${matkulData.class_id}">
                        <input type="hidden" name="_method" value="PUT">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                                <input type="text" value="${matkulData.course_code}" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg bg-gray-100 text-sm md:text-base" readonly>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <input type="text" name="course_name" value="${matkulData.course_name}" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM</label>
                                <div class="relative">
                                    <select name="kom" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg bg-white appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                                        <option value="">Pilih KOM</option>
                                        <option value="Kom A1" ${matkulData.kom === 'Kom A1' ? 'selected' : ''}>Kom A1</option>
                                        <option value="Kom A2" ${matkulData.kom === 'Kom A2' ? 'selected' : ''}>Kom A2</option>
                                        <option value="Kom B1" ${matkulData.kom === 'Kom B1' ? 'selected' : ''}>Kom B1</option>
                                        <option value="Kom B2" ${matkulData.kom === 'Kom B2' ? 'selected' : ''}>Kom B2</option>
                                        <option value="Kom C1" ${matkulData.kom === 'Kom C1' ? 'selected' : ''}>Kom C1</option>
                                        <option value="Kom C2" ${matkulData.kom === 'Kom C2' ? 'selected' : ''}>Kom C2</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Semester</label>
                                <div class="relative">
                                    <select name="semester" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg bg-white appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                                        <option value="Ganjil" ${matkulData.semester === 'Ganjil' ? 'selected' : ''}>Ganjil</option>
                                        <option value="Genap" ${matkulData.semester === 'Genap' ? 'selected' : ''}>Genap</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Dosen</label>
                                <input type="text" name="lecturer" value="${matkulData.lecturer}" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base" required>
                                <p class="text-xs text-gray-500 mt-1">*Nama dosen akan otomatis diupdate untuk semua kelas dengan prefix yang sama (contoh: Kom A1 dan Kom A2)</p>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button type="button" id="cancelEditButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">Batal</button>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto"><i class="bi bi-check-lg mr-2"></i>Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        `);
        
        document.getElementById('cancelEditButton').addEventListener('click', () => document.body.removeChild(modal));
        
        document.getElementById('formEditMatkul').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const classId = formData.get('class_id');
            document.body.removeChild(modal);
            const processingModal = showProcessingModal('Sedang menyimpan perubahan...');
            
            fetch(`/kelola-matkul/${classId}`, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            })
            .then(response => response.json())
            .then(data => {
                document.body.removeChild(processingModal);
                if (data.success) {
                    showSuccessModal(data.message, true);
                } else {
                    showAlertModal('Error', data.message);
                }
            })
            .catch(error => {
                document.body.removeChild(processingModal);
                showAlertModal('Error', 'Terjadi kesalahan saat menyimpan perubahan');
                console.error('Error:', error);
            });
        });
    }

    function showSubmitConfirmationModal() {
        const modal = createModal(`
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-4 md:p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="bi bi-check-lg text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Submit</h3>
                        <p class="text-sm text-gray-500 mb-4">Anda akan mengirim semua data mata kuliah. Lanjutkan?</p>
                    </div>
                    <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                        <button id="cancelSubmitButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">Batal</button>
                        <button id="confirmSubmitButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto"><i class="bi bi-check-lg mr-2"></i>Ya, Submit</button>
                    </div>
                </div>
            </div>
        `);
        
        document.getElementById('cancelSubmitButton').addEventListener('click', () => document.body.removeChild(modal));
        document.getElementById('confirmSubmitButton').addEventListener('click', function() {
            document.body.removeChild(modal);
            submitMatkul();
        });
    }

    function submitMatkul() {
        const processingModal = showProcessingModal('Sedang mengirim data mata kuliah...');
        
        fetch('/kelola-matkul/submit', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.body.removeChild(processingModal);
            if (data.success) {
                showSuccessModal(data.message);
            } else {
                showAlertModal('Error', data.message);
            }
        })
        .catch(error => {
            document.body.removeChild(processingModal);
            showAlertModal('Error', 'Terjadi kesalahan saat mengirim data');
            console.error('Error:', error);
        });
    }

    function showDeleteConfirmationModal(classId, matkulNama) {
        const modal = createModal(`
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-4 md:p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                        <p class="text-sm text-gray-500 mb-4">Anda akan menghapus mata kuliah <strong>${matkulNama}</strong>. Tindakan ini tidak dapat dikembalikan.</p>
                    </div>
                    <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                        <button id="cancelDeleteButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">Batal</button>
                        <button id="confirmDeleteButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto"><i class="bi bi-trash mr-2"></i>Ya, Hapus</button>
                    </div>
                </div>
            </div>
        `);
        
        document.getElementById('cancelDeleteButton').addEventListener('click', () => document.body.removeChild(modal));
        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            document.body.removeChild(modal);
            deleteMatkul(classId, matkulNama);
        });
    }

    function deleteMatkul(classId, matkulNama) {
        const processingModal = showProcessingModal('Sedang menghapus mata kuliah...');
        
        fetch(`/kelola-matkul/${classId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.body.removeChild(processingModal);
            if (data.success) {
                showSuccessModal(data.message, true);
            } else {
                showAlertModal('Error', data.message);
            }
        })
        .catch(error => {
            document.body.removeChild(processingModal);
            showAlertModal('Error', 'Terjadi kesalahan saat menghapus mata kuliah');
            console.error('Error:', error);
        });
    }

    function createModal(html) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = html;
        document.body.appendChild(modal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
        return modal;
    }

    function showProcessingModal(message) {
        const modal = createModal(`
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                            <i class="bi bi-arrow-repeat text-blue-600 text-xl animate-spin"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Memproses</h3>
                        <p class="text-sm text-gray-500 mb-4">${message}</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-gradient-to-r from-blue-900 to-red-700 h-2 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        return modal;
    }

    function showSuccessModal(message, reload = false) {
        const modal = createModal(`
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
                        <button id="successOkButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">OK</button>
                    </div>
                </div>
            </div>
        `);
        
        document.getElementById('successOkButton').addEventListener('click', function() {
            document.body.removeChild(modal);
            if (reload) {
                location.reload();
            }
        });
    }

    function showAlertModal(title, message) {
        const modal = createModal(`
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
                        <button id="alertOkButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">OK</button>
                    </div>
                </div>
            </div>
        `);
        
        document.getElementById('alertOkButton').addEventListener('click', function() {
            document.body.removeChild(modal);
        });
    }
});
</script>
@endpush