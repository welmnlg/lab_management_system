<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" viewport-fit=cover>
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
    <main class="main-content bg-gray-100 p-4 xl:p-8 pt-6 md:pt-8">
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

    // ========================================
    // NOTIFICATION BADGE MANAGEMENT
    // ========================================
    
    /**
     * Update notification badge di navbar
     */
    function updateNotificationBadge() {
        fetch('/api/notifications/unread-count', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notification-badge');
            
            if (badge && data.success) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error updating badge:', error));
    }
    
    // Initial badge update
    updateNotificationBadge();
    
    // Update badge setiap 10 detik
    setInterval(updateNotificationBadge, 10000);
    
    // Subscribe to real-time badge updates via Pusher
    if (window.Echo) {
        @auth
        const userId = {{ auth()->user()->user_id }};
        
        window.Echo.private(`notification.${userId}`)
            .listen('ScheduleReminderNotification', (event) => {
                console.log('🔔 New notification received in navbar');
                updateNotificationBadge();
                
                // Optional: Show toast notification
                showNotificationToast(event);
            })
            .error((error) => {
                console.error('❌ Echo error in navbar:', error);
            });
        @endauth
    }
    
    /**
     * Show toast notification (optional)
     */
    function showNotificationToast(event) {
        // Cek apakah user sedang di halaman notifikasi
        if (window.location.pathname === '/notifikasi') {
            return; // Jangan tampilkan toast di halaman notifikasi
        }
        
        const toastHTML = `
            <div class="fixed bottom-4 right-4 max-w-md z-50 animate-slide-in notification-toast">
                <div class="rounded-lg shadow-lg overflow-hidden bg-blue-600">
                    <div class="p-4 text-white">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="font-semibold">Pengingat Jadwal Mengajar</p>
                                <p class="text-sm mt-1">${event.course_name} (${event.class_name})</p>
                                <p class="text-xs mt-1 opacity-90">${event.time_slot} - ${event.room_name}</p>
                            </div>
                            <button onclick="this.closest('.notification-toast').remove()" class="ml-auto flex-shrink-0">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', toastHTML);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const toast = document.querySelector('.notification-toast');
            if (toast) toast.remove();
        }, 5000);
    }
});
</script>

{{-- Script tambahan dari halaman anak --}}
@stack('scripts')

<style>
@keyframes slide-in {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>

</body>
</html>
