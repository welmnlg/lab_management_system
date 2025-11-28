<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for current user
     */
    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $limit = $request->get('limit', 50);

            $notifications = Notification::with(['schedule.course', 'schedule.class', 'schedule.room'])
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($notif) {
                    return [
                        'notification_id' => $notif->notification_id,
                        'schedule_id' => $notif->schedule_id,
                        'title' => $notif->title,
                        'message' => $notif->message,
                        'status' => $notif->class_status,
                        'course_name' => $notif->schedule->course->course_name ?? '',
                        'class_name' => $notif->schedule->class->class_name ?? '',
                        'room_name' => $notif->schedule->room->room_name ?? '',
                        'time_slot' => $notif->schedule->time_slot,
                        'day' => $notif->schedule->day,
                        'notified_at' => $notif->notified_at?->toIso8601String(),
                        'confirmed_at' => $notif->confirmed_at?->toIso8601String(),
                        'created_at' => $notif->created_at->toIso8601String()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'total' => $notifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi'
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        try {
            $userId = Auth::id();
            $count = Notification::where('user_id', $userId)
                ->where('class_status', 'waiting')
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0
            ]);
        }
    }

    /**
     * Mark notification as confirmed
     */
    public function confirm(Request $request)
    {
        try {
            $request->validate([
                'notification_id' => 'required|exists:notifications,notification_id'
            ]);

            $userId = Auth::id();
            $notif = Notification::where('notification_id', $request->notification_id)
                ->where('user_id', $userId)
                ->first();

            if (!$notif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            $notif->update([
                'class_status' => 'confirmed',
                'confirmed_at' => now()
            ]);

            Log::info('Notification confirmed', [
                'notification_id' => $notif->notification_id,
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dikonfirmasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Error confirming notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengonfirmasi notifikasi'
            ], 500);
        }
    }

    /**
     * Mark all notifications as confirmed
     */
    public function confirmAll(Request $request)
    {
        try {
            $userId = Auth::id();

            Notification::where('user_id', $userId)
                ->where('class_status', 'waiting')
                ->update([
                    'class_status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

            Log::info('All notifications confirmed', ['user_id' => $userId]);

            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil dikonfirmasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Error confirming all notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengonfirmasi notifikasi'
            ], 500);
        }
    }

// FILE: app/Http/Controllers/NotificationController.php - METHOD destroy SAJA

    /**
     * Delete notification
     */
    public function destroy($notificationId)
    {
        try {
            $userId = Auth::id();
            $notif = Notification::where('notification_id', $notificationId)
                ->where('user_id', $userId)
                ->first();

            if (!$notif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notifikasi tidak ditemukan'
                ], 404);
            }

            $notif->delete();

            Log::info('Notification deleted', [
                'notification_id' => $notificationId,
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus notifikasi'
            ], 500);
        }
    }
}