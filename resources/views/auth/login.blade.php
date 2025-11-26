@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-6xl flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-8">
        <div class="text-center lg:text-left">
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold" style="color: #0E2C48;">WELCOME</h2>
            <h3 class="text-2xl md:text-3xl lg:text-4xl font-semibold" style="color: #0E2C48;">laboratory assistant</h3>
        </div>

         <div class="w-full max-w-sm rounded-3xl p-6 lg:p-8 shadow-2xl" style="background-image: url('{{ asset('images/bg_rounded.png') }}'); background-size: cover; background-position: center;">
            <h2 class="text-2xl lg:text-3xl font-bold text-white text-center mb-6 lg:mb-8">Log In</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-5 lg:space-y-6">
                @csrf
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h16v12H4V6zm3 2a2 2 0 100 4 2 2 0 000-4zm0 6a4 4 0 00-4 4h8a4 4 0 00-4-4zm6-4h4v1h-4v-1zm0 2h4v1h-4v-1z"/>
                        </svg>
                    </div>
                    <input type="text" name="nim" id="nim" placeholder="NIM" value="{{ old('nim') }}"
                        class="w-full pl-12 pr-4 py-3 lg:py-4 rounded-xl text-white placeholder-white/70 focus:outline-none transition-all duration-200"
                        style="background: rgba(150, 120, 110, 0.6); border: 2px solid #99391B;" required>
                    @error('nim')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-white/70" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" placeholder="Kata Sandi"
                        class="w-full pl-12 pr-12 py-3 lg:py-4 rounded-xl text-white placeholder-white/70 focus:outline-none transition-all duration-200"
                        style="background: rgba(150, 120, 110, 0.6); border: 2px solid #99391B;" required>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <button type="button" onclick="togglePassword()" class="text-white/70 hover:text-white focus:outline-none transition-colors duration-200">
                            <svg id="eye-open" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-right">
                    <button type="button" onclick="openModalForgotPassword()" class="text-sm text-white/80 hover:text-white hover:underline transition">
                        Lupa Kata Sandi?
                    </button>
                </div>
                <button type="submit" 
                    class="w-full py-3 lg:py-4 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-lg"
                    style="background: linear-gradient(135deg, #0E2C48 0%, #99391B 100%);">
                        Log In
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('modals')
{{-- Modal 1: Input Email untuk Lupa Password --}}
<div id="forgot-password-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Lupa Kata Sandi</h2>
            <button onclick="closeModalForgotPassword()" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
            <p class="text-gray-600 mb-4">Masukkan Alamat E-mail agar kami dapat mengirimkan kode untuk verifikasi</p>
            <div class="relative">
                <input type="email" id="forgot-email" placeholder="Masukkan Alamat E-mail" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p id="forgot-email-error" class="mt-2 text-sm text-red-500 hidden"></p>
            </div>
            <button onclick="sendOTP()" id="btn-send-otp"
                class="w-full mt-6 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90 transition">
                Lanjut
            </button>
        </div>
    </div>
</div>

