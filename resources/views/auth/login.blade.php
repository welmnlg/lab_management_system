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
                    <button type="button" onclick="openModal('forgot-password-modal')" class="text-sm text-white/80 hover:text-white hover:underline transition">
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
{{-- Modal 1: lupa password --}}
<div id="forgot-password-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Lupa Kata Sandi</h2>
            <button onclick="closeModal('forgot-password-modal')" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
             <p class="text-gray-600 mb-4">Masukkan Alamat E-mail agar kami dapat mengirimkan kode untuk verifikasi</p>
            <input type="email" placeholder="Masukkan Alamat E-mail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            <button onclick="openModal('verification-code-modal'); closeModal('forgot-password-modal')" class="w-full mt-6 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                Lanjut
            </button>
        </div>
    </div>
</div>
{{-- Modal 2: Kode verifikasi --}}
<div id="verification-code-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Lupa Kata Sandi</h2>
            <button onclick="closeModal('verification-code-modal')" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
            <p class="text-gray-600 mb-6">Masukkan kode yang dikirimkan ke Alamat E-mail</p>
            <div class="flex justify-center gap-2">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" class="w-12 h-12 text-center text-2xl border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                @endfor
            </div>
            <button onclick="openModal('new-password-modal'); closeModal('verification-code-modal')" class="w-full mt-6 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                Lanjut
            </button>
        </div>
    </div>
</div>
{{-- Modal 3: Sandi baru --}}
<div id="new-password-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md transform transition-all overflow-hidden">
        <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Buat Kata Sandi Baru</h2>
            <button onclick="closeModal('new-password-modal')" class="text-2xl font-bold text-white hover:text-gray-200">&times;</button>
        </div>
        <div class="p-8">
            <p class="text-gray-600 mb-4 text-sm">Kata sandi akan dipakai untuk masuk ke ITLG Lab Management System</p>
            <div class="space-y-4">
                <input type="password" placeholder="Kata sandi baru" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <div class="text-xs text-gray-500 space-y-1">
                    <p class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> 9 - 12 Karakter</p>
                    <p class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Angka</p>
                    <p class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Huruf besar dan kecil</p>
                    <p class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Karakter spesial</p>
                </div>
                <input type="password" placeholder="Konfirmasi kata sandi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button onclick="closeModal('new-password-modal')" class="w-full mt-6 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                Simpan
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection