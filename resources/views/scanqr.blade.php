@extends('layouts.main')

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

            {{-- Area Scanner --}}
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

            {{-- Tombol Scanner --}}
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

    {{-- ### BAGIAN MODAL YANG DIPERBAIKI ### --}}
    {{-- Ganti 'items-center' menjadi 'items-start' dan tambahkan 'pt-20' --}}
        <div id="info-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform transition-all">
            <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 text-center rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white">Informasi Jadwal</h2>
            </div>
            <div class="p-6 md:p-8">
                <div id="modal-content" class="space-y-4">
                    {{-- Konten informasi akan diisi oleh JavaScript --}}
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="button" onclick="handleOkClick()"
                        class="px-8 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Kode JavaScript Anda tidak perlu diubah, jadi biarkan seperti semula --}}
<script>
    let stream;
    function startScanner() {
        const video = document.getElementById('qr-video');
        const status = document.getElementById('scan-status');
        document.getElementById('start-scan').style.display = 'none';
        document.getElementById('stop-scan').style.display = 'block';

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function (mediaStream) {
                stream = mediaStream;
                video.srcObject = mediaStream;
                status.innerHTML = '<p class="text-green-600">Scanner aktif...</p>';
                setTimeout(simulateScan, 3000);
            })
            .catch(function (error) {
                status.innerHTML = '<p class="text-red-600">Error: Tidak dapat mengakses kamera</p>';
            });
        }
    }

    function stopScanner() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        document.getElementById('start-scan').style.display = 'block';
        document.getElementById('stop-scan').style.display = 'none';
        document.getElementById('scan-status').innerHTML = '<p>Posisikan QR code dalam area scanner</p>';
    }
    
    function simulateScan() {
        stopScanner();
        const jadwalInfo = {
            tanggal: '29 September 2025',
            nama: 'Aulia Halimatusyaddiah',
            nim: '221402170',
            kelas: 'KOM B1',
            waktu: '08:00 - 09:40',
            matakuliah: 'Praktikum Web Semantik',
            ruangan: 'Lab Jaringan 1'
        };
        showInfoModal(jadwalInfo);
    }

    function showInfoModal(data) {
        const modal = document.getElementById('info-modal');
        const modalContent = document.getElementById('modal-content');

        modalContent.innerHTML = `
            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-gray-50"><td class="px-4 py-3 font-medium text-gray-600 w-1/3">Tanggal</td><td class="px-4 py-3 text-gray-800 font-semibold">${data.tanggal}</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-gray-600">Nama</td><td class="px-4 py-3 text-gray-800">${data.nama}</td></tr>
                        <tr class="bg-gray-50"><td class="px-4 py-3 font-medium text-gray-600">NIM</td><td class="px-4 py-3 text-gray-800">${data.nim}</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-gray-600">Mata Kuliah</td><td class="px-4 py-3 text-gray-800">${data.matakuliah}</td></tr>
                        <tr class="bg-gray-50"><td class="px-4 py-3 font-medium text-gray-600">Kelas</td><td class="px-4 py-3 text-gray-800">${data.kelas}</td></tr>
                        <tr><td class="px-4 py-3 font-medium text-gray-600">Ruangan</td><td class="px-4 py-3 text-gray-800">${data.ruangan}</td></tr>
                        <tr class="bg-gray-50"><td class="px-4 py-3 font-medium text-gray-600">Waktu</td><td class="px-4 py-3 text-gray-800">${data.waktu}</td></tr>
                    </tbody>
                </table>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    function handleOkClick() {
        localStorage.setItem('lab1_status', 'occupied'); 
        const modal = document.getElementById('info-modal');
        modal.classList.add('hidden');
        window.location.href = "{{ route('profile.edit') }}"; 
    }

    window.addEventListener('beforeunload', stopScanner);
</script>
@endpush