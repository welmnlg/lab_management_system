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
     * ENHANCED: Filter berdasarkan user yang login
     */
    public function getFormData()
    {
        try {
            $userId = auth()->user  (); // User yang login (Kim Mingyu = user_id 3)
            
            // ✅ FIX 1: Ambil UNIQUE course_id yang diajarkan user
            $userCourseIds = Schedule::where('user_id', $userId)
                ->join('course_classes', 'schedules.class_id', '=', 'course_classes.class_id')
                ->pluck('course_classes.course_id')
                ->unique();
            
            // ✅ FIX 2: Ambil class_id yang diajarkan user
            $userClassIds = Schedule::where('user_id', $userId)
                ->pluck('class_id')
                ->unique();
            
            $data = [
                // Filtered courses: hanya mata kuliah yang diajarkan user
                'courses' => Course::whereIn('course_id', $userCourseIds)
                    ->select('course_id', 'course_code', 'course_name')
                    ->orderBy('course_name')
                    ->get(),
                
                // Filtered course classes: hanya kelas yang diajarkan user
                'course_classes' => CourseClass::whereIn('class_id', $userClassIds)
                    ->with('course:course_id,course_name,course_code')
                    ->select('class_id', 'course_id', 'class_name', 'lecturer')
                    ->orderBy('class_name')
                    ->get()
                    ->map(function($class) {
                        return [
                            'class_id' => $class->class_id,
                            'course_id' => $class->course_id,
                            'class_name' => $class->class_name,
                            'lecturer' => $class->lecturer,
                            'display_name' => $class->course->course_name . ' - Kelas ' . $class->class_name
                        ];
                    }),
                
                // Rooms (semua ruangan)
                'rooms' => Room::select('room_id', 'room_name', 'location')
                    ->orderBy('room_name')
                    ->get(),
                
                // Days (hari kerja)
                'days' => [
                    ['value' => 'Senin', 'label' => 'Senin'],
                    ['value' => 'Selasa', 'label' => 'Selasa'],
                    ['value' => 'Rabu', 'label' => 'Rabu'],
                    ['value' => 'Kamis', 'label' => 'Kamis'],
                    ['value' => 'Jumat', 'label' => 'Jumat']
                ],
                
                // Time slots
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
                'data' => $data
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
     * Membuat kelas ganti dengan validasi lengkap dan week-based logic
     */
    public function createScheduleOverride(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|integer|exists:course_classes,class_id',
            'room_id' => 'required|exists:rooms,room_id',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $userId = auth()->id();
            
            // ✅ STEP 1: Hitung tanggal override berdasarkan week-based logic
            $overrideDate = $this->calculateOverrideDate($request->day);
            
            // ✅ STEP 2: Cek apakah ada jadwal asli user di class_id ini
            $originalSchedule = Schedule::where('user_id', $userId)
                ->where('class_id', $request->class_id)
                ->first();
            
            if (!$originalSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak mengajar kelas ini'
                ], 403);
            }
            
            // ✅ STEP 3: Cek konflik dengan jadwal lain di schedule
            $conflictResult = $this->checkScheduleConflict(
                $request->room_id,
                $request->day,
                $request->start_time,
                $request->end_time,
                $userId,
                $request->class_id
            );
            
            if ($conflictResult['has_conflict']) {
                if ($conflictResult['is_own_schedule']) {
                    // Konflik dengan jadwal sendiri (tapi beda mata kuliah/kelas)
                    return response()->json([
                        'success' => false,
                        'message' => "Anda memiliki jadwal lain di ruangan {$conflictResult['room_name']} pada hari {$request->day} jam {$request->start_time}-{$request->end_time} untuk {$conflictResult['conflict_info']}. Apakah Anda ingin override jadwal tersebut?",
                        'conflict' => true,
                        'requires_confirmation' => true,
                        'conflict_details' => $conflictResult
                    ], 409);
                } else {
                    // Konflik dengan jadwal orang lain
                    return response()->json([
                        'success' => false,
                        'message' => "Ruangan {$conflictResult['room_name']} sudah digunakan pada {$request->day} jam {$request->start_time}-{$request->end_time} oleh {$conflictResult['instructor_name']} untuk {$conflictResult['conflict_info']}",
                        'conflict' => true,
                        'requires_confirmation' => false
                    ], 409);
                }
            }
            
            // ✅ STEP 4: Cek konflik dengan schedule_override lain
            $overrideConflict = ScheduleOverride::where('room_id', $request->room_id)
                ->where('date', $overrideDate->format('Y-m-d'))
                ->where('status', 'active')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->with(['user', 'courseClass.course'])
                ->first();
            
            if ($overrideConflict) {
                return response()->json([
                    'success' => false,
                    'message' => "Sudah ada kelas ganti di ruangan ini pada tanggal yang sama oleh {$overrideConflict->user->name}",
                    'conflict' => true
                ], 409);
            }
            
            // ✅ STEP 5: Jika tidak ada konflik, simpan override
            // Cari schedule_id asli untuk referensi
            $originalScheduleForOverride = Schedule::where('user_id', $userId)
                ->where('class_id', $request->class_id)
                ->where('day', $originalSchedule->day)
                ->first();
            
            $override = ScheduleOverride::create([
                'schedule_id' => $originalScheduleForOverride->schedule_id ?? null,
                'user_id' => $userId,
                'room_id' => $request->room_id,
                'class_id' => $request->class_id,
                'date' => $overrideDate->format('Y-m-d'),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => $request->reason ?? "Kelas ganti",
                'status' => 'active'
            ]);
            
            $override->load(['user', 'room', 'courseClass.course', 'schedule']);
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil dibuat',
                'data' => [
                    'override' => $override,
                    'override_date' => $overrideDate->format('Y-m-d'),
                    'week_info' => $this->getWeekInfo($overrideDate)
                ]
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
 * Helper: Hitung tanggal override berdasarkan week-based logic
 */
private function calculateOverrideDate($selectedDay)
{
    $now = Carbon::now();
    $currentWeekStart = $now->copy()->startOfWeek(Carbon::MONDAY);
    
    // Map hari ke index (0 = Senin, 4 = Jumat)
    $dayMap = [
        'Senin' => 0,
        'Selasa' => 1,
        'Rabu' => 2,
        'Kamis' => 3,
        'Jumat' => 4
    ];
    
    $selectedDayIndex = $dayMap[$selectedDay];
    
    // Hitung tanggal untuk hari yang dipilih di minggu ini
    $targetDateThisWeek = $currentWeekStart->copy()->addDays($selectedDayIndex);
    
    // Jika tanggal sudah lewat (lebih kecil dari hari ini), gunakan minggu depan
    if ($targetDateThisWeek->lt($now->startOfDay())) {
        return $targetDateThisWeek->addWeek();
    }
    
    return $targetDateThisWeek;
}

/**
 * Helper: Get week info for display
 */
private function getWeekInfo($date)
{
    $weekStart = $date->copy()->startOfWeek(Carbon::MONDAY);
    $weekEnd = $weekStart->copy()->endOfWeek(Carbon::FRIDAY);
    
    return [
        'week_start' => $weekStart->format('Y-m-d'),
        'week_end' => $weekEnd->format('Y-m-d'),
        'week_display' => $weekStart->format('d M') . ' - ' . $weekEnd->format('d M Y')
    ];
}


    /**
     * Helper: Cek konflik jadwal dengan logic khusus
     */
    private function checkScheduleConflict($roomId, $day, $startTime, $endTime, $userId, $classId)
    {
        // Cek jadwal di tabel schedule pada hari dan ruangan yang sama
        $conflictSchedule = Schedule::where('room_id', $roomId)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>=', $endTime);
                    });
            })
            ->with(['user', 'room', 'courseClass.course'])
            ->first();
        
        if (!$conflictSchedule) {
            return ['has_conflict' => false];
        }
        
        // Jika konflik adalah jadwal yang sama persis (class_id sama), abaikan
        if ($conflictSchedule->class_id == $classId) {
            return ['has_conflict' => false];
        }
        
        // Jika konflik adalah jadwal user sendiri (tapi beda kelas)
        $isOwnSchedule = $conflictSchedule->user_id == $userId;
        
        return [
            'has_conflict' => true,
            'is_own_schedule' => $isOwnSchedule,
            'schedule_id' => $conflictSchedule->schedule_id,
            'room_name' => $conflictSchedule->room->room_name,
            'instructor_name' => $conflictSchedule->user->name,
            'conflict_info' => $conflictSchedule->courseClass->course->course_name . ' - Kelas ' . $conflictSchedule->courseClass->class_name,
            'conflict_schedule' => $conflictSchedule
        ];
    }
    

    /**
     * Helper: Convert day name to Carbon day number
     */
    private function getIndonesianDay($date)
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];
        
        return $days[$date->dayOfWeek];
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
    public function getLabSchedule($labName)
    {
        $today = Carbon::today();
        $now = Carbon::now();

        $schedules = Schedule::where('lab_name', $labName)
            ->whereDate('date', $today)
            ->orderBy('start_time')
            ->get()
            ->map(function ($item) use ($now) {
                if ($now->between($item->start_time, $item->end_time)) {
                    $item->status = 'Sedang Berlangsung';
                } elseif ($now->greaterThan($item->end_time)) {
                    $item->status = 'Selesai';
                } else {
                    $item->status = 'Akan Berlangsung';
                }
                return $item;
            });

        return response()->json($schedules);
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