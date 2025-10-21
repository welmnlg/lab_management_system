@extends('layouts.pageadmin')

@section('title', 'Notifikasi - ITLG Lab Management System')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
    <!-- Daftar Notifikasi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" id="notifications-container">
        <!-- Notifikasi akan di-load secara dinamis -->
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
        <p class="mt-1 text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
    </div>
</div>

<script>
// Notification Management untuk halaman notifikasi
document.addEventListener('DOMContentLoaded', function() {
    const notificationsContainer = document.getElementById('notifications-container');
    const emptyState = document.getElementById('empty-state');
    
    // Load and display notifications
    function loadNotifications() {
        const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
        
        if (notifications.length === 0) {
            notificationsContainer.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        notificationsContainer.classList.remove('hidden');
        
        // Urutkan: yang belum dibaca di atas, lalu yang terbaru di atas
        const sortedNotifications = [...notifications].sort((a, b) => {
            if (a.read !== b.read) {
                return a.read ? 1 : -1;
            }
            return new Date(b.createdAt) - new Date(a.createdAt);
        });
        
        notificationsContainer.innerHTML = sortedNotifications.map(notif => {
            const isUnread = !notif.read;
            
            return `
                <div class="border-b border-gray-200 last:border-b-0 notification-item ${isUnread ? 'bg-green-50 border-l-4 border-l-green-500' : ''}" 
                     data-id="${notif.id}">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-3 sm:space-x-4">
                                <!-- Icon -->
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full ${isUnread ? 'bg-green-100' : 'bg-gray-100'} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ${isUnread ? 'text-green-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                
                                <!-- Konten -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 mb-1">
                                        <span class="font-semibold text-gray-900 text-sm sm:text-base truncate">${notif.title}</span>
                                        <span class="hidden sm:inline text-xs text-gray-500">•</span>
                                        <span class="text-xs text-gray-500 mt-1 sm:mt-0">${notif.time}</span>
                                    </div>
                                    <p class="text-gray-700 ${isUnread ? 'font-medium' : ''} text-sm sm:text-base line-clamp-2">
                                        ${notif.message}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Indicator dots hijau untuk notifikasi baru -->
                            ${isUnread ? 
                                '<div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-green-500 mt-2 notification-indicator flex-shrink-0 ml-2" title="Pesan baru"></div>' 
                                : 
                                '<div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-gray-300 mt-2 flex-shrink-0 ml-2" title="Sudah dibaca"></div>'
                            }
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    // Mark all as read when leaving this page
    function setupPageLeaveHandler() {
        // Mark as read when user navigates away from this page
        window.addEventListener('beforeunload', function() {
            const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
            const updatedNotifications = notifications.map(notif => ({
                ...notif,
                read: true
            }));
            
            localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
            
            // Update header badge in parent
            if (window.parent && window.parent.updateHeaderBadge) {
                window.parent.updateHeaderBadge();
            }
            
            console.log('All notifications marked as read (leaving page)');
        });
        
        // Also mark as read when clicking any navigation link
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('notifikasi')) {
                const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
                const updatedNotifications = notifications.map(notif => ({
                    ...notif,
                    read: true
                }));
                
                localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
                
                if (window.parent && window.parent.updateHeaderBadge) {
                    window.parent.updateHeaderBadge();
                }
                
                console.log('All notifications marked as read (navigating away)');
            }
        });
    }
    
    // Initialize
    loadNotifications();
    setupPageLeaveHandler();
    
    // Auto-refresh setiap 3 detik untuk update real-time
    setInterval(loadNotifications, 3000);
});
</script>

<style>
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
</style>
@endsection