{{-- Modal 2: Input Kode OTP --}}
<div id="verification-code-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Verifikasi Kode OTP</h2>
            <button onclick="closeModalOTP()" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
            <p class="text-gray-600 mb-6">Masukkan kode yang dikirimkan ke Alamat E-mail</p>
            
            {{-- Countdown Timer --}}
            <div class="text-center mb-4">
                <p class="text-sm text-gray-600">Kode berlaku dalam:</p>
                <div class="text-3xl font-bold text-blue-900" id="countdown-timer">10:00</div>
                <p id="timer-expired-msg" class="text-sm text-red-500 hidden mt-2">⏱️ Kode OTP telah kadaluarsa</p>
            </div>

            {{-- OTP Input --}}
            <div class="flex justify-center gap-2 mb-6">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <input type="text" maxlength="1" class="otp-input w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <p id="otp-error" class="text-sm text-red-500 mb-4 hidden"></p>

            {{-- Buttons --}}
            <div class="space-y-3">
                <button onclick="verifyOTP()" id="btn-verify-otp"
                    class="w-full py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90 transition">
                    Verifikasi
                </button>
                <button onclick="resendOTP()" id="btn-resend-otp"
                    class="w-full py-3 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition cursor-not-allowed"
                    disabled>
                    Kirim Ulang OTP (<span id="resend-countdown">60</span>s)
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 3: Input Password Baru --}}
<div id="new-password-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Buat Kata Sandi Baru</h2>
            <button onclick="closeModalNewPassword()" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
            <p class="text-gray-600 mb-4 text-sm">Kata sandi akan dipakai untuk masuk ke ITLG Lab Management System</p>
            <div class="space-y-4">
                {{-- Password Baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
                    <input type="password" id="new-password" placeholder="Masukkan kata sandi baru" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    
                    {{-- Password Strength Indicators --}}
                    <div class="mt-3 space-y-1">
                        <div class="flex items-center text-xs">
                            <span id="indicator-length" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                            <span id="text-length" class="text-gray-600">Minimal 6 karakter</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span id="indicator-letter" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                            <span id="text-letter" class="text-gray-600">Mengandung huruf</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span id="indicator-number" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                            <span id="text-number" class="text-gray-600">Mengandung angka</span>
                        </div>
                        <div class="flex items-center text-xs">
                            <span id="indicator-special" class="w-3 h-3 rounded-full bg-gray-300 mr-2"></span>
                            <span id="text-special" class="text-gray-600">Mengandung karakter khusus (!$@%)</span>
                        </div>
                    </div>
                    <p id="error-new-password" class="text-red-500 text-xs mt-2 hidden"></p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm-password" placeholder="Tulis ulang kata sandi baru" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <p id="error-confirm-password" class="text-red-500 text-xs mt-2 hidden"></p>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-6">
                <button type="button" onclick="closeModalNewPassword()" 
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="button" onclick="submitNewPassword()" id="btn-submit-password"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg cursor-not-allowed transition"
                    disabled>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Success --}}
<div id="modal-success-reset" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Berhasil!</h3>
        <p class="text-gray-600 mb-6" id="success-message">Kata sandi berhasil direset. Silakan login dengan password baru.</p>
        <button onclick="redirectToLogin()" 
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition w-full">
            Kembali ke Login
        </button>
    </div>
</div>

