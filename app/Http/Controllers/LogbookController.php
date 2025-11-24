<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Room;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Show logbook dashboard
     */
    public function index(Request $request)
    {
        // ✅ Filter logbooks
        $query = Logbook::with(['user', 'room', 'course', 'schedule']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        if ($request->filled('activity')) {
            $query->where('activity', $request->activity);
        }

        $logbooks = $query->orderBy('date', 'desc')
            ->orderBy('login', 'desc')
            ->paginate(20);

        $users = User::all();
        $rooms = Room::all();

        return view('logbooks.index', compact('logbooks', 'users', 'rooms'));
    }

    /**
     * ✅ NEW: Get logbook untuk user hari ini (API)
     */
    public function getMyLogbookToday()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $today = Carbon::today();

            $logbooks = Logbook::where('user_id', $user->user_id ?? $user->id)
                ->whereDate('date', $today)
                ->with(['user', 'room', 'course', 'schedule'])
                ->orderBy('login', 'desc')
                ->get();

            // ✅ Map dengan calculated fields
            $logbookData = $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'user_name' => $logbook->user->name ?? 'N/A',
                    'nim' => $logbook->user->nim ?? 'N/A',
                    'course_name' => $logbook->course->course_name ?? 'N/A',
                    'class_name' => $logbook->schedule->courseClass->class_name ?? 'N/A',
                    'room_name' => $logbook->room->room_name ?? 'N/A',
                    'date' => $logbook->date->format('d/m/Y'),
                    'schedule_time' => $logbook->schedule 
                        ? $logbook->schedule->start_time . ' - ' . $logbook->schedule->end_time
                        : 'N/A',
                    'login' => $logbook->login,
                    'logout' => $logbook->logout ?? '-',
                    'duration' => $this->calculateDuration($logbook->login, $logbook->logout),
                    'activity' => $logbook->activity,
                    'status' => $logbook->status ?? 'AKTIF',
                    'is_active' => is_null($logbook->logout),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $logbookData,
                'count' => count($logbookData),
                'today' => $today->format('d/m/Y')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting logbook today: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ NEW: Create logbook entry saat user scan QR
     */
    public function createLoginEntry($userId, $roomId, $courseId, $scheduleId = null, $activity = 'MENGAJAR')
    {
        try {
            $logbook = Logbook::create([
                'user_id' => $userId,
                'room_id' => $roomId,
                'course_id' => $courseId,
                'schedule_id' => $scheduleId,
                'date' => today(),
                'login' => now()->format('H:i:s'),
                'activity' => $activity,
                'status' => null,
                'logout' => null
            ]);

            return $logbook->load(['user', 'room', 'course', 'schedule']);

        } catch (\Exception $e) {
            \Log::error('Error creating logbook entry: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ✅ NEW: Update logbook saat user klik "Selesai" atau "Pindah Ruangan"
     */
    public function updateLogoutEntry(Request $request, $logbookId)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'status' => 'required|in:SELESAI,GANTI RUANGAN'
            ]);

            $logbook = Logbook::findOrFail($logbookId);

            // ✅ Verify ownership
            if ($logbook->user_id != ($user->user_id ?? $user->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // ✅ Update logout + status
            $logbook->update([
                'logout' => now()->format('H:i:s'),
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logbook updated successfully',
                'data' => $logbook->load(['user', 'room', 'course', 'schedule']),
                'duration' => $this->calculateDuration($logbook->login, $logbook->logout)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating logbook: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ NEW: Get active sessions (masih login)
     */
    public function getActiveSessions()
    {
        try {
            $activeSessions = Logbook::with(['user', 'room', 'course', 'schedule'])
                ->whereDate('date', today())
                ->whereNull('logout')
                ->orderBy('login', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $activeSessions,
                'count' => count($activeSessions)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting active sessions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Calculate duration antara login dan logout
     */
    private function calculateDuration($loginTime, $logoutTime)
    {
        if (!$logoutTime) {
            return 'AKTIF';
        }

        try {
            $login = Carbon::createFromFormat('H:i:s', $loginTime);
            $logout = Carbon::createFromFormat('H:i:s', $logoutTime);

            $minutes = $logout->diffInMinutes($login);
            $hours = intdiv($minutes, 60);
            $mins = $minutes % 60;

            if ($hours > 0) {
                return "{$hours}h {$mins}m";
            } else {
                return "{$mins}m";
            }
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * Report
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'room_id' => 'nullable|exists:rooms,room_id',
            'user_id' => 'nullable|exists:users,user_id',
        ]);

        $query = Logbook::with(['user', 'room', 'course'])
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']]);

        if ($validated['room_id']) {
            $query->where('room_id', $validated['room_id']);
        }

        if ($validated['user_id']) {
            $query->where('user_id', $validated['user_id']);
        }

        $logbooks = $query->orderBy('date', 'desc')
            ->orderBy('login', 'desc')
            ->get();

        return response()->json($logbooks);
    }
}