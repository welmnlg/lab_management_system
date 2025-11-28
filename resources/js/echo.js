import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
});

// Test connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Pusher connected');
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('❌ Pusher disconnected');
});

window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('❌ Pusher error:', error);
});
```

**File: `.env.example`** (Update)
```
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"