{{-- Modal Error --}}
<div id="modal-error-reset" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 text-center">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Gagal!</h3>
        <p class="text-gray-600 mb-6" id="error-message">Terjadi kesalahan. Silakan coba lagi.</p>
        <button onclick="closeModalError()" 
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-700 to-orange-500 rounded-lg hover:opacity-90 transition w-full">
            Tutup
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ==========================================
    // GLOBAL VARIABLES
    // ==========================================
    let forgotEmail = '';
    let otpToken = '';
    let otpTimerInterval = null;
    let resendTimerInterval = null;
    let otpExpiresIn = 0;
    let resendCountdown = 0;
    let isPasswordValid = false;
    let isConfirmValid = false;

    // ==========================================
    // PASSWORD TOGGLE
    // ==========================================
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // ==========================================
    // MODAL FUNCTIONS - FORGOT PASSWORD FLOW
    // ==========================================
    function openModalForgotPassword() {
        document.getElementById('forgot-password-modal').classList.remove('hidden');
        document.getElementById('forgot-email').focus();
        clearForgotPasswordForm();
    }

    function closeModalForgotPassword() {
        document.getElementById('forgot-password-modal').classList.add('hidden');
        clearForgotPasswordForm();
    }

    function clearForgotPasswordForm() {
        document.getElementById('forgot-email').value = '';
        document.getElementById('forgot-email-error').classList.add('hidden');
        document.getElementById('forgot-email-error').textContent = '';
    }

    function closeModalOTP() {
        document.getElementById('verification-code-modal').classList.add('hidden');
        clearOTPTimer();
        clearResendTimer();
    }

    function closeModalNewPassword() {
        document.getElementById('new-password-modal').classList.add('hidden');
        clearPasswordForm();
    }

    // ==========================================
    // STEP 1: SEND OTP
    // ==========================================
    async function sendOTP() {
        const email = document.getElementById('forgot-email').value.trim();
        const errorEl = document.getElementById('forgot-email-error');
        const btn = document.getElementById('btn-send-otp');

        // Validate email
        if (!email) {
            showError(errorEl, 'Email harus diisi');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError(errorEl, 'Email tidak valid');
            return;
        }

        // Disable button
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        try {
            const response = await fetch('{{ route("forgot-password.send-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                forgotEmail = email;
                hideError(errorEl);
                
                // Move to OTP verification modal
                closeModalForgotPassword();
                openModalOTP();
                
                // Start countdown - PERBAIKAN: Langsung pakai integer dari backend
                otpExpiresIn = result.data.otp_expires_in;
                startOTPTimer();
                startResendTimer();
            } else {
                if (result.field_error) {
                    showError(errorEl, result.message);
                } else {
                    showErrorModal(result.message);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorModal('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Lanjut';
        }
    }

    function openModalOTP() {
        document.getElementById('verification-code-modal').classList.remove('hidden');
        // Focus first OTP input
        setTimeout(() => {
            document.querySelector('.otp-input').focus();
        }, 100);
    }

    // ==========================================
    // OTP INPUT HANDLING
    // ==========================================
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('otp-input')) {
            const input = e.target;
            
            // Only allow digits
            input.value = input.value.replace(/[^0-9]/g, '');

            // Move to next input
            if (input.value.length === 1) {
                const next = input.nextElementSibling;
                if (next && next.classList.contains('otp-input')) {
                    next.focus();
                }
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('otp-input')) {
            const input = e.target;
            
            // Handle backspace
            if (e.key === 'Backspace' && input.value === '') {
                const prev = input.previousElementSibling;
                if (prev && prev.classList.contains('otp-input')) {
                    prev.focus();
                }
            }
        }
    });

    // ==========================================
    // STEP 2: VERIFY OTP
    // ==========================================
    async function verifyOTP() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        const errorEl = document.getElementById('otp-error');
        const btn = document.getElementById('btn-verify-otp');

        if (otp.length !== 6) {
            showError(errorEl, 'Kode OTP harus 6 digit');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Memverifikasi...';

        try {
            const response = await fetch('{{ route("forgot-password.verify-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    email: forgotEmail,
                    otp: otp
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                otpToken = result.data.token;
                hideError(errorEl);
                clearOTPTimer();
                clearResendTimer();
                
                // Move to new password modal
                closeModalOTP();
                openModalNewPassword();
            } else {
                if (result.is_expired) {
                    document.getElementById('timer-expired-msg').classList.remove('hidden');
                    document.getElementById('countdown-timer').classList.add('hidden');
                    enableResendButton();
                }
                showError(errorEl, result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorModal('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Verifikasi';
        }
    }

    // ==========================================
    // RESEND OTP
    // ==========================================
    async function resendOTP() {
        const btn = document.getElementById('btn-resend-otp');
        
        if (btn.disabled) return;

        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        try {
            const response = await fetch('{{ route("forgot-password.resend-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email: forgotEmail })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Hide error messages
                document.getElementById('otp-error').classList.add('hidden');
                document.getElementById('timer-expired-msg').classList.add('hidden');
                document.getElementById('countdown-timer').classList.remove('hidden');
                
                // Clear OTP inputs
                document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                
                // PERBAIKAN: Reset countdown dengan waktu baru dari server
                otpExpiresIn = result.data.otp_expires_in;
                startOTPTimer();
                startResendTimer();
                
                document.querySelector('.otp-input').focus();
                
                // PERBAIKAN: Reset button text setelah berhasil
                btn.textContent = 'Kirim Ulang OTP';
            } else {
                showErrorModal(result.message);
                btn.textContent = 'Kirim Ulang OTP';
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorModal('Terjadi kesalahan. Silakan coba lagi.');
            btn.textContent = 'Kirim Ulang OTP';
        }
    }

    // ==========================================
    // TIMER FUNCTIONS
    // ==========================================
    function startOTPTimer() {
        clearOTPTimer();
        
        otpTimerInterval = setInterval(() => {
            otpExpiresIn--;
            
            // PERBAIKAN: Format waktu tanpa desimal
            const minutes = Math.floor(otpExpiresIn / 60);
            const seconds = otpExpiresIn % 60;
            const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            document.getElementById('countdown-timer').textContent = timeString;
            
            if (otpExpiresIn <= 0) {
                clearOTPTimer();
                document.getElementById('timer-expired-msg').classList.remove('hidden');
                document.getElementById('countdown-timer').classList.add('hidden');
                document.getElementById('btn-verify-otp').disabled = true;
                enableResendButton();
        }
    }, 1000);
}

function clearOTPTimer() {
    if (otpTimerInterval) {
        clearInterval(otpTimerInterval);
        otpTimerInterval = null;
    }
}

function startResendTimer() {
    clearResendTimer();
    
    resendCountdown = 60;
    const btn = document.getElementById('btn-resend-otp');
    const countdownSpan = document.getElementById('resend-countdown');
    
    // PERBAIKAN: Disable button dengan styling abu-abu
    btn.disabled = true;
    btn.className = 'w-full py-3 bg-gray-300 text-gray-700 font-semibold rounded-lg cursor-not-allowed transition';
    
    resendTimerInterval = setInterval(() => {
        resendCountdown--;
        countdownSpan.textContent = resendCountdown;
        
        if (resendCountdown <= 0) {
            clearResendTimer();
            enableResendButton();
        }
    }, 1000);
}

function clearResendTimer() {
    if (resendTimerInterval) {
        clearInterval(resendTimerInterval);
        resendTimerInterval = null;
    }
}

// PERBAIKAN: Function untuk enable button resend dengan styling gradasi
function enableResendButton() {
    const btn = document.getElementById('btn-resend-otp');
    btn.disabled = false;
    btn.className = 'w-full py-3 bg-gradient-to-r from-blue-700 to-teal-600 text-white font-semibold rounded-lg hover:opacity-90 transition';
    btn.innerHTML = 'Kirim Ulang OTP';
}

// ==========================================
// PASSWORD VALIDATION
// ==========================================
function openModalNewPassword() {
    document.getElementById('new-password-modal').classList.remove('hidden');
    resetPasswordValidation();
    setTimeout(() => {
        document.getElementById('new-password').focus();
    }, 100);
}

function clearPasswordForm() {
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-password').value = '';
    resetPasswordValidation();
}

function resetPasswordValidation() {
    isPasswordValid = false;
    isConfirmValid = false;
    
    // Reset indicators
    const indicators = ['length', 'letter', 'number', 'special'];
    indicators.forEach(id => {
        document.getElementById(`indicator-${id}`).className = 'w-3 h-3 rounded-full bg-gray-300 mr-2';
        document.getElementById(`text-${id}`).className = 'text-gray-600';
    });
    
    // Hide errors
    document.getElementById('error-new-password').classList.add('hidden');
    document.getElementById('error-confirm-password').classList.add('hidden');
    
    // Disable submit button
    updateSubmitButton();
}

// Real-time validation untuk password baru
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            validateNewPassword(this.value);
            validateConfirmPassword();
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            validateConfirmPassword();
        });
    }
});

function validateNewPassword(password) {
    const errorEl = document.getElementById('error-new-password');
    
    // Check length (min 6 characters)
    const isLengthValid = password.length >= 6;
    updateIndicator('length', isLengthValid);
    
    // Check letter
    const hasLetter = /[a-zA-Z]/.test(password);
    updateIndicator('letter', hasLetter);
    
    // Check number
    const hasNumber = /[0-9]/.test(password);
    updateIndicator('number', hasNumber);
    
    // Check special character (!$@%)
    const hasSpecial = /[!$@%]/.test(password);
    updateIndicator('special', hasSpecial);
    
    // Overall validation
    isPasswordValid = isLengthValid && hasLetter && hasNumber && hasSpecial;
    
    if (password.length === 0) {
        hideError(errorEl);
    } else if (!isPasswordValid) {
        showError(errorEl, 'Password tidak memenuhi semua persyaratan');
    } else {
        hideError(errorEl);
    }
    
    updateSubmitButton();
}

function validateConfirmPassword() {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const errorEl = document.getElementById('error-confirm-password');
    
    if (confirmPassword.length === 0) {
        hideError(errorEl);
        isConfirmValid = false;
    } else if (confirmPassword !== newPassword) {
        showError(errorEl, 'Konfirmasi password tidak cocok');
        isConfirmValid = false;
    } else {
        hideError(errorEl);
        isConfirmValid = true;
    }
    
    updateSubmitButton();
}

function updateIndicator(type, isValid) {
    const indicator = document.getElementById(`indicator-${type}`);
    const text = document.getElementById(`text-${type}`);
    
    if (isValid) {
        indicator.className = 'w-3 h-3 rounded-full bg-green-500 mr-2';
        text.className = 'text-green-600';
    } else {
        indicator.className = 'w-3 h-3 rounded-full bg-red-500 mr-2';
        text.className = 'text-red-600';
    }
}

function updateSubmitButton() {
    const btn = document.getElementById('btn-submit-password');
    
    if (isPasswordValid && isConfirmValid) {
        btn.disabled = false;
        btn.className = 'px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-900 to-red-700 rounded-lg hover:opacity-90 transition';
    } else {
        btn.disabled = true;
        btn.className = 'px-4 py-2 text-sm font-semibold text-white bg-gray-400 rounded-lg cursor-not-allowed transition';
    }
}

// ==========================================
// STEP 3: SUBMIT NEW PASSWORD
// ==========================================
async function submitNewPassword() {
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const btn = document.getElementById('btn-submit-password');
    
    // Final validation
    if (!isPasswordValid || !isConfirmValid) {
        showErrorModal('Harap lengkapi semua field dengan benar.');
        return;
    }
    
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';
    
    try {
        const response = await fetch('{{ route("forgot-password.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: forgotEmail,
                token: otpToken,
                password: newPassword,
                password_confirmation: confirmPassword
            })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeModalNewPassword();
            showSuccessModal(result.message);
        } else {
            if (result.errors) {
                let errorMessage = '';
                for (let key in result.errors) {
                    errorMessage += result.errors[key][0] + '\n';
                }
                showErrorModal(errorMessage || result.message);
            } else {
                showErrorModal(result.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorModal('Terjadi kesalahan. Silakan coba lagi.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Simpan';
        updateSubmitButton();
    }
}

// ==========================================
// HELPER FUNCTIONS
// ==========================================
function showError(element, message) {
    element.textContent = message;
    element.classList.remove('hidden');
}

function hideError(element) {
    element.classList.add('hidden');
    element.textContent = '';
}

function showSuccessModal(message) {
    document.getElementById('success-message').textContent = message;
    document.getElementById('modal-success-reset').classList.remove('hidden');
    document.getElementById('modal-success-reset').classList.add('flex');
}

function showErrorModal(message) {
    document.getElementById('error-message').textContent = message;
    document.getElementById('modal-error-reset').classList.remove('hidden');
    document.getElementById('modal-error-reset').classList.add('flex');
}

function closeModalError() {
    document.getElementById('modal-error-reset').classList.add('hidden');
    document.getElementById('modal-error-reset').classList.remove('flex');
}

function redirectToLogin() {
    // Clear all modals
    closeModalNewPassword();
    document.getElementById('modal-success-reset').classList.add('hidden');
    document.getElementById('modal-success-reset').classList.remove('flex');
    
    // Reset all variables
    forgotEmail = '';
    otpToken = '';
    clearOTPTimer();
    clearResendTimer();
    
    // Reload page to go back to login
    window.location.reload();
}
</script>
@endsection