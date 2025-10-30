<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RoomScheduleService;
use App\Models\Room;
use App\Models\ScheduleOverride;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    protected $scheduleService;

    public function __construct(RoomScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * GET /api/dashboard/rooms/status
     * Mendapatkan status semua ruangan (hijau/abu-abu)
     */
    public function getRoomsStatus()
    {
        try {
            $rooms = $this->scheduleService->getAllRoomsStatus();
            
            return response()->json([
                'success' => true,
                'message' => 'Status ruangan berhasil diambil',
                'data' => $rooms,
                'timestamp' => Carbon::now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status ruangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/dashboard/rooms/{roomId}/schedules
     * Mendapatkan jadwal ruangan untuk hari ini
     */
    public function getRoomSchedules($roomId, Request $request)
    {
        try {
            $date = $request->input('date') 
                ? Carbon::parse($request->input('date')) 
                : Carbon::now();
            
            $room = Room::findOrFail($roomId);
            $serviceResult = $this->scheduleService->getRoomSchedulesForDay($roomId, $date);
            $schedules = $serviceResult['schedules'];
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal ruangan berhasil diambil',
                'data' => [
                    'room' => [
                        'room_id' => $room->room_id,
                        'room_name' => $room->room_name,
                        'location' => $room->location
                    ],
                    'date' => $date->format('Y-m-d'),
                    'day' => $date->locale('id')->isoFormat('dddd'),
                    'schedules' => $schedules,
                    'total_schedules' => count($schedules)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal ruangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/dashboard/rooms/{roomId}/calendar
     * Mendapatkan kalender mingguan untuk ruangan
     */
    public function getRoomWeeklyCalendar($roomId, Request $request)
    {
        try {
            $weekStart = $request->query('week_start', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'));
            $calendar = $this->scheduleService->getWeeklyCalendar($roomId, $weekStart);
            
            return response()->json([
                'success' => true,
                'message' => 'Kalender mingguan berhasil diambil',
                'data' => $calendar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kalender: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/dashboard/form-data
     * Mendapatkan data untuk form kelas ganti (dropdown options)
     */
    public function getFormData()
    {
        try {
            // ✅ Hardcode user_id = 1 untuk sementara
            $userId = 1;  // Nanti ganti: auth()->id()
            
            // Filter courses & classes berdasarkan user
            $userCourseIds = Schedule::where('user_id', $userId)
                ->pluck('class_id')
                ->unique();
            
            $userCourses = CourseClass::whereIn('class_id', $userCourseIds)
                ->pluck('course_id')
                ->unique();
            
            $data = [
                // Filtered courses
                'courses' => Course::whereIn('course_id', $userCourses)
                    ->select('course_id', 'course_code', 'course_name')
                    ->orderBy('course_name')
                    ->get(),
                
                // Filtered course classes
                'course_classes' => CourseClass::whereIn('class_id', $userCourseIds)
                    ->with('course:course_id,course_name')
                    ->select('class_id', 'course_id', 'class_name', 'lecturer')
                    ->orderBy('class_name')
                    ->get(),
                
                // Rooms (semua ruangan - tidak difilter)
                'rooms' => Room::select('room_id', 'room_name', 'location')
                    ->orderBy('room_name')
                    ->get(),
                
                // Instructors (semua dosen/aslab)
                'instructors' => User::whereHas('roles', function($query) {
                        $query->whereIn('name', ['Aslab', 'Dosen']);
                    })
                    ->select('user_id', 'name', 'email')
                    ->orderBy('name')
                    ->get(),
                
                // Days (semua hari)
                'days' => [
                    ['value' => 'Senin', 'label' => 'Senin'],
                    ['value' => 'Selasa', 'label' => 'Selasa'],
                    ['value' => 'Rabu', 'label' => 'Rabu'],
                    ['value' => 'Kamis', 'label' => 'Kamis'],
                    ['value' => 'Jumat', 'label' => 'Jumat']
                ],
                
                // Time slots (semua waktu)
                'time_slots' => [
                    ['value' => '08:00-09:40', 'start' => '08:00:00', 'end' => '09:40:00', 'label' => '08:00 - 09:40'],
                    ['value' => '09:40-11:20', 'start' => '09:40:00', 'end' => '11:20:00', 'label' => '09:40 - 11:20'],
                    ['value' => '11:20-13:00', 'start' => '11:20:00', 'end' => '13:00:00', 'label' => '11:20 - 13:00'],
                    ['value' => '13:00-14:40', 'start' => '13:00:00', 'end' => '14:40:00', 'label' => '13:00 - 14:40'],
                    ['value' => '14:40-16:20', 'start' => '14:40:00', 'end' => '16:20:00', 'label' => '14:40 - 16:20']
                ]
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Data form berhasil diambil',
                'data' => $data  // ✅ "data" wrapper TETAP ADA
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data form',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/dashboard/schedule-override
     * Membuat kelas ganti (schedule override)
     */
    public function createScheduleOverride(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            // 'nim' => 'required|string|max:50',
            'class_id' => 'required|integer|exists:course_classes,class_id',
            'room_id' => 'required|exists:rooms,room_id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'start_time' => 'required',
            'end_time' => 'required',
            'week_start' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Hitung tanggal dari week_start + day
            $weekStart = Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY);
            $dayIndex = array_search($request->day, ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
            $date = $weekStart->copy()->addDays($dayIndex);

            // Cek conflict
            $hasConflict = $this->scheduleService->hasScheduleConflict(
                $request->room_id,
                $date->format('Y-m-d'),
                $request->start_time,
                $request->end_time,
                $request->class_id
            );

            if ($hasConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bentrok dengan jadwal lain di ruangan yang sama',
                    'conflict' => true
                ], 409);
            }

            // Simpan schedule override (gunakan user_id = 1 sebagai default untuk sementara)
            $override = ScheduleOverride::create([
                'schedule_id' => null,
                'user_id' => 1, // Default user ID (ubah setelah autentikasi ready)
                'room_id' => $request->room_id,
                'class_id' => $request->class_id,
                'date' => $date->format('Y-m-d'),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => "Kelas ganti oleh {$request->name} (NIM: {$request->nim})"
            ]);

            $override->load(['user', 'room', 'courseClass.course']);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil dibuat',
                'data' => $override
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat kelas ganti',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /api/dashboard/schedule-override/{id}
     * Update schedule override
     */
    public function updateScheduleOverride(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,user_id',
            'class_id' => 'sometimes|exists:course_classes,class_id',
            'room_id' => 'sometimes|exists:rooms,room_id',
            'date' => 'sometimes|date',
            'day' => 'sometimes|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'start_time' => 'sometimes|date_format:H:i:s',
            'end_time' => 'sometimes|date_format:H:i:s',
            'status' => 'sometimes|in:active,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $override = ScheduleOverride::findOrFail($id);
            
            // Jika update waktu/ruangan, cek conflict
            if ($request->has(['room_id', 'date', 'start_time', 'end_time'])) {
                $hasConflict = $this->scheduleService->hasScheduleConflict(
                    $request->room_id ?? $override->room_id,
                    $request->date ?? $override->date,
                    $request->start_time ?? $override->start_time,
                    $request->end_time ?? $override->end_time,
                    $id
                );

                if ($hasConflict) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jadwal bentrok dengan jadwal lain',
                        'conflict' => true
                    ], 409);
                }
            }

            $override->update($request->all());
            $override->load(['user', 'room', 'courseClass.course']);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil diupdate',
                'data' => $override
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update kelas ganti',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        /**
     * Check schedule conflict - FIXED VERSION
     */
    public function hasScheduleConflict($roomId, $date, $startTime, $endTime, $excludeOverrideId = null)
    {
        // Parse date
        $dateObj = Carbon::parse($date);
        
        // Get day name in Indonesian (sesuai dengan database format)
        $dayName = $this->getIndonesianDay($dateObj);
        
        // ✅ FIX: Check regular schedule dengan day name yang BENAR
        $hasRegularConflict = Schedule::where('room_id', $roomId)
            ->where('day', $dayName) // Pastikan format sama dengan database
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        // ✅ FIX: Check override schedule dengan date yang BENAR
        $hasOverrideConflict = ScheduleOverride::where('room_id', $roomId)
            ->where('date', $dateObj->format('Y-m-d')) // Pastikan format YYYY-MM-DD
            ->when($excludeOverrideId, function ($query, $id) {
                $query->where('id', '!=', $id);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        return $hasRegularConflict || $hasOverrideConflict;
    }


    /**
     * DELETE /api/dashboard/schedule-override/{id}
     * Batalkan schedule override
     */
    public function deleteScheduleOverride($id)
    {
        try {
            $override = ScheduleOverride::findOrFail($id);
            
            // Soft delete dengan ubah status jadi cancelled
            $override->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan kelas ganti',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
    // public function getAvailableTimeSlots(Request $request)
    // {
    //     $roomId = $request->room_id;
    //     $day = $request->day;
        
    //     $allSlots = [
    //         ['start' => '08:00:00', 'end' => '09:40:00', 'label' => '08:00 - 09:40'],
    //         ['start' => '09:40:00', 'end' => '11:20:00', 'label' => '09:40 - 11:20'],
    //         ['start' => '11:20:00', 'end' => '13:00:00', 'label' => '11:20 - 13:00'],
    //         ['start' => '13:00:00', 'end' => '14:40:00', 'label' => '13:00 - 14:40'],
    //         ['start' => '14:40:00', 'end' => '16:20:00', 'label' => '14:40 - 16:20'],
    //         // ... etc
    //     ];
        
    //     // Filter out occupied slots
    //     $occupied = Schedule::where('room_id', $roomId)
    //         ->where('day', $day)
    //         ->get(['start_time', 'end_time']);
        
    //     $available = array_filter($allSlots, function($slot) use ($occupied) {
    //         foreach ($occupied as $occ) {
    //             if ($slot['start'] == $occ->start_time && $slot['end'] == $occ->end_time) {
    //                 return false;
    //             }
    //         }
    //         return true;
    //     });
        
    //     return response()->json(['success' => true, 'slots' => array_values($available)]);
    // }
}