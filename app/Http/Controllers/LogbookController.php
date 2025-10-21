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
     * Display a listing of logbooks.
     */
    public function index(Request $request)
    {
        $query = Logbook::with(['user', 'room', 'course', 'schedule']);

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan ruangan
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Filter berdasarkan aktivitas
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
     * Store login entry (when user confirms room usage).
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'room_id' => 'required|exists:rooms,room_id',
            'course_id' => 'required|exists:courses,course_id',
            'schedule_id' => 'nullable|exists:schedules,schedule_id',
            'override_id' => 'nullable|exists:schedule_overrides,id',
            'activity' => 'required|in:MENGAJAR,BELAJAR',
        ]);

        $logbook = Logbook::create([
            'user_id' => $validated['user_id'],
            'room_id' => $validated['room_id'],
            'course_id' => $validated['course_id'],
            'schedule_id' => $validated['schedule_id'] ?? null,
            'override_id' => $validated['override_id'] ?? null,
            'date' => Carbon::today(),
            'login' => Carbon::now()->format('H:i:s'),
            'activity' => $validated['activity'],
        ]);

        return response()->json([
            'message' => 'Login berhasil dicatat',
            'data' => $logbook->load(['user', 'room', 'course'])
        ]);
    }

    /**
     * Store logout entry (when user finishes using room).
     */
    public function logout(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:GANTI RUANGAN,SELESAI',
        ]);

        $logbook->update([
            'logout' => Carbon::now()->format('H:i:s'),
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Logout berhasil dicatat',
            'data' => $logbook->load(['user', 'room', 'course'])
        ]);
    }

    /**
     * Get active sessions (users who haven't logged out).
     */
    public function activeSessions()
    {
        $activeSessions = Logbook::with(['user', 'room', 'course'])
            ->whereDate('date', Carbon::today())
            ->whereNull('logout')
            ->orderBy('login', 'desc')
            ->get();

        return response()->json($activeSessions);
    }

    /**
     * Generate report for specific date range.
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
