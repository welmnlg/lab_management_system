<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Get all rooms
     */
    public function index()
    {
        try {
            $rooms = Room::all();

            return response()->json([
                'success' => true,
                'data' => $rooms
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rooms: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ruangan'
            ], 500);
        }
    }

    /**
     * Store new room
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'location' => 'required|string|max:255',
                'room_name' => 'required|string|max:255'
            ]);

            $room = Room::create([
                'location' => $request->location,
                'room_name' => $request->room_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan',
                'data' => $room
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating room: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ruangan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get schedules for specific room
     */
    public function getSchedules($roomId)
    {
        try {
            // Get active period
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            // Build query - with or without period filter
            $query = Schedule::with(['user', 'class.course', 'room'])
                ->where('room_id', $roomId);
            
            // Add period filter if active period exists
            if ($activePeriod) {
                $query->where('period_id', $activePeriod->period_id);
            }
            
            $schedules = $query->get()
                ->map(function($schedule) {
                    // Format time slot from start_time and end_time
                    $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H.i');
                    $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H.i');
                    $timeSlot = "{$startTime} - {$endTime}";
                    
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $schedule->class && $schedule->class->course 
                            ? $schedule->class->course->course_name 
                            : 'Unknown Course',
                        'course_code' => $schedule->class && $schedule->class->course 
                            ? $schedule->class->course->course_code 
                            : '',
                        'lecturer_name' => $schedule->user->name ?? 'Unknown Lecturer',
                        'user_id' => $schedule->user_id, // Owner of schedule
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day_of_week' => $schedule->day,
                        'time_slot' => $timeSlot,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'status' => $schedule->status ?? 'terjadwal',
                        'building_name' => $schedule->room->location ?? 'Unknown Location',
                        'can_edit' => $schedule->user_id === auth()->id() // Can current user edit?
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting room schedules: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal ruangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manage rooms view
     */
    public function manage()
    {
        return view('manage.rooms');
    }

    /**
     * Get room usage report
     */
    public function usageReport()
    {
        try {
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $usage = DB::table('schedules')
                ->join('rooms', 'schedules.room_id', '=', 'rooms.room_id')
                ->where('schedules.period_id', $activePeriod->period_id)
                ->where('schedules.status', 'active')
                ->select(
                    'rooms.room_id',
                    'rooms.room_name',
                    'rooms.location as building_name',
                    DB::raw('COUNT(schedules.schedule_id) as schedule_count')
                )
                ->groupBy('rooms.room_id', 'rooms.room_name', 'rooms.location')
                ->orderBy('schedule_count', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'period_info' => $activePeriod,
                    'usage' => $usage
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating room usage report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat laporan penggunaan ruangan'
            ], 500);
        }
    }
}
