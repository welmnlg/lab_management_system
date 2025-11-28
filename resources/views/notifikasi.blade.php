@extends('layouts.main')
@section('title', 'Notifikasi - ITLG Lab Management System')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden" id="notifications-container">
        <div class="text-center py-12">
            <div class="animate-spin inline-block w-8 h-8 border-4 border-gray-300 border-t-blue-500 rounded-full"></div>
            <p class="mt-4 text-gray-600">Memuat notifikasi...</p>
        </div>
    </div>
    <div id="empty-state" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="text-center py-12 px-4">
            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada notifikasi</h3>
            <p class="mt-1 text-gray-500">Anda tidak memiliki notifikasi saat ini.</p>
        </div>
    </div>
</div>

<script>
let currentUserId = {{ Auth::user()->user_id }};
let allNotifications = [];
let hasMarkedAsRead = false;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Notifikasi page loaded, user_id:', currentUserId);
    loadNotifications();
    subscribeToNotifications();
    setTimeout(() => markAllAsRead(), 1000);
    setInterval(loadNotifications, 30000);
    // Update timestamps every minute
    setInterval(updateTimestamps, 60000);
});

function loadNotifications() {
    const container = document.getElementById('notifications-container');
    const emptyState = document.getElementById('empty-state');

    fetch('/api/notifications', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response failed');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            allNotifications = data.data || [];
            console.log('📬 Loaded notifications:', allNotifications.length);
            
            if (allNotifications.length === 0) {
                container.classList.add('hidden');
                emptyState.classList.remove('hidden');
            } else {
                renderNotifications(allNotifications);
                container.classList.remove('hidden');
                emptyState.classList.add('hidden');
            }
            updateNavbarBadge();
        }
    })
    .catch(error => {
        console.error('❌ Error loading notifications:', error);
        container.innerHTML = `
            <div class="text-center py-12">
                <p class="text-red-600">Gagal memuat notifikasi</p>
                <button onclick="loadNotifications()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Coba Lagi
                </button>
            </div>
        `;
    });
}

function formatRelativeTime(timestamp) {
    const now = new Date();
    const notifDate = new Date(timestamp);
    const diffMs = now - notifDate;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'Baru saja';
    if (diffMins < 60) return `${diffMins} menit lalu`;
    if (diffHours < 24) return `${diffHours} jam lalu`;
    if (diffDays === 1) return 'Kemarin';
    
    // Format tanggal: "28 Nov 2025"
    const day = notifDate.getDate();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    const month = months[notifDate.getMonth()];
    const year = notifDate.getFullYear();
    return `${day} ${month} ${year}`;
}

function renderNotifications(notifications) {
    const container = document.getElementById('notifications-container');
    
    if (notifications.length === 0) {
        container.innerHTML = '';
        return;
    }

    const sortedNotifications = [...notifications].sort((a, b) => {
        if (a.status !== b.status) {
            return a.status === 'waiting' ? -1 : 1;
        }
        return new Date(b.created_at) - new Date(a.created_at);
    });

    container.innerHTML = sortedNotifications.map(notif => {
        const isUnread = notif.status === 'waiting';
        const timeAgo = formatRelativeTime(notif.created_at);
        
        return `
            <div class="border-b border-gray-200 last:border-b-0 notification-item ${isUnread ? 'bg-green-50 border-l-4 border-l-green-500' : ''}" 
                 data-id="${notif.notification_id}"
                 data-timestamp="${notif.created_at}">
                <div class="p-4 sm:p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3 sm:space-x-4 flex-1">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full ${isUnread ? 'bg-green-100' : 'bg-gray-100'} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 ${isUnread ? 'text-green-600' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 mb-1">
                                    <span class="font-semibold text-gray-900 text-sm sm:text-base truncate">${notif.title}</span>
                                    <span class="hidden sm:inline text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-500 mt-1 sm:mt-0 timestamp-text">${timeAgo}</span>
                                </div>
                                <p class="text-gray-700 ${isUnread ? 'font-medium' : ''} text-sm sm:text-base line-clamp-2">
                                    ${notif.message}
                                </p>
                            </div>
                        </div>
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

function updateTimestamps() {
    document.querySelectorAll('.notification-item').forEach(item => {
        const timestamp = item.dataset.timestamp;
        const timestampEl = item.querySelector('.timestamp-text');
        if (timestampEl && timestamp) {
            timestampEl.textContent = formatRelativeTime(timestamp);
        }
    });
}

function markAllAsRead() {
    if (hasMarkedAsRead) {
        console.log('⚠️ Already marked as read, skipping...');
        return;
    }
    
    const unreadNotifs = allNotifications.filter(n => n.status === 'waiting');
    
    if (unreadNotifs.length === 0) {
        console.log('ℹ️ No unread notifications to mark');
        return;
    }
    
    console.log('📖 Auto-marking all notifications as read...');
    hasMarkedAsRead = true;
    
    fetch('/api/notifications/confirm-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ All notifications marked as read');
            updateNavbarBadge(0);
            setTimeout(() => loadNotifications(), 500);
        }
    })
    .catch(error => {
        console.error('❌ Error marking notifications as read:', error);
        hasMarkedAsRead = false;
    });
}

function updateNavbarBadge(count = null) {
    const badge = document.getElementById('notification-badge');
    
    if (count === null) {
        count = allNotifications.filter(n => n.status === 'waiting').length;
    }
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
}

function subscribeToNotifications() {
    if (!window.Echo) {
        console.warn('⚠️ Echo not initialized yet');
        setTimeout(subscribeToNotifications, 1000);
        return;
    }

    const channelName = `notification.${currentUserId}`;
    console.log(`🔔 Subscribing to channel: ${channelName}`);

    window.Echo.private(channelName)
        .listen('ScheduleReminderNotification', (event) => {
            console.log('📬 Real-time notification received:', event);
            setTimeout(() => {
                hasMarkedAsRead = false;
                loadNotifications();
            }, 500);
        })
        .error((error) => {
            console.error('❌ Error subscribing to channel:', error);
        });

    console.log(`✅ Subscribed to ${channelName}`);
}
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection