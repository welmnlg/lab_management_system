@extends('layouts.app')

@section('title', 'Scan QR')

@section('content')

    {{-- KONTEN INTI HALAMAN SCAN QR --}}
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 bg-gray-100 px-4 py-2 rounded-lg">
                    Arahkan Kode QR dalam kotak untuk mulai memindai
                </p>
            </div>

            <div class="relative bg-gray-200 rounded-lg overflow-hidden mb-4" style="aspect-ratio: 1/1;">
                <video id="qr-video" class="w-full h-full object-cover" autoplay playsinline></video>

                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="relative w-64 h-64">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-900 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-900 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-900 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-900 rounded-br-lg"></div>

                        <div class="absolute inset-x-4 top-4 h-0.5 bg-red-500 animate-scan" id="scan-line">
                            <div class="w-full h-full bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="scan-status" class="text-center text-sm text-gray-600 mb-4">
                <p>Posisikan QR code dalam area scanner</p>
            </div>

            <div class="flex space-x-3">
                <button id="start-scan" onclick="startScanner()"
                    class="flex-1 bg-gradient-to-r from-blue-900 to-red-700 text-white py-3 px-4 rounded-lg font-medium hover:opacity-90 transition-opacity">
                    Mulai Scan
                </button>

                <button id="stop-scan" onclick="stopScanner()"
                    class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-gray-700 transition-colors"
                    style="display: none;">
                    Berhenti
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL FORM SETELAH SCAN BERHASIL --}}
    <div id="form-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl m-4 p-6 md:p-8 transform transition-all">
            <form action="#" method="POST">
                {{-- Isi form Anda di sini... --}}
                <div class="space-y-6">
                    <div>
                        <label for="nama-lengkap" class="block text-sm font-medium text-gray-800 mb-2">Nama Lengkap</label>
                        <input type="text" id="nama-lengkap" name="nama-lengkap"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama lengkap Anda">
                    </div>
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-800 mb-2">NIM</label>
                        <input type="text" id="nim" name="nim"
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan NIM Anda">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-3">Aktivitas</label>
                        <div class="flex items-center space-x-6">
                            <label for="mengajar" class="flex items-center cursor-pointer">
                                <input type="radio" id="mengajar" name="aktivitas" value="mengajar" class="hidden peer">
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-2 peer-checked:bg-blue-900 peer-checked:border-blue-900"></div>
                                <span class="text-gray-700">Mengajar</span>
                            </label>
                            <label for="belajar" class="flex items-center cursor-pointer">
                                <input type="radio" id="belajar" name="aktivitas" value="belajar" class="hidden peer">
                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-2 peer-checked:bg-blue-900 peer-checked:border-blue-900"></div>
                                <span class="text-gray-700">Belajar</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <select name="ruangan"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option selected disabled>Ruangan yang Digunakan</option>
                                <option value="jaringan1">Lab Jaringan 1</option>
                                <option value="jaringan2">Lab Jaringan 2</option>
                                <option value="jaringan3">Lab Jaringan 3</option>
                                <option value="jaringan4">Lab Jaringan 4</option>
                            </select>
                        </div>
                        <div>
                            <select name="waktu"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option selected disabled>Waktu Pemakaian Ruangan</option>
                                <option value="slot1">08:00 - 09:40</option>
                                <option value="slot2">09:40 - 11:20</option>
                                <option value="slot3">11:20 - 13:00</option>
                                <option value="slot4">13:00 - 14:40</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeFormModal()"
                        class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let video;
    let scanning = false;
    let stream;

    function startScanner() {
        const video = document.getElementById('qr-video');
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        const status = document.getElementById('scan-status');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment' }
            })
            .then(function (mediaStream) {
                stream = mediaStream;
                video.srcObject = mediaStream;
                scanning = true;

                startBtn.style.display = 'none';
                stopBtn.style.display = 'block';
                status.innerHTML = '<p class="text-green-600">Scanner aktif - Arahkan QR code ke kamera</p>';

                setTimeout(() => {
                    if (scanning) {
                        simulateScan();
                    }
                }, 3000);
            })
            .catch(function (error) {
                console.error('Error accessing camera:', error);
                status.innerHTML = '<p class="text-red-600">Error: Tidak dapat mengakses kamera</p>';
            });
        } else {
            status.innerHTML = '<p class="text-red-600">Browser tidak mendukung akses kamera</p>';
        }
    }

    function stopScanner() {
        const video = document.getElementById('qr-video');
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        const status = document.getElementById('scan-status');

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        video.srcObject = null;
        scanning = false;

        startBtn.style.display = 'block';
        stopBtn.style.display = 'none';
        status.innerHTML = '<p>Posisikan QR code dalam area scanner</p>';
    }

    function simulateScan() {
        const result = "LAB-JR-01"; // Contoh ID Ruangan
        stopScanner();
        // Anda perlu menyesuaikan ini dengan logika backend Anda
        showFormModal({ id_ruangan: 'jaringan1', nama_ruangan: 'Lab Jaringan 1' });
    }

    function showFormModal(data) {
        const modal = document.getElementById('form-modal');
        const selectRuangan = document.querySelector('select[name="ruangan"]');
        if (data && selectRuangan) {
            selectRuangan.value = data.id_ruangan;
        }
        modal.classList.remove('hidden');
    }

    function closeFormModal() {
        const modal = document.getElementById('form-modal');
        modal.classList.add('hidden');
    }

    window.addEventListener('beforeunload', function () {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush