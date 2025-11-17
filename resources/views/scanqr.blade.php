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
                <div id="reader" class="w-full h-full object-cover" autoplay playsinline></div>
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
                    <button id=confirm-btn type="button" onclick="handleOkClick()"
                        class="px-8 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                        OK
                    </button>
                </div>
            </div>
        </div>

        {{-- Loading Spinner Overlay --}}
        <div id="loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 flex flex-col items-center">
                <svg class="animate-spin h-12 w-12 text-blue-900 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-700 font-medium">Memproses...</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // ============= GLOBAL VARIABLES =============
    let stream;
    let html5QrCode;
    let isProcessing = false; // Prevent multiple scans
    let pendingRoomData = null; // Store data untuk confirm entry
    let isScannerActive = false;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ============= UTILITY FUNCTIONS =============
    
    function showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }

    function showStatus(message, isError = false) {
        const status = document.getElementById('scan-status');
        const color = isError ? 'text-red-600' : 'text-green-600';
        status.innerHTML = `<p class="${color}">${message}</p>`;
    }

    function showAlert(message, type = 'error') {
        const bgColor = type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-[100px] right-4 ${bgColor} border px-4 py-3 rounded-lg shadow-lg z-50 max-w-md`;
        alertDiv.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 font-bold">×</button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // ============= QR SCANNER FUNCTIONS =============

    function startScanner() {
        const video = document.getElementById('reader');
        const status = document.getElementById('scan-status');
        
        document.getElementById('start-scan').style.display = 'none';
        document.getElementById('stop-scan').style.display = 'block';

        // Initialize html5-qrcode scanner
        html5QrCode = new Html5Qrcode("reader");
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera
            config,
            onScanSuccess,
            onScanError
        ).then(() => {
            isScannerActive = true;
            showStatus('Scanner aktif. Arahkan QR code...', false);
        }).catch(err => {
            console.error('Error starting scanner:', err);
            isScannerActive = false;
            showStatus('Error: Tidak dapat mengakses kamera', true);
            showAlert('Tidak dapat mengakses kamera. Pastikan permission sudah diberikan.', 'error');
        });
    }

    function stopScanner() {
       if (html5QrCode) {
        if (isScannerActive) {
            // ✅ hanya hentikan kalau memang aktif
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                resetScannerUI();
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                resetScannerUI();
            }).finally(() => {
                isScannerActive = false;
                isProcessing = false;
            });
        } else {
            // ✅ kalau belum aktif (kamera gagal), cukup reset UI saja
            resetScannerUI();
        }
    } else {
        resetScannerUI();
    }
    }

    function resetScannerUI(messageHtml) {
        document.getElementById('start-scan').style.display = 'block';
        document.getElementById('stop-scan').style.display = 'none';
        document.getElementById('scan-status').innerHTML = '<p>Posisikan QR code dalam area scanner</p>';
    }
    function onScanSuccess(decodedText, decodedResult) {
        // Prevent multiple processing
        if (isProcessing) return;
        
        isProcessing = true;
        console.log('QR Code detected:', decodedText);
        
        // Stop scanner immediately
        stopScanner();
        
        // Process the QR code
        processQrCode(decodedText);
    }

    function onScanError(errorMessage) {
        // Ignore scan errors (normal saat mencari QR code)
    }

    // ============= BACKEND API INTEGRATION =============

    /**
     * Step 1: Verify QR Code dengan Backend
     * Endpoint: POST /api/lab/qr-verify
     */
    function processQrCode(decodedText) {
        // if (isProcessing) {
        //     console.log('Already processing, ignoring duplicate scan');
        //     return;
        // }

        // isProcessing = true;
        // showLoading();

        console.log('QR Code detected:', decodedText);

        // Extract token from URL
        let token = '';
        try {
            const url = new URL(decodedText);
            token = url.searchParams.get('token');
        } catch (e) {
            // If not a valid URL, assume it's the token itself
            token = decodedText;
        }

        if (!token) {
            hideLoading();
            showErrorNotification('QR Code Tidak Valid', 'Format QR code tidak dapat dibaca.');
            isProcessing = false;
            return;
        }

        console.log('Sending token to /api/lab/qr-verify');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF Token available:', !!csrfToken);
        console.log('Token to send:', token.substring(0, 50) + '...');

        fetch('/api/lab/qr-verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include',
            body: JSON.stringify({ token: token })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json().then(data => {
                return { status: response.status, data: data };
            });
        })
        .then(({ status, data }) => {
            hideLoading();
            console.log('API Response:', data);

            if (data.success) {
                // ✅ QR Valid - Show modal
                pendingRoomData = data.data;
                showInfoModal(data.data);
                showSuccessNotification('QR Code Valid!', data.message);

                setTimeout(() => {
                    isProcessing = false;
                }, 5000);
            } else {
                // ❌ QR Invalid - Show specific error notification
                handleQrError(data, status);
                isProcessing = false;
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Fetch error:', error);
            showErrorNotification('Kesalahan Jaringan', 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
            isProcessing = false;
        });
    }


    // ===================================
    // ERROR HANDLING FUNCTIONS
    // ===================================

    function handleQrError(errorData, statusCode) {
        const errorType = errorData.error_type || 'unknown';
        const message = errorData.message || 'Terjadi kesalahan';
        const details = errorData.details || '';

        console.error('QR Validation Error:', errorData);

        // Mapping error types to user-friendly notifications
        const errorNotifications = {
            'auth_required': {
                title: '🔐 Login Diperlukan',
                message: 'Anda harus login terlebih dahulu untuk scan QR code.',
                type: 'warning'
            },
            'invalid_qr': {
                title: '❌ QR Code Tidak Valid',
                message: details || 'QR code yang Anda scan tidak valid atau sudah kadaluarsa.',
                type: 'error'
            },
            'room_not_found': {
                title: '🚫 Ruangan Tidak Ditemukan',
                message: details || 'Ruangan yang Anda tuju tidak ada dalam sistem.',
                type: 'error'
            },
            'no_schedule': {
                title: '📅 Tidak Ada Jadwal',
                message: details || message,
                type: 'warning',
                showScheduleInfo: true
            },
            'wrong_day': {
                title: '📆 Bukan Hari Jadwal Anda',
                message: details || message,
                type: 'info',
                showScheduleInfo: true
            },
            'wrong_time': {
                title: '⏰ Di Luar Waktu Jadwal',
                message: details || message,
                type: 'warning',
                showScheduleInfo: true
            }
        };

        const notification = errorNotifications[errorType] || {
            title: '⚠️ Validasi Gagal',
            message: message,
            type: 'error'
        };

        // Show notification based on type
        if (notification.type === 'error') {
            showErrorNotification(notification.title, notification.message);
        } else if (notification.type === 'warning') {
            showWarningNotification(notification.title, notification.message);
        } else {
            showInfoNotification(notification.title, notification.message);
        }

        // Show additional schedule info if available
        if (notification.showScheduleInfo && errorData.schedule_info) {
            const scheduleInfo = errorData.schedule_info;
            let extraInfo = '';

            if (scheduleInfo.scheduled_day && scheduleInfo.current_day) {
                extraInfo = `Jadwal: ${scheduleInfo.scheduled_day} ${scheduleInfo.time}\nHari ini: ${scheduleInfo.current_day}`;
            } else if (scheduleInfo.scheduled_time) {
                extraInfo = `Jadwal: ${scheduleInfo.scheduled_time}\nSekarang: ${scheduleInfo.current_time}`;
            }

            if (extraInfo) {
                setTimeout(() => {
                    showInfoNotification('ℹ️ Info Jadwal', extraInfo.trim());
                }, 500);
            }
        }
    }

    // ===================================
    // TOAST NOTIFICATION FUNCTIONS
    // ===================================

    function showSuccessNotification(title, message) {
        showToast(title, message, 'success');
    }

    function showErrorNotification(title, message) {
        showToast(title, message, 'error');
    }

    function showWarningNotification(title, message) {
        showToast(title, message, 'warning');
    }

    function showInfoNotification(title, message) {
        showToast(title, message, 'info');
    }

    function showToast(title, message, type = 'info') {
        // Remove existing toast if any
        const existingToast = document.querySelector('.custom-toast');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = `custom-toast toast-${type}`;
        
        const icons = {
            success: '✅',
            error: '❌',
            warning: '⚠️',
            info: 'ℹ️'
        };

        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        toast.innerHTML = `
            <div style="display: flex; align-items: start; gap: 12px;">
                <div style="font-size: 24px;">${icons[type]}</div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; margin-bottom: 4px;">${title}</div>
                    <div style="font-size: 14px; opacity: 0.9; white-space: pre-line;">${message}</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: white; opacity: 0.7;">×</button>
            </div>
        `;

        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }


    /**
     * Display modal dengan informasi jadwal
     */
    function showInfoModal(data) {
        const modal = document.getElementById('info-modal');
        const modalContent = document.getElementById('modal-content');

        // Format data sesuai struktur yang ada
        const tanggal = new Date().toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });

        modalContent.innerHTML = `
            <div class="overflow-hidden border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-600 w-1/3">Tanggal</td>
                            <td class="px-4 py-3 text-gray-800 font-semibold">${tanggal}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600">Nama</td>
                            <td class="px-4 py-3 text-gray-800">{{ auth()->user()->name ?? 'N/A' }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-600">NIM</td>
                            <td class="px-4 py-3 text-gray-800">{{ auth()->user()->nim ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600">Mata Kuliah</td>
                            <td class="px-4 py-3 text-gray-800">${data.subject_name || 'N/A'}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-600">Kelas</td>
                            <td class="px-4 py-3 text-gray-800">${data.class_name || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600">Ruangan</td>    
                            <td class="px-4 py-3 text-gray-800">${data.room_name || 'N/A'} (${data.location || ''})</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-600">Hari</td>
                            <td class="px-4 py-3 text-gray-800">${data.day || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-600">Waktu</td>
                            <td class="px-4 py-3 text-gray-800">${data.start_time || ''} - ${data.end_time || ''}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Konfirmasi:</strong> Dengan klik OK, Anda mengkonfirmasi akan menggunakan ruangan ini sesuai jadwal.
                </p>
            </div>
        `;
        
        modal.classList.remove('hidden');
    }

    /**
     * Step 2: Confirm Entry ke Ruangan
     * Endpoint: POST /api/lab/confirm-entry
     */
    function handleOkClick() {
        const confirmBtn = document.getElementById('confirm-btn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = 'Memproses...';
        
        showLoading();

        fetch('/api/lab/confirm-entry', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            
            if (data.success) {
                console.log('Entry confirmed:', data);
                
                // Close modal
                document.getElementById('info-modal').classList.add('hidden');
                
                // Show success message
                showAlert('Berhasil! Ruangan sekarang aktif dan sedang Anda gunakan.', 'success');
                
                // ✅ Redirect dengan parameter refresh
                setTimeout(() => {
                    window.location.href = "{{ route('dashboard') }}?confirmed=1&room_id=" + data.room_id;
                }, 1500);
            } else {
                hideLoading();
                showAlert(data.message || 'Gagal konfirmasi entry', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'OK';
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Confirm entry error:', error);
            
            const errorMessage = error.message || 'Terjadi kesalahan saat konfirmasi';
            showAlert(errorMessage, 'error');
            
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = 'OK';
        });
    }


    // ✅ FUNCTION BARU: Update dashboard real-time setelah confirm
        function updateRoomStatusInDashboard(roomId) {
            // Fetch ulang data jadwal untuk room tersebut
            fetch(`/api/room/${roomId}/schedule?date=${currentWeekStart}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.schedules && data.schedules.length > 0) {
                    // Trigger update dashboard jika halaman dashboard terbuka
                    if (typeof displayScheduleContent === 'function') {
                        displayScheduleContent(data);
                    }
                }
            })
            .catch(error => {
                console.error('Error updating dashboard:', error);
            });
        }


    /**
     * Cancel confirmation
     */
    function handleCancelClick() {
        // Close modal
        document.getElementById('info-modal').classList.add('hidden');
        
        // Reset state
        isProcessing = false;
        pendingRoomData = null;
        
        showStatus('Konfirmasi dibatalkan', false);
    }

    // ============= LIFECYCLE HOOKS =============

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        stopScanner();
    });

    // Optional: Auto-start scanner on page load
    // window.addEventListener('DOMContentLoaded', () => {
    //     startScanner();
    // });
    // ===================================
    // INJECT CSS ANIMATIONS
    // ===================================
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .custom-toast {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    `;
    document.head.appendChild(style);

</script>
@endpush