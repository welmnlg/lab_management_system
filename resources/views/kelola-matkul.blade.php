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
        <button id="tambahMatkulBtn" class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-xs md:text-sm w-full sm:w-auto">
            <i class="bi bi-plus-circle mr-2 text-xs md:text-sm"></i>
            Tambah Matkul
        </button>
    </div>

    <!-- Table untuk Semua Device -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] md:min-w-0">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-12 md:w-16 px-2 py-2 md:px-3 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <span class="sr-only">Pilih</span>
                    </th>
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
                <!-- Row 1 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="ganjil">
                    <td class="px-2 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">1</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">TIF001</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum ProWeb</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">A</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Ganjil</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Dr. Ahmad Fauzi, M.Kom.</td>
                    <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-start space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-matkul='{"id":1,"kode":"TIF001","nama":"Praktikum ProWeb","kom":"A","semester":"Ganjil","dosen":"Dr. Ahmad Fauzi, M.Kom."}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-matkul-id="1" data-matkul-nama="Praktikum ProWeb">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="ganjil">
                    <td class="px-2 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">2</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">TIF002</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum DI</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">B</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Ganjil</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Prof. Dr. Siti Aminah, M.T.</td>
                    <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-start space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-matkul='{"id":2,"kode":"TIF002","nama":"Praktikum DI","kom":"B","semester":"Ganjil","dosen":"Prof. Dr. Siti Aminah, M.T."}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-matkul-id="2" data-matkul-nama="Praktikum DI">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="ganjil">
                    <td class="px-2 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">3</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">TIF003</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum MSBD</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">C</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Ganjil</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Dr. Budi Santoso, M.Kom.</td>
                    <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-start space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-matkul='{"id":3,"kode":"TIF003","nama":"Praktikum MSBD","kom":"C","semester":"Ganjil","dosen":"Dr. Budi Santoso, M.Kom."}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-matkul-id="3" data-matkul-nama="Praktikum MSBD">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 4 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="genap">
                    <td class="px-2 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">4</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">TIF004</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum EVA</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">A</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Genap</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Dr. Rina Wijaya, M.T.</td>
                    <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-start space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-matkul='{"id":4,"kode":"TIF004","nama":"Praktikum EVA","kom":"A","semester":"Genap","dosen":"Dr. Rina Wijaya, M.T."}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-matkul-id="4" data-matkul-nama="Praktikum EVA">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 5 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors" data-semester="genap">
                    <td class="px-2 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-2 py-2 md:px-2 md:py-3 whitespace-nowrap text-sm text-gray-900 text-center">5</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">TIF005</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum PBOL</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">B</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Genap</td>
                    <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm text-gray-900">Dr. Hendra Gunawan, M.Kom.</td>
                    <td class="px-3 py-2 md:px-3 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-start space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-matkul='{"id":5,"kode":"TIF005","nama":"Praktikum PBOL","kom":"B","semester":"Genap","dosen":"Dr. Hendra Gunawan, M.Kom."}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-7 h-7 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-matkul-id="5" data-matkul-nama="Praktikum PBOL">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tombol Submit untuk Semua Device -->
    <div class="px-4 md:px-6 py-4 border-t border-gray-200 flex justify-end">
        <button id="submitButton" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto justify-center">
            <i class="bi bi-check-lg mr-2"></i>
            Submit
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter semester functionality
        const semesterGanjilBtn = document.getElementById('semesterGanjil');
        const semesterGenapBtn = document.getElementById('semesterGenap');
        const matkulTableBody = document.getElementById('matkulTableBody');
        const matkulRows = matkulTableBody.querySelectorAll('tr');
        const semesterTitle = document.getElementById('semesterTitle');
        const tambahMatkulBtn = document.getElementById('tambahMatkulBtn');
        
        // Set default active semester
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
        
        // Event listener untuk tombol tambah matkul
        tambahMatkulBtn.addEventListener('click', function() {
            showTambahMatkulModal();
        });
        
        function filterMatkulBySemester(semester) {
            // Filter untuk table
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

        // Toggle button functionality
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                // Toggle active class
                this.classList.toggle('active');
                
                // Toggle icon visibility
                if (this.classList.contains('active')) {
                    this.classList.remove('bg-gray-200', 'text-gray-400');
                    this.classList.add('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
                    icon.classList.remove('hidden');
                } else {
                    this.classList.remove('bg-gradient-to-r', 'from-blue-900', 'to-red-700', 'text-white', 'shadow-md');
                    this.classList.add('bg-gray-200', 'text-gray-400');
                    icon.classList.add('hidden');
                }
            });
        });

        // Event listener untuk tombol submit
        const submitButton = document.getElementById('submitButton');
        submitButton.addEventListener('click', function() {
            showSubmitConfirmationModal();
        });

        // Event listener untuk tombol edit
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const matkulData = JSON.parse(this.getAttribute('data-matkul'));
                showEditModal(matkulData);
            });
        });

        // Event listener untuk tombol hapus individual
        const deleteSingleButtons = document.querySelectorAll('.delete-single-btn');
        deleteSingleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const matkulId = this.getAttribute('data-matkul-id');
                const matkulNama = this.getAttribute('data-matkul-nama');
                showDeleteConfirmationModal(matkulId, matkulNama);
            });
        });

        // Modal Tambah Matkul
        function showTambahMatkulModal() {
            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-4 md:p-6">
                        <div class="text-center mb-4 md:mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Tambah Mata Kuliah</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Kode Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                                <input type="text" 
                                       placeholder="Masukkan kode mata kuliah"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>
                            
                            <!-- Nama Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <input type="text" 
                                       placeholder="Masukkan nama mata kuliah"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>
                            
                            <!-- KOM -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="">Pilih KOM</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                            
                            <!-- Semester -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Semester</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="ganjil" ${activeSemester === 'ganjil' ? 'selected' : ''}>Ganjil</option>
                                    <option value="genap" ${activeSemester === 'genap' ? 'selected' : ''}>Genap</option>
                                </select>
                            </div>
                            
                            <!-- Nama Dosen -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Dosen</label>
                                <input type="text" 
                                       placeholder="Masukkan nama dosen"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button id="cancelTambahButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                Batal
                            </button>
                            <button id="simpanTambahButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                Tambah Matkul
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelTambahButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Tambah
            const simpanButton = document.getElementById('simpanTambahButton');
            simpanButton.addEventListener('click', function() {
                document.body.removeChild(modal);
                const processingModal = showProcessingModal('Sedang menambahkan mata kuliah...');
                
                // Simulasi proses tambah
                setTimeout(() => {
                    document.body.removeChild(processingModal);
                    showSuccessModal('Mata kuliah berhasil ditambahkan!');
                }, 1500);
            });
        }

        // Modal konfirmasi submit
        function showSubmitConfirmationModal() {
            // Cek apakah ada matkul yang dipilih
            const selectedMatkul = document.querySelectorAll('.toggle-btn.active');
            
            if (selectedMatkul.length === 0) {
                showAlertModal('Peringatan', 'Tidak ada mata kuliah yang dipilih untuk disubmit.');
                return;
            }

            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-4 md:p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                <i class="bi bi-check-lg text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Submit</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Anda akan mengirim <strong>${selectedMatkul.length} mata kuliah</strong> yang dipilih. Lanjutkan?
                            </p>
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button id="cancelSubmitButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                Batal
                            </button>
                            <button id="confirmSubmitButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                <i class="bi bi-check-lg mr-2"></i>
                                Ya, Submit
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelSubmitButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Ya, Submit
            const confirmButton = document.getElementById('confirmSubmitButton');
            confirmButton.addEventListener('click', function() {
                document.body.removeChild(modal);
                submitSelectedMatkul(selectedMatkul.length);
            });
        }

        function submitSelectedMatkul(count) {
            const processingModal = showProcessingModal(`Sedang mengirim ${count} mata kuliah yang dipilih...`);
            
            // Simulasi proses submit
            setTimeout(() => {
                document.body.removeChild(processingModal);
                showSuccessModal(`${count} mata kuliah berhasil dikirim!`);
            }, 2000);
        }

        // Modal konfirmasi hapus
        function showDeleteConfirmationModal(matkulId, matkulNama) {
            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-4 md:p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Anda akan menghapus mata kuliah <strong>${matkulNama}</strong>. Tindakan ini tidak dapat dikembalikan.
                            </p>
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button id="cancelDeleteButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                Batal
                            </button>
                            <button id="confirmDeleteButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                <i class="bi bi-trash mr-2"></i>
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelDeleteButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Ya, Hapus
            const confirmButton = document.getElementById('confirmDeleteButton');
            confirmButton.addEventListener('click', function() {
                document.body.removeChild(modal);
                deleteMatkul(matkulId, matkulNama);
            });
        }

        function deleteMatkul(matkulId, matkulNama) {
            const processingModal = showProcessingModal(`Sedang menghapus mata kuliah ${matkulNama}...`);
            
            // Simulasi proses hapus
            setTimeout(() => {
                document.body.removeChild(processingModal);
                showSuccessModal(`Mata kuliah ${matkulNama} berhasil dihapus!`);
            }, 1500);
        }

        // Modal Edit
        function showEditModal(matkulData) {
            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-4 md:p-6">
                        <div class="text-center mb-4 md:mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Mata Kuliah</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Kode Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kode Mata Kuliah</label>
                                <input type="text" 
                                       value="${matkulData.kode}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base"
                                       readonly>
                            </div>
                            
                            <!-- Nama Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <input type="text" 
                                       value="${matkulData.nama}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>
                            
                            <!-- KOM -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">KOM</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="A" ${matkulData.kom === 'A' ? 'selected' : ''}>A</option>
                                    <option value="B" ${matkulData.kom === 'B' ? 'selected' : ''}>B</option>
                                    <option value="C" ${matkulData.kom === 'C' ? 'selected' : ''}>C</option>
                                </select>
                            </div>
                            
                            <!-- Semester -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Semester</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="ganjil" ${matkulData.semester === 'Ganjil' ? 'selected' : ''}>Ganjil</option>
                                    <option value="genap" ${matkulData.semester === 'Genap' ? 'selected' : ''}>Genap</option>
                                </select>
                            </div>
                            
                            <!-- Nama Dosen -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Dosen</label>
                                <input type="text" 
                                       value="${matkulData.dosen}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button id="cancelEditButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                Batal
                            </button>
                            <button id="saveEditButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                <i class="bi bi-check-lg mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelEditButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Simpan
            const saveButton = document.getElementById('saveEditButton');
            saveButton.addEventListener('click', function() {
                document.body.removeChild(modal);
                const processingModal = showProcessingModal('Sedang menyimpan perubahan...');
                
                // Simulasi proses edit
                setTimeout(() => {
                    document.body.removeChild(processingModal);
                    showSuccessModal('Data mata kuliah berhasil diperbarui!');
                }, 1500);
            });
        }

        // Helper function untuk membuat modal
        function createModal(html) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
            modal.innerHTML = html;
            
            document.body.appendChild(modal);
            
            // Event listener untuk klik di luar modal
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
                                <i class="bi bi-arrow-repeat text-blue-600 text-xl"></i>
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

        function showSuccessModal(message) {
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
                            <button id="successOkButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            const okButton = document.getElementById('successOkButton');
            okButton.addEventListener('click', function() {
                document.body.removeChild(modal);
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
                            <button id="alertOkButton" class="inline-flex items-center justify-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base w-full sm:w-auto">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            `);
            
            const okButton = document.getElementById('alertOkButton');
            okButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
        }
    });
</script>
@endpush