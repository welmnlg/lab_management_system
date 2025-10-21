<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ITLG Lab Management System')</title>

    <!-- Tambahkan Tailwind CSS dan Bootstrap Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        .toggle-btn {
            transition: all 0.2s ease-in-out;
        }
        .toggle-btn.active {
            background-color: #10b981;
        }
        .toggle-btn.active:hover {
            background-color: #059669;
        }
        
        /* Mobile sidebar overlay */
        .mobile-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .mobile-sidebar-overlay.active {
            opacity: 1;
        }
        
        /* Mobile sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 85%;
            max-width: 320px;
            height: 100%;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        
        /* Prevent body scroll when sidebar is open */
        body.sidebar-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
        
        /* Smooth transitions for sidebar content */
        .mobile-sidebar-content {
            opacity: 0;
            transform: translateX(-20px);
            transition: all 0.3s ease-in-out 0.1s;
        }
        
        .mobile-sidebar.open .mobile-sidebar-content {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Swipe to close support */
        .mobile-sidebar.swipe-close {
            transition: transform 0.2s ease-out;
        }
        
        /* Better touch targets for mobile */
        .mobile-nav-item {
            padding: 14px 16px;
            min-height: 56px;
        }
        
        /* Active state improvements */
        .mobile-nav-active {
            background: linear-gradient(135deg, #1e3a8a 0%, #991b1b 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Notification styles */
        .notification-item {
            transition: all 0.2s ease-in-out;
        }

        .notification-indicator {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .bg-green-50 {
            border-left-color: #10b981;
        }

        /* Untuk line clamp di browser yang tidak mendukung */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar untuk sidebar desktop */
        .sidebar-desktop::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-desktop::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar-desktop::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .sidebar-desktop::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Smooth scrolling untuk sidebar */
        .sidebar-desktop {
            scroll-behavior: smooth;
        }

        /* Main content area styling */
        .main-content {
            margin-top: 64px; /* Height navbar */
            margin-left: 256px; /* Width sidebar desktop */
            min-height: calc(100vh - 64px);
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Ensure navbar stays on top */
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        /* Desktop sidebar fixed positioning */
        .sidebar-desktop-fixed {
            position: fixed;
            top: 64px; /* Height navbar */
            left: 0;
            bottom: 0;
            width: 256px;
            z-index: 900;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen relative">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="mobile-sidebar-overlay"></div>
    
    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="mobile-sidebar lg:hidden">
        <div class="h-full flex flex-col mobile-sidebar-content">
            <!-- Mobile Sidebar Header -->
            <div class="bg-gradient-to-r from-blue-900 to-red-700 px-5 py-5 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 mr-3 relative">
                        <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">ITLG LAB</h1>
                        <p class="text-xs text-blue-200 opacity-90">Management System</p>
                    </div>
                </div>
                <button id="closeMobileSidebar" class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Mobile Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('dashboard') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">BERANDA</span>
                </a>

                <!-- Menu Kelola Pengguna -->
                <a href="{{ route('admin') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('admin') || request()->routeIs('tambah-pengguna') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">KELOLA PENGGUNA</span>
                </a>    

                <a href="{{ route('scanqr') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('scanqr') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">SCAN QR</span>
                </a>

                <a href="{{ route('logbook') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('logbook') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">LOGBOOK</span>
                </a>

                <a href="{{ route('ambil-jadwal') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('ambil-jadwal') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">AMBIL JADWAL</span>
                </a>

                <a href="{{ route('kelola-matkul') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('kelola-matkul') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">KELOLA MATA KULIAH</span>
                </a>

                <a href="{{ route('profil') }}" 
                   class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                          {{ request()->routeIs('profil') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap text-base">PROFIL</span>
                </a>
            </nav>
            
            <!-- Mobile User Info -->
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('profil') }}" 
                   class="flex items-center space-x-3 bg-gray-50 rounded-xl p-3 hover:bg-gray-100 active:bg-gray-200 transition-colors duration-200 cursor-pointer">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-600 to-red-600 flex items-center justify-center shadow-sm">
                        <span class="text-white font-semibold text-base">AH</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">Aulia Halimatusyaddiah</p>
                        <p class="text-xs text-gray-500 truncate">auliahalim217@gmail.com</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar (Fixed untuk semua device) -->
    <div class="bg-white shadow-sm border-b border-gray-200 px-4 lg:px-6 py-4 navbar-fixed">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button id="openMobileSidebar" class="lg:hidden mr-3 p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors duration-200">
                    <i class="bi bi-list text-2xl text-gray-700"></i>
                </button>
                
                <div class="w-8 h-8 md:w-10 md:h-10 mr-2 md:mr-3 relative">
                    <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-sm md:text-xl font-bold text-blue-900">ITLG LAB MANAGEMENT SYSTEM</h1>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('notifikasi') }}" 
                   class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-200 relative
                          {{ request()->routeIs('notifikasi') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white border-transparent' : 'text-gray-600' }}"
                   id="notification-link">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <!-- Badge Counter -->
                    <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                </a>
                
                <!-- User Info dengan Link ke Profil -->
                <a href="{{ route('profil') }}" 
                   class="hidden lg:flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2 hover:bg-gray-100 transition-colors duration-200 cursor-pointer
                          {{ request()->routeIs('profil') ? 'bg-blue-50 border border-blue-200' : '' }}">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">AH</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Aulia Halimatusyaddiah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Desktop Sidebar (Fixed Position) - DIHAPUS LOGO DAN TULISAN -->
    <div class="hidden lg:block sidebar-desktop-fixed sidebar-desktop">
        <div class="bg-white shadow-sm border-r border-gray-200 h-full overflow-y-auto">
            <nav class="p-4 space-y-2">
                <!-- HAPUS BAGIAN INI: Logo/Header Sidebar Desktop -->
                <!-- 
                <div class="mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-10 h-10 mr-3 relative">
                            <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-blue-900">ITLG LAB</h1>
                            <p class="text-xs text-gray-500">Management System</p>
                        </div>
                    </div>
                </div>
                -->
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                          {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">BERANDA</span>
                </a>

                <!-- Menu Kelola Pengguna -->
                <a href="{{ route('admin') }}" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg
                        {{ request()->routeIs('admin') || request()->routeIs('tambah-pengguna') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">KELOLA PENGGUNA</span>
                </a>    

                <a href="{{ route('scanqr') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg
                          {{ request()->routeIs('scanqr') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">SCAN QR</span>
                </a>

                <a href="{{ route('logbook') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg
                          {{ request()->routeIs('logbook') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">LOGBOOK</span>
                </a>

                <a href="{{ route('ambil-jadwal') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg
                          {{ request()->routeIs('ambil-jadwal') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">AMBIL JADWAL</span>
                </a>

                <a href="{{ route('kelola-matkul') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg
                          {{ request()->routeIs('kelola-matkul') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">KELOLA MATA KULIAH</span>
                </a>

                <a href="{{ route('profil') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg
                          {{ request()->routeIs('profil') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">PROFIL</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="p-4 md:p-6">
            @yield('content')
        </div>
    </div>

    <script>
    // Enhanced Mobile Sidebar Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
        const openMobileSidebarBtn = document.getElementById('openMobileSidebar');
        const closeMobileSidebarBtn = document.getElementById('closeMobileSidebar');
        const body = document.body;
        
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        // Open mobile sidebar
        function openMobileSidebar() {
            mobileSidebar.classList.add('open');
            mobileSidebarOverlay.style.display = 'block';
            setTimeout(() => {
                mobileSidebarOverlay.classList.add('active');
            }, 10);
            body.classList.add('sidebar-open');
            
            // Add swipe to close event listeners
            document.addEventListener('touchstart', handleTouchStart, { passive: false });
            document.addEventListener('touchmove', handleTouchMove, { passive: false });
            document.addEventListener('touchend', handleTouchEnd);
        }
        
        // Close mobile sidebar
        function closeMobileSidebar() {
            mobileSidebar.classList.remove('open');
            mobileSidebarOverlay.classList.remove('active');
            setTimeout(() => {
                mobileSidebarOverlay.style.display = 'none';
            }, 300);
            body.classList.remove('sidebar-open');
            
            // Remove swipe to close event listeners
            document.removeEventListener('touchstart', handleTouchStart);
            document.removeEventListener('touchmove', handleTouchMove);
            document.removeEventListener('touchend', handleTouchEnd);
        }
        
        // Touch events for swipe to close
        function handleTouchStart(e) {
            if (!mobileSidebar.classList.contains('open')) return;
            
            startX = e.touches[0].clientX;
            currentX = startX;
            isDragging = true;
            mobileSidebar.classList.add('swipe-close');
        }
        
        function handleTouchMove(e) {
            if (!isDragging) return;
            
            currentX = e.touches[0].clientX;
            const diff = startX - currentX;
            
            if (diff > 0) {
                e.preventDefault();
                const translateX = Math.max(-100, -diff);
                mobileSidebar.style.transform = `translateX(${translateX}px)`;
            }
        }
        
        function handleTouchEnd() {
            if (!isDragging) return;
            
            isDragging = false;
            mobileSidebar.classList.remove('swipe-close');
            
            const diff = startX - currentX;
            const threshold = mobileSidebar.offsetWidth * 0.3;
            
            if (diff > threshold) {
                closeMobileSidebar();
            } else {
                mobileSidebar.style.transform = 'translateX(0)';
            }
        }
        
        // Event listeners
        if (openMobileSidebarBtn) {
            openMobileSidebarBtn.addEventListener('click', openMobileSidebar);
        }
        
        if (closeMobileSidebarBtn) {
            closeMobileSidebarBtn.addEventListener('click', closeMobileSidebar);
        }
        
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
        }
        
        // Close sidebar when clicking on a nav link (mobile only)
        const mobileNavLinks = mobileSidebar.querySelectorAll('a');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(closeMobileSidebar, 300);
            });
        });
        
        // Close sidebar with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileSidebar.classList.contains('open')) {
                closeMobileSidebar();
            }
        });
        
        // Prevent body scroll when sidebar is open
        document.addEventListener('touchmove', function(e) {
            if (mobileSidebar.classList.contains('open')) {
                e.preventDefault();
            }
        }, { passive: false });
    });
    
    // Notification Management System
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize notifications if not exists
        function initializeNotifications() {
            if (!localStorage.getItem('notifications-initialized')) {
                // Create initial notifications
                const initialNotifications = [
                    { 
                        id: 1, 
                        title: "AMBIL JADWAL", 
                        message: "Kelas Praktikum PBOL akan dimulai dalam 30 menit!", 
                        time: "1 menit", 
                        type: "schedule",
                        createdAt: new Date().toISOString(),
                        read: false
                    },
                    { 
                        id: 2, 
                        title: "AMBIL JADWAL", 
                        message: "Kelas Praktikum PWEB akan dimulai dalam 1 jam!", 
                        time: "5 menit", 
                        type: "schedule",
                        createdAt: new Date().toISOString(),
                        read: false
                    },
                    { 
                        id: 3, 
                        title: "AMBIL JADWAL", 
                        message: "Kelas Praktikum PBO akan dimulai dalam 2 jam!", 
                        time: "10 menit", 
                        type: "schedule",
                        createdAt: new Date().toISOString(),
                        read: false
                    }
                ];
                
                localStorage.setItem('notifications', JSON.stringify(initialNotifications));
                localStorage.setItem('notifications-initialized', 'true');
            }
        }

        // Update header badge - hitung yang belum dibaca (read: false)
        function updateHeaderBadge() {
            const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
            
            // Count unread notifications (read: false)
            const unreadCount = notifications.filter(notif => !notif.read).length;
            
            const badge = document.getElementById('notification-badge');
            
            if (badge) {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
            
            localStorage.setItem('unread-notifications', unreadCount);
        }

        // Mark all notifications as read when leaving notification page
        function markAllAsRead() {
            const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
            const updatedNotifications = notifications.map(notif => ({
                ...notif,
                read: true
            }));
            
            localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
            updateHeaderBadge();
        }

        // Track page navigation
        let isOnNotificationPage = false;

        // Check if we're on notification page
        if (window.location.pathname.includes('notifikasi')) {
            isOnNotificationPage = true;
            console.log('User entered notification page');
        }

        // Listen for page leave (beforeunload)
        window.addEventListener('beforeunload', function() {
            if (isOnNotificationPage) {
                console.log('User leaving notification page - marking all as read');
                markAllAsRead();
            }
        });

        // Simulate new notifications (for testing)
        function simulateNewNotification() {
            const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
            const newId = notifications.length > 0 ? Math.max(...notifications.map(n => n.id)) + 1 : 1;
            
            const newNotification = {
                id: newId,
                title: "AMBIL JADWAL",
                message: `Kelas Praktikum MATA KULIAH ${newId} akan dimulai dalam 30 menit!`,
                time: "Baru saja",
                type: "schedule",
                createdAt: new Date().toISOString(),
                read: false
            };
            
            notifications.unshift(newNotification);
            localStorage.setItem('notifications', JSON.stringify(notifications));
            
            updateHeaderBadge();
            
            console.log('New notification received:', newNotification.message);
        }

        // Initialize
        initializeNotifications();
        updateHeaderBadge();

        // Simulate receiving new notifications every 30 seconds (for testing)
        // setInterval(simulateNewNotification, 30000);
        
        // Export function untuk diakses dari halaman lain
        window.updateHeaderBadge = updateHeaderBadge;
        window.simulateNewNotification = simulateNewNotification;
    });
    </script>

    {{-- Script tambahan dari halaman anak --}}
    @stack('scripts')
</body>
</html>