@extends('layouts.pageadmin')

@section('title', 'Kelola Jadwal Mata Kuliah - ITLG Lab Management System')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-16 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    <th class="w-16 px-2 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
                    <th class="w-32 px-4 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Row 1 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden"></i>
                        </button>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 text-center">1</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum ProWeb</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-jadwal='{"id":1,"nama":"Praktikum ProWeb","ruangan":"Lab. Jaringan 1","kelas":"KOM A1","hari":"Senin","waktu":"08:00 - 09:40"}'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-jadwal-id="1" data-jadwal-nama="Praktikum ProWeb">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden"></i>
                        </button>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 text-center">2</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum DI</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-jadwal='{"id":2,"nama":"Praktikum DI","ruangan":"Lab. Jaringan 2","kelas":"KOM A2","hari":"Selasa","waktu":"10:00 - 11:40"}'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-jadwal-id="2" data-jadwal-nama="Praktikum DI">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden"></i>
                        </button>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 text-center">3</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum MSBD</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-jadwal='{"id":3,"nama":"Praktikum MSBD","ruangan":"Lab. Jaringan 3","kelas":"KOM B1","hari":"Rabu","waktu":"13:00 - 14:40"}'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-jadwal-id="3" data-jadwal-nama="Praktikum MSBD">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 4 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden"></i>
                        </button>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 text-center">4</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum EVA</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-jadwal='{"id":4,"nama":"Praktikum EVA","ruangan":"Lab Multimedia","kelas":"KOM B2","hari":"Kamis","waktu":"08:00 - 09:40"}'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-jadwal-id="4" data-jadwal-nama="Praktikum EVA">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Row 5 -->
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 transition-colors">
                    <td class="px-2 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="toggle-btn inline-flex items-center justify-center w-8 h-8 bg-gray-200 text-gray-400 rounded-md hover:bg-gray-300 transition-colors">
                            <i class="bi bi-check-lg hidden"></i>
                        </button>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 text-center">5</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Praktikum PBOL</td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button class="edit-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Edit" data-jadwal='{"id":5,"nama":"Praktikum PBOL","ruangan":"Lab. Jaringan 1","kelas":"KOM C1","hari":"Jumat","waktu":"15:00 - 16:40"}'>
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-single-btn inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg" title="Hapus" data-jadwal-id="5" data-jadwal-nama="Praktikum PBOL">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tombol Submit -->
    <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
        <button id="submitButton" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
            <i class="bi bi-check-lg mr-2"></i>
            Submit
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                const jadwalData = JSON.parse(this.getAttribute('data-jadwal'));
                showEditModal(jadwalData);
            });
        });

        // Event listener untuk tombol hapus individual
        const deleteSingleButtons = document.querySelectorAll('.delete-single-btn');
        deleteSingleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const jadwalId = this.getAttribute('data-jadwal-id');
                const jadwalNama = this.getAttribute('data-jadwal-nama');
                showDeleteConfirmationModal(jadwalId, jadwalNama);
            });
        });

        // Modal konfirmasi submit
        function showSubmitConfirmationModal() {
            // Cek apakah ada jadwal yang dipilih
            const selectedJadwal = document.querySelectorAll('.toggle-btn.active');
            
            if (selectedJadwal.length === 0) {
                showAlertModal('Peringatan', 'Tidak ada mata kuliah yang dipilih untuk disubmit.');
                return;
            }

            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                <i class="bi bi-check-lg text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Submit</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Anda akan mengirim <strong>${selectedJadwal.length} mata kuliah</strong> yang dipilih. Lanjutkan?
                            </p>
                        </div>
                        <div class="mt-6 flex justify-center space-x-3">
                            <button id="cancelSubmitButton" class="inline-flex items-center px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                Batal
                            </button>
                            <button id="confirmSubmitButton" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
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
                submitSelectedJadwal();
            });
        }

        function submitSelectedJadwal() {
            const selectedJadwal = document.querySelectorAll('.toggle-btn.active');
            const processingModal = showProcessingModal(`Sedang mengirim ${selectedJadwal.length} mata kuliah yang dipilih...`);
            
            // Simulasi proses submit
            setTimeout(() => {
                document.body.removeChild(processingModal);
                showSuccessModal(`${selectedJadwal.length} mata kuliah berhasil dikirim!`);
            }, 2000);
        }

        // Modal konfirmasi hapus
        function showDeleteConfirmationModal(jadwalId, jadwalNama) {
            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Anda akan menghapus mata kuliah <strong>${jadwalNama}</strong>. Tindakan ini tidak dapat dikembalikan.
                            </p>
                        </div>
                        <div class="mt-6 flex justify-center space-x-3">
                            <button id="cancelDeleteButton" class="inline-flex items-center px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                Batal
                            </button>
                            <button id="confirmDeleteButton" class="inline-flex items-center px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
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
                deleteJadwal(jadwalId, jadwalNama);
            });
        }

        function deleteJadwal(jadwalId, jadwalNama) {
            const processingModal = showProcessingModal(`Sedang menghapus mata kuliah ${jadwalNama}...`);
            
            // Simulasi proses hapus
            setTimeout(() => {
                document.body.removeChild(processingModal);
                showSuccessModal(`Mata kuliah ${jadwalNama} berhasil dihapus!`);
            }, 1500);
        }

        // Modal Edit
        function showEditModal(jadwalData) {
            const modal = createModal(`
                <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Edit Mata Kuliah</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <!-- Nama Mata Kuliah -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nama Mata Kuliah</label>
                                <input type="text" 
                                       value="${jadwalData.nama}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                        <div class="mt-6 flex justify-center space-x-3">
                            <button id="cancelEditButton" class="inline-flex items-center px-6 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                Batal
                            </button>
                            <button id="saveEditButton" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
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
            
            // Event listener untuk tombol Simpan - FIXED
            const saveButton = document.getElementById('saveEditButton');
            const saveHandler = function() {
                document.body.removeChild(modal);
                const processingModal = showProcessingModal('Sedang menyimpan perubahan...');
                
                // Simulasi proses edit
                setTimeout(() => {
                    document.body.removeChild(processingModal);
                    showSuccessModal('Data mata kuliah berhasil diperbarui!');
                }, 1500);
            };
            
            saveButton.addEventListener('click', saveHandler);
        }

        // Helper function untuk membuat modal
        function createModal(html) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
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
                            <button id="successOkButton" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
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
                            <button id="alertOkButton" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-md hover:from-blue-800 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
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