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
     * Get Logbook Data for DataTable (API)
     */
    public function getLogbookData(Request $request)
    {
        try {
            $query = Logbook::with(['user', 'room', 'course', 'schedule']);

            // Filter Period
            if ($request->period === 'week') {
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->period === 'month') {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
            } elseif ($request->period === 'day') {
                $query->whereDate('date', now());
            }

            // Consolidated Search (Name or NIM)
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nim', 'like', '%' . $search . '%');
                });
            }

            // Advanced Filters
            if ($request->filled('course')) {
                $query->whereHas('course', function($q) use ($request) {
                    $q->where('course_name', $request->course);
                });
            }

            if ($request->filled('class')) {
                $query->whereHas('schedule.courseClass', function($q) use ($request) {
                    $q->where('class_name', $request->class);
                });
            }

            if ($request->filled('room')) {
                $query->whereHas('room', function($q) use ($request) {
                    $q->where('room_name', $request->room);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Pagination 10 items
            $logbooks = $query->orderBy('date', 'desc')
                ->orderBy('login', 'desc')
                ->paginate(10);

            $data = $logbooks->getCollection()->map(function ($log) {
                return [
                    'date' => $log->date->format('d/m/Y'),
                    'name' => $log->user->name ?? '-',
                    'nim' => $log->user->nim ?? '-',
                    'course' => $log->course->course_name ?? '-',
                    'class' => $log->schedule->courseClass->class_name ?? '-',
                    'room' => $log->room->room_name ?? '-',
                    'schedule' => $log->schedule ? ($log->schedule->day . ' / ' . $log->schedule->start_time . ' - ' . $log->schedule->end_time) : '-',
                    // ✅ FIX: Format Time Only (H:i:s)
                    'login' => $log->login ? \Carbon\Carbon::parse($log->login)->format('H:i:s') : '-',
                    'logout' => $log->logout ? \Carbon\Carbon::parse($log->logout)->format('H:i:s') : '-',
                    'activity' => $log->activity,
                    'status' => $log->status ?? 'AKTIF'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $logbooks->currentPage(),
                    'last_page' => $logbooks->lastPage(),
                    'total' => $logbooks->total(),
                    'per_page' => $logbooks->perPage(),
                    'next_page_url' => $logbooks->nextPageUrl(),
                    'prev_page_url' => $logbooks->previousPageUrl(),
                    'links' => $logbooks->linkCollection()->toArray() // ✅ Add links for standard pagination
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get Filter Options for Dropdowns
     */
    public function getFilterOptions()
    {
        try {
            $courses = Course::select('course_name')->distinct()->orderBy('course_name')->pluck('course_name');
            $rooms = Room::select('room_name')->distinct()->orderBy('room_name')->pluck('room_name');
            
            // For classes, we need to join with course_classes
            $classes = \App\Models\CourseClass::select('class_name')->distinct()->orderBy('class_name')->pluck('class_name');

            return response()->json([
                'success' => true,
                'courses' => $courses,
                'rooms' => $rooms,
                'classes' => $classes
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Export Logbook to Excel
     */
    public function exportLogbook(Request $request)
    {
        $filters = $request->all();
        $filename = 'logbook_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LogbookExport($filters), $filename);
    }
}