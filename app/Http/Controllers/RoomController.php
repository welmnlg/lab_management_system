<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use App\Models\Building;
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
            $rooms = Room::with('building')->get();

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
                'building_id' => 'required|exists:buildings,building_id',
                'room_name' => 'required|string|max:255'
            ]);

            $room = Room::create([
                'building_id' => $request->building_id,
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
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $schedules = Schedule::with(['user', 'course', 'class'])
                ->where('period_id', $activePeriod->period_id)
                ->where('room_id', $roomId)
                ->where('status', 'active')
                ->get()
                ->map(function($schedule) {
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                        'course_code' => $schedule->course->course_code ?? '',
                        'lecturer_name' => $schedule->user->name ?? 'Unknown Lecturer',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day_of_week' => $schedule->day,
                        'time_slot' => $schedule->time_slot,
                        'building_name' => $schedule->room->building->building_name ?? 'Unknown Building'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $schedules
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting room schedules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal ruangan'
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
                ->join('buildings', 'rooms.building_id', '=', 'buildings.building_id')
                ->where('schedules.period_id', $activePeriod->period_id)
                ->where('schedules.status', 'active')
                ->select(
                    'rooms.room_id',
                    'rooms.room_name',
                    'buildings.building_name',
                    DB::raw('COUNT(schedules.schedule_id) as schedule_count')
                )
                ->groupBy('rooms.room_id', 'rooms.room_name', 'buildings.building_name')
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