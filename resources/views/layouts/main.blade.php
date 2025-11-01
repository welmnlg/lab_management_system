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
    <!-- Tambahkan custom CSS JS lain DI SINI -->
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen relative">
    @php $user = auth()->user(); @endphp
    {{-- Sidebar: hanya include partial (tanpa asides, tanpa w-64, tanpa flex lg) --}}
    @if ($user && $user->roles->contains('status', 'bph'))
        @include('layouts.pageadmin')
    @else
        @include('layouts.app')
    @endif
    
    
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Test jQuery
        console.log('jQuery version:', $.fn.jquery);
        console.log('jQuery loaded:', typeof $ !== 'undefined');
    </script>

@stack('scripts')
</body>

</html>
