<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ITLG Lab Management System - Login')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- link custom css eksternal kalau ada -->
    <!-- Tambahkan library lain jika perlu -->
</head>
<body class="min-h-screen relative" style="background-image: url('{{ asset('images/bg login.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    
    <!-- Bisa juga logo/header global disini, jika ingin semua halaman auth ada logo -->
    <div class="absolute top-4 left-4 md:top-6 md:left-6 z-10">
        <div class="flex items-center">
            <div class="w-8 h-8 md:w-12 md:h-12 mr-2 md:mr-3 relative">
                <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain rounded-lg">
            </div>
            <h1 class="text-sm md:text-2xl font-bold" style="color: #0E2C48;">ITLG Lab Management System</h1>
        </div>
    </div>

    <main>
        @yield('content')  <!-- tempat form login/register/forgot password -->
    </main>

    @yield('modals')     <!-- letakkan semua modal dari form login, forgot password, dll di section ini -->
    @yield('scripts')    <!-- letakkan semua script js tambahan di section ini -->

</body>
</html>