<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ITLG Lab Management System')</title>
    
    <!-- CSS/ICON WAJIB -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
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

        /* Main content area styling */
        .main-content {
            margin-top: 64px; /* Height navbar */
            margin-left: 0; /* Default no sidebar */
            min-height: calc(100vh - 64px);
            transition: margin-left 0.3s ease;
        }

        /* Desktop sidebar */
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 256px; /* Width sidebar desktop */
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

        /* Notification styles */
        .notification-indicator {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* FIX DROPDOWN DOUBLE ARROW */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Remove default arrow in IE */
        select::-ms-expand {
            display: none;
        }

        /* FIX MODAL POSITION */
        .modal-fixed {
            align-items: flex-start;
            padding-top: 5rem;
        }

        @media (max-width: 768px) {
            .modal-fixed {
                padding-top: 2rem;
                align-items: center;
            }
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    @php 
        $user = auth()->user();
        // Cek apakah user punya role BPH - SAMA PERSIS seperti di UserController
        $isAdmin = $user && $user->roles->contains('status', 'bph');
    @endphp
    
    {{-- DEBUG OUTPUT - UNCOMMENT UNTUK CEK --}}
    {{-- 
    <div style="position: fixed; top: 100px; right: 10px; background: red; color: white; padding: 10px; z-index: 99999; font-weight: bold;">
        User: {{ $user->name ?? 'N/A' }}<br>
        Email: {{ $user->email ?? 'N/A' }}<br>
        Roles: {{ $user->roles->pluck('status')->join(', ') }}<br>
        Is BPH: {{ $isAdmin ? 'YES' : 'NO' }}<br>
        Loading: {{ $isAdmin ? 'pageadmin.blade' : 'app.blade' }}
    </div>
    --}}
    
    {{-- Pilih layout berdasarkan role --}}
    @if ($isAdmin)
        {{-- SIDEBAR UNTUK BPH/ADMIN --}}
        @include('layouts.pageadmin')
    @else
        {{-- SIDEBAR UNTUK ASLAB BIASA --}}
        @include('layouts.app')
    @endif

    <!-- MAIN KONTEN -->
    <main class="main-content bg-gray-100 p-4 xl:p-8">
        @yield('content')
    </main>

    {{-- Script untuk sidebar mobile dan notifications --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Sidebar Functionality
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
        const openMobileSidebarBtn = document.getElementById('openMobileSidebar');
        const closeMobileSidebarBtn = document.getElementById('closeMobileSidebar');
        
        function openMobileSidebar() {
            if (mobileSidebar && mobileSidebarOverlay) {
                mobileSidebar.classList.add('open');
                mobileSidebarOverlay.style.display = 'block';
                setTimeout(() => {
                    mobileSidebarOverlay.classList.add('active');
                }, 10);
                document.body.classList.add('sidebar-open');
            }
        }
        
        function closeMobileSidebar() {
            if (mobileSidebar && mobileSidebarOverlay) {
                mobileSidebar.classList.remove('open');
                mobileSidebarOverlay.classList.remove('active');
                setTimeout(() => {
                    mobileSidebarOverlay.style.display = 'none';
                }, 300);
                document.body.classList.remove('sidebar-open');
            }
        }
        
        // Event listeners untuk mobile sidebar
        if (openMobileSidebarBtn) {
            openMobileSidebarBtn.addEventListener('click', openMobileSidebar);
        }
        
        if (closeMobileSidebarBtn) {
            closeMobileSidebarBtn.addEventListener('click', closeMobileSidebar);
        }
        
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebar);
        }
        
        // Close sidebar dengan Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileSidebar && mobileSidebar.classList.contains('open')) {
                closeMobileSidebar();
            }
        });

        // Notification Management
        function initializeNotifications() {
            if (!localStorage.getItem('notifications-initialized')) {
                const initialNotifications = [
                    { 
                        id: 1, 
                        title: "AMBIL JADWAL", 
                        message: "Kelas Praktikum PBOL akan dimulai dalam 30 menit!", 
                        time: "1 menit", 
                        type: "schedule",
                        createdAt: new Date().toISOString(),
                        read: false
                    }
                ];
                
                localStorage.setItem('notifications', JSON.stringify(initialNotifications));
                localStorage.setItem('notifications-initialized', 'true');
            }
        }

        function updateHeaderBadge() {
            const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
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
        }

        // Initialize
        initializeNotifications();
        updateHeaderBadge();
    });
    </script>

    {{-- Script tambahan dari halaman anak --}}
    @stack('scripts')
</body>
</html>