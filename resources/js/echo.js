import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Only initialize Echo if Pusher credentials are provided
const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const pusherCluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'ap1';

if (pusherKey) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: pusherKey,
        cluster: pusherCluster,
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
} else {
    console.warn('⚠️ Pusher is not configured. Real-time features will be disabled.');
    // Create a dummy Echo object to prevent errors
    window.Echo = {
        channel: () => ({
            listen: () => { },
            notification: () => { },
        }),
        private: () => ({
            listen: () => { },
            notification: () => { },
        }),
        leave: () => { },
    };
}