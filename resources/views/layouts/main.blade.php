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
    
    // Variable untuk track jumlah notifikasi terakhir
    let lastNotificationCount = 0;
    let isFirstLoad = true;

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
                const newCount = parseInt(data.count);
                
                // Update badge UI
                if (newCount > 0) {
                    badge.textContent = newCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
                
                // DETEKSI NOTIFIKASI BARU (Logic Toast)
                // Jika bukan load pertama, dan jumlah bertambah -> Ada notif baru!
                if (!isFirstLoad && newCount > lastNotificationCount) {
                    console.log('🔔 New notification detected via polling!');
                    fetchLatestNotificationAndShowToast();
                }
                
                // Update tracker
                lastNotificationCount = newCount;
                isFirstLoad = false;
            }
        })
        .catch(error => console.error('Error updating badge:', error));
    }
    
    /**
     * Fetch latest notification content for toast
     */
    function fetchLatestNotificationAndShowToast() {
        fetch('/api/notifications?limit=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.length > 0) {
                const latestNotif = data.data[0];
                
                // Hanya tampilkan jika statusnya 'waiting' (belum dibaca)
                // dan baru dibuat dalam 1 menit terakhir (untuk menghindari spam notif lama)
                const createdTime = new Date(latestNotif.created_at).getTime();
                const now = new Date().getTime();
                const diffMinutes = (now - createdTime) / 60000;
                
                if (latestNotif.status === 'waiting' && diffMinutes < 2) {
                    showNotificationToast({
                        title: latestNotif.title,
                        message: latestNotif.message,
                        // Extract info from message/data if needed
                        course_name: 'Jadwal Praktikum', // Fallback
                        class_name: '',
                        time_slot: '',
                        room_name: ''
                    });
                }
            }
        })
        .catch(err => console.error('Error fetching latest notif:', err));
    }
    
    // Initial badge update
    updateNotificationBadge();
    
    // Update badge setiap 5 detik (dipercepat biar lebih responsif)
    setInterval(updateNotificationBadge, 5000);
    
    // Subscribe to real-time badge updates via Pusher (tetap ada sebagai backup)
    if (window.Echo) {
        @auth
        const userId = {{ auth()->user()->user_id }};
        
        window.Echo.private(`notification.${userId}`)
            .listen('ScheduleReminderNotification', (event) => {
                console.log('🔔 Real-time event received');
                updateNotificationBadge();
                showNotificationToast(event);
            });
        @endauth
    }
    
    /**
     * Show toast notification (optional)
     */
    /**
     * Show toast notification (Top Right Pop-up)
     */
    function showNotificationToast(event) {
        // Cek apakah user sedang di halaman notifikasi (opsional, bisa dihapus jika ingin tetap muncul)
        if (window.location.pathname === '/notifikasi') {
            return; 
        }
        
        // Generate unique ID
        const toastId = 'toast-' + Date.now();
        
        // Message content (fallback if event structure differs)
        const title = event.title || 'Pengingat Jadwal Praktikum';
        const message = event.message || `Jadwal ${event.course_name || 'Praktikum'} akan segera dimulai.`;

        const toastHTML = `
            <div id="${toastId}" class="fixed top-24 right-4 max-w-md w-full md:w-[26rem] z-[9999] animate-slide-down notification-toast cursor-pointer group" 
                 onclick="window.location.href='/profil'">
                <div class="relative bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-blue-500/20">
                    
                    <!-- Vibrant Gradient Left Border -->
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-500 via-indigo-500 to-purple-600"></div>
                    
                    <!-- Decorative Background Glow -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

                    <div class="p-4 pl-5 flex items-start relative">
                        <!-- Vibrant Glowing Icon -->
                        <div class="flex-shrink-0 pt-1">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30 ring-2 ring-white relative overflow-hidden">
                                <div class="absolute inset-0 bg-white opacity-10 rotate-45 transform translate-y-1/2"></div>
                                <svg class="h-6 w-6 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-bold text-gray-900 leading-tight pr-2">${title}</p>
                                <!-- Red Dot Pulse -->
                                <span class="flex h-2.5 w-2.5 relative flex-shrink-0 mt-1">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                </span>
                            </div>
                            <p class="mt-1.5 text-sm text-gray-600 line-clamp-2 leading-relaxed">${message}</p>
                            
                            <div class="mt-3 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 group-hover:bg-indigo-100 transition-colors">
                                    📍 Klik untuk konfirmasi
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Close Button -->
                        <div class="ml-2 flex-shrink-0 flex -mr-1">
                            <button onclick="event.stopPropagation(); document.getElementById('${toastId}').remove()" class="text-gray-300 hover:text-gray-500 transition-colors focus:outline-none p-1 rounded-full hover:bg-gray-100">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Gradient Progress Bar -->
                    <div class="h-1 w-full bg-gray-50">
                        <div class="h-full bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 animate-progress"></div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', toastHTML);
        
        // Auto remove after 6 seconds
        setTimeout(() => {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('animate-fade-out');
                setTimeout(() => toast.remove(), 300); // Wait for fade out animation
            }
        }, 6000);
    }
});
</script>

{{-- Script tambahan dari halaman anak --}}
@stack('scripts')

<style>
@keyframes slide-down {
    from {
        transform: translateY(-100%) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

@keyframes fade-out {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.animate-slide-down {
    animation: slide-down 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); /* Bouncy effect */
}

.animate-fade-out {
    animation: fade-out 0.3s ease-in forwards;
}

.animate-progress {
    animation: progress 6s linear forwards;
}
</style>

</body>
</html>
