@extends('layouts.pageadmin')

@section('title', 'Kelola Akun Pengguna - ITLG Lab Management System')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header dengan Tombol Tambah dan Hapus -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4 md:py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 text-center md:text-left">Kelola Akun Pengguna</h1>
                <div class="flex items-center justify-center space-x-3">
                    <!-- Button Hapus -->
                    <button id="hapusButton" class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg text-sm md:text-base">
                        <i class="bi bi-trash mr-2 text-xs md:text-sm"></i>
                        Hapus
                    </button>
                    <!-- Button Tambah Pengguna -->
                    <a href="{{ route('tambah-pengguna') }}" class="inline-flex items-center px-3 py-2 md:px-4 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg text-sm md:text-base">
                        <i class="bi bi-plus-lg mr-2 text-xs md:text-sm"></i>
                        Tambah Pengguna
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] md:min-w-0">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-2 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                    <th class="px-3 py-2 md:px-6 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Row 1 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">1</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Aulia Halimatusyaddiah</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">221402170</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">aulishail?8@gmail.com</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">bablabla</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aslab</span>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">Praktikum Kecerdasan Buatan</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">KOM A</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-user='{"id":1,"nama":"Aulia Halimatusyaddiah","nim":"221402170","email":"aulishail?8@gmail.com","password":"bablabla","peran":"aslab","mataKuliah":"kecerdasan_buatan","kelas":"KOM A"}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-user-id="1" data-user-name="Aulia Halimatusyaddiah">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">2</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">David Hartono</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">221402123</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">davidddz@@gmail.com</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">bablabla</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aslab</span>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">Praktikum Desain Interaksi</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">KOM A</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-user='{"id":2,"nama":"David Hartono","nim":"221402123","email":"davidddz@@gmail.com","password":"bablabla","peran":"aslab","mataKuliah":"desain_interaksi","kelas":"KOM A"}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-user-id="2" data-user-name="David Hartono">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">3</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Indah Azzahra</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">221402050</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">indashh29@gmail.com</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">bablabla</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin</span>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">Praktikum Pemrograman Web</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">KOM B</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-user='{"id":3,"nama":"Indah Azzahra","nim":"221402050","email":"indashh29@gmail.com","password":"bablabla","peran":"admin","mataKuliah":"pemrograman_web","kelas":"KOM B"}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-user-id="3" data-user-name="Indah Azzahra">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 4 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-2 md:px-4 md:py-3 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden text-xs md:text-sm"></i>
                        </button>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">4</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium text-gray-900">Nurul Aini</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">221402047</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">nurullf28@gmail.com</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">bablabla</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aslab</span>
                    </td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">Praktikum Web Semantik</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm text-gray-900">KOM B</td>
                    <td class="px-3 py-2 md:px-6 md:py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-user='{"id":4,"nama":"Nurul Aini","nim":"221402047","email":"nurullf28@gmail.com","password":"bablabla","peran":"aslab","mataKuliah":"web_semantik","kelas":"KOM B"}'>
                                <i class="bi bi-pencil text-xs md:text-sm"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-6 h-6 md:w-8 md:h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-user-id="4" data-user-name="Nurul Aini">
                                <i class="bi bi-trash text-xs md:text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle button functionality
    document.addEventListener('DOMContentLoaded', function() {
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

        // Modal konfirmasi hapus (untuk tombol hapus multiple di header)
        const hapusButton = document.getElementById('hapusButton');
        
        hapusButton.addEventListener('click', function() {
            showMultipleDeleteConfirmationModal();
        });

        // Event listener untuk tombol edit
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userData = JSON.parse(this.getAttribute('data-user'));
                showEditModal(userData);
            });
        });

        // Event listener untuk tombol hapus individual
        const deleteSingleButtons = document.querySelectorAll('.delete-single-btn');
        deleteSingleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const userName = this.getAttribute('data-user-name');
                showSingleDeleteConfirmationModal(userId, userName);
            });
        });

        // Modal konfirmasi hapus untuk multiple (tombol di header)
        function showMultipleDeleteConfirmationModal() {
            // Cek apakah ada pengguna yang dipilih
            const selectedUsers = document.querySelectorAll('.toggle-btn.active');
            
            if (selectedUsers.length === 0) {
                showAlertModal('Peringatan', 'Tidak ada pengguna yang dipilih untuk dihapus.');
                return;
            }

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelMultipleDeleteButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Ya, Hapus
            const confirmButton = document.getElementById('confirmMultipleDeleteButton');
            confirmButton.addEventListener('click', function() {
                deleteMultipleUsers();
                document.body.removeChild(modal);
            });
            
            // Event listener untuk klik di luar modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        function deleteMultipleUsers() {
            const selectedUsers = document.querySelectorAll('.toggle-btn.active');
            showProcessingModal(selectedUsers.length);
        }

        // Modal Edit
        function showEditModal(userData) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-4 md:p-6">
                        <div class="text-center mb-4 md:mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Pengguna</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Nama Lengkap -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" 
                                       value="${userData.nama}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>

                            <!-- NIM -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">NIM</label>
                                <input type="text" 
                                       value="${userData.nim}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" 
                                       value="${userData.email}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>

                            <!-- Kata Sandi -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                                <input type="password" 
                                       value="${userData.password}"
                                       class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                            </div>

                            <!-- Peran -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Peran</label>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-3">
                                        <input type="radio" name="peran" value="aslab" ${userData.peran === 'aslab' ? 'checked' : ''} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Aslab</span>
                                    </label>
                                    <label class="flex items-center space-x-3">
                                        <input type="radio" name="peran" value="admin" ${userData.peran === 'admin' ? 'checked' : ''} class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Admin</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="kecerdasan_buatan" ${userData.mataKuliah === 'kecerdasan_buatan' ? 'selected' : ''}>Praktikum Kecerdasan Buatan</option>
                                    <option value="desain_interaksi" ${userData.mataKuliah === 'desain_interaksi' ? 'selected' : ''}>Praktikum Desain Interaksi</option>
                                    <option value="pemrograman_web" ${userData.mataKuliah === 'pemrograman_web' ? 'selected' : ''}>Praktikum Pemrograman Web</option>
                                    <option value="web_semantik" ${userData.mataKuliah === 'web_semantik' ? 'selected' : ''}>Praktikum Web Semantik</option>
                                </select>
                            </div>

                            <!-- Kelas -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                                <select class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm md:text-base">
                                    <option value="KOM A" ${userData.kelas === 'KOM A' ? 'selected' : ''}>KOM A</option>
                                    <option value="KOM B" ${userData.kelas === 'KOM B' ? 'selected' : ''}>KOM B</option>
                                    <option value="KOM C" ${userData.kelas === 'KOM C' ? 'selected' : ''}>KOM C</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-center space-x-3">
                            <button id="cancelEditButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                                Batal
                            </button>
                            <button id="saveEditButton" class="inline-flex items-center px-4 py-2 md:px-6 md:py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm md:text-base">
                                <i class="bi bi-check-lg mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelEditButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Simpan
            const saveButton = document.getElementById('saveEditButton');
            saveButton.addEventListener('click', function() {
                // Di sini tambahkan logika untuk menyimpan perubahan
                showProcessingEditModal();
                document.body.removeChild(modal);
            });
            
            // Event listener untuk klik di luar modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        function showProcessingEditModal() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                                <i class="bi bi-pencil text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Menyimpan Perubahan</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Sedang menyimpan perubahan data pengguna...
                            </p>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="bg-gradient-to-r from-blue-900 to-red-700 h-2 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Simulasi proses edit (ganti dengan AJAX request ke server)
            setTimeout(() => {
                document.body.removeChild(modal);
                showSuccessModal('Data pengguna berhasil diperbarui!');
            }, 1500);
        }

        // Modal konfirmasi hapus untuk individual
        function showSingleDeleteConfirmationModal(userId, userName) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
            
            // Event listener untuk tombol Batal
            const cancelButton = document.getElementById('cancelSingleDeleteButton');
            cancelButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
            
            // Event listener untuk tombol Ya, Hapus
            const confirmButton = document.getElementById('confirmSingleDeleteButton');
            confirmButton.addEventListener('click', function() {
                deleteSingleUser(userId, userName);
                document.body.removeChild(modal);
            });
            
            // Event listener untuk klik di luar modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        function deleteSingleUser(userId, userName) {
            showProcessingModal(1, userName);
        }

        function showProcessingModal(userCount, userName = '') {
            const message = userName ? 
                `Sedang menghapus pengguna ${userName}...` : 
                `Sedang menghapus ${userCount} pengguna yang dipilih...`;
            
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
                                <div class="bg-gradient-to-r from-blue-900 to-red-700 h-2 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Simulasi proses hapus
            setTimeout(() => {
                document.body.removeChild(modal);
                const successMessage = userName ? 
                    `Pengguna ${userName} berhasil dihapus!` : 
                    `${userCount} pengguna berhasil dihapus!`;
                showSuccessModal(successMessage);
            }, 2000);
        }

        function showSuccessModal(message) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
            
            const okButton = document.getElementById('successOkButton');
            okButton.addEventListener('click', function() {
                document.body.removeChild(modal);
                // Refresh halaman atau update tabel
                location.reload();
            });
        }

        function showAlertModal(title, message) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
            
            const okButton = document.getElementById('alertOkButton');
            okButton.addEventListener('click', function() {
                document.body.removeChild(modal);
            });
        }
    });
</script>
@endpush