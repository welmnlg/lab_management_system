<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\SemesterPeriod;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Get schedules for a specific room - COMPLETELY FIXED VERSION
     */
    public function getSchedulesByRoom($roomId)
    {
        try {
            $activePeriod = SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $currentUser = Auth::user();
            $currentUserId = $currentUser->user_id;

            Log::info('🔍 === PERMISSION CHECK START ===', [
                'room_id' => $roomId,
                'current_user_id' => $currentUserId,
                'current_user_name' => $currentUser->name,
                'period_id' => $activePeriod->period_id
            ]);

            // CRITICAL FIX: Load schedules dengan eager loading user relationship
            $schedules = Schedule::with(['user', 'course', 'class', 'room.building'])
                ->where('period_id', $activePeriod->period_id)
                ->where('room_id', $roomId)
                ->where('status', 'active')
                ->get()
                ->map(function($schedule) use ($currentUserId, $activePeriod) {
                    // CRITICAL: Pastikan user_id adalah integer
                    $scheduleUserId = (int) $schedule->user_id;
                    $isOwner = $scheduleUserId === $currentUserId;

                    // Check permission menggunakan model Schedule
                    $canEdit = $schedule->canUserEdit($currentUserId);

                    // CRITICAL FIX: Get user name with proper fallback
                    $ownerName = 'Unknown Owner';
                    if ($schedule->user) {
                        $ownerName = $schedule->user->name;
                    } else {
                        // Fallback: load user manually
                        $user = User::find($scheduleUserId);
                        if ($user) {
                            $ownerName = $user->name;
                        }
                    }

                    Log::info('📋 SCHEDULE PERMISSION', [
                        'schedule_id' => $schedule->schedule_id,
                        'schedule_user_id' => $scheduleUserId,
                        'owner_name' => $ownerName,
                        'current_user_id' => $currentUserId,
                        'current_user_name' => Auth::user()->name,
                        'is_owner' => $isOwner,
                        'is_schedule_taking_open' => $activePeriod->is_schedule_taking_open,
                        'is_schedule_open' => $activePeriod->is_schedule_open,
                        'allowed_users' => $activePeriod->allowed_users,
                        'can_edit' => $canEdit ? 'YES ✅' : 'NO ❌'
                    ]);

                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                        'course_code' => $schedule->course->course_code ?? '',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day_of_week' => $schedule->day,
                        'time_slot' => $schedule->time_slot,
                        'building_name' => $schedule->room->building->building_name ?? 'Unknown Building',
                        'user_id' => $scheduleUserId, // ID user yang booking
                        'owner_name' => $ownerName,   // FIXED: Nama user yang booking (bukan lecturer)
                        'can_edit' => $canEdit
                    ];
                });

            Log::info('✅ === PERMISSION CHECK END ===', [
                'total_schedules' => $schedules->count(),
                'editable_count' => $schedules->filter(fn($s) => $s['can_edit'])->count(),
                'current_period' => $activePeriod->formatted_period
            ]);

            return response()->json([
                'success' => true,
                'data' => $schedules->values()
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error getting schedules by room: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal'
            ], 500);
        }
    }

    /**
     * Get specific schedule - FIXED VERSION
     */
    public function show($scheduleId)
    {
        try {
            $schedule = Schedule::with(['user', 'course', 'class', 'room.building'])
                ->where('schedule_id', $scheduleId)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            // Check if user can edit this schedule
            $canEdit = $schedule->canUserEdit(auth()->id());

            // Get owner name with fallback
            $ownerName = 'Unknown Owner';
            if ($schedule->user) {
                $ownerName = $schedule->user->name;
            } else {
                $user = User::find($schedule->user_id);
                if ($user) {
                    $ownerName = $user->name;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'schedule_id' => $schedule->schedule_id,
                    'course_id' => $schedule->course_id,
                    'course_code' => $schedule->course->course_code ?? '',
                    'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                    'class_id' => $schedule->class_id,
                    'class_name' => $schedule->class->class_name ?? '',
                    'room_id' => $schedule->room_id,
                    'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                    'building_id' => $schedule->room->building_id ?? null,
                    'building_name' => $schedule->room->building->building_name ?? 'Unknown Building',
                    'day_of_week' => $schedule->day,
                    'time_slot' => $schedule->time_slot,
                    'user_id' => $schedule->user_id,
                    'owner_name' => $ownerName, // FIXED: Nama user yang booking
                    'can_edit' => $canEdit
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jadwal'
            ], 500);
        }
    }

    public function getUserCoursesActive()
    {
        try {
            $userId = auth()->id();
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $currentSemester = $activePeriod->semester_type;

            $courses = DB::table('user_courses')
                ->join('course_classes', 'user_courses.class_id', '=', 'course_classes.class_id')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->where('user_courses.user_id', $userId)
                ->where('courses.semester', $currentSemester)
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'course_classes.class_id',
                    'course_classes.class_name'
                )
                ->get()
                ->groupBy('course_id')
                ->map(function($classes) {
                    $first = $classes->first();
                    return [
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'classes' => $classes->map(function($class) {
                            return [
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name
                            ];
                        })->values()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user courses for active semester: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata kuliah'
            ], 500);
        }
    }

    public function getBuildingsWithRooms()
    {
        try {
            $buildings = DB::table('buildings')
                ->select('building_id', 'building_name', 'building_code')
                ->orderBy('building_name')
                ->get();

            $rooms = DB::table('rooms')
                ->join('buildings', 'rooms.building_id', '=', 'buildings.building_id')
                ->select(
                    'rooms.room_id',
                    'rooms.room_name',
                    'buildings.building_id'
                )
                ->orderBy('rooms.room_name')
                ->get()
                ->groupBy('building_id');

            return response()->json([
                'success' => true,
                'data' => [
                    'buildings' => $buildings,
                    'rooms' => $rooms
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting buildings with rooms: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data gedung dan ruangan'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,course_id',
                'class_id' => 'required|exists:course_classes,class_id',
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'time_slot' => 'required|string'
            ], [
                'course_id.required' => 'Mata kuliah harus dipilih',
                'class_id.required' => 'Kelas harus dipilih',
                'room_id.required' => 'Ruangan harus dipilih',
                'day_of_week.required' => 'Hari harus dipilih',
                'time_slot.required' => 'Waktu harus dipilih'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $activePeriod = SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif. Silakan hubungi admin untuk membuka semester.'
                ], 404);
            }

            if (!$activePeriod->is_schedule_taking_open && !$activePeriod->is_schedule_open) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode pengambilan jadwal sudah ditutup. Silakan hubungi admin.'
                ], 422);
            }

            DB::beginTransaction();

            $timeSlot = $request->time_slot;
            $timeParts = explode(' - ', $timeSlot);
            if (count($timeParts) !== 2) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Format waktu tidak valid'
                ], 422);
            }
            $startTime = str_replace('.', ':', trim($timeParts[0])) . ':00';
            $endTime = trim($timeParts[1]) . ':00';

            $roomConflict = Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day_of_week)
                ->where('time_slot', $request->time_slot)
                ->where('period_id', $activePeriod->period_id)
                ->where('status', 'active')
                ->exists();

            if ($roomConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bentrok! Ruangan sudah dibooking pada waktu tersebut.'
                ], 422);
            }

            $userConflict = Schedule::where('user_id', auth()->id())
                ->where('day', $request->day_of_week)
                ->where('time_slot', $request->time_slot)
                ->where('period_id', $activePeriod->period_id)
                ->where('status', 'active')
                ->exists();

            if ($userConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki jadwal lain pada waktu yang sama!'
                ], 422);
            }

            $schedule = Schedule::create([
                'period_id' => $activePeriod->period_id,
                'user_id' => auth()->id(),
                'course_id' => $request->course_id,
                'class_id' => $request->class_id,
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'time_slot' => $request->time_slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'active'
            ]);

            DB::commit();

            Log::info('Schedule created successfully', [
                'schedule_id' => $schedule->schedule_id,
                'user_id' => auth()->id(),
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'time_slot' => $request->time_slot
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dibooking!',
                'data' => [
                    'schedule_id' => $schedule->schedule_id,
                    'schedule_info' => [
                        'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day' => $schedule->day,
                        'time_slot' => $schedule->time_slot
                    ]
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membooking jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $scheduleId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'time_slot' => 'required|string'
            ], [
                'room_id.required' => 'Ruangan harus dipilih',
                'day_of_week.required' => 'Hari harus dipilih',
                'time_slot.required' => 'Waktu harus dipilih'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $activePeriod = SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif.'
                ], 404);
            }

            $schedule = Schedule::where('schedule_id', $scheduleId)->first();
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            $currentUserId = auth()->id();
            if (!$schedule->canUserEdit($currentUserId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit jadwal ini. Periode pengambilan jadwal sudah ditutup.'
                ], 403);
            }

            DB::beginTransaction();

            $timeSlot = $request->time_slot;
            $timeParts = explode(' - ', $timeSlot);
            if (count($timeParts) !== 2) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Format waktu tidak valid'
                ], 422);
            }
            $startTime = str_replace('.', ':', trim($timeParts[0])) . ':00';
            $endTime = trim($timeParts[1]) . ':00';

            $roomConflict = Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day_of_week)
                ->where('time_slot', $request->time_slot)
                ->where('period_id', $activePeriod->period_id)
                ->where('status', 'active')
                ->where('schedule_id', '!=', $scheduleId)
                ->exists();

            if ($roomConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bentrok! Ruangan sudah dibooking pada waktu tersebut.'
                ], 422);
            }

            $userConflict = Schedule::where('user_id', $schedule->user_id)
                ->where('day', $request->day_of_week)
                ->where('time_slot', $request->time_slot)
                ->where('period_id', $activePeriod->period_id)
                ->where('status', 'active')
                ->where('schedule_id', '!=', $scheduleId)
                ->exists();

            if ($userConflict) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki jadwal lain pada waktu yang sama!'
                ], 422);
            }

            $schedule->update([
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'time_slot' => $request->time_slot,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);

            DB::commit();

            Log::info('Schedule updated successfully', [
                'schedule_id' => $schedule->schedule_id,
                'user_id' => $schedule->user_id,
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'time_slot' => $request->time_slot
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diupdate!',
                'data' => [
                    'schedule_id' => $schedule->schedule_id,
                    'schedule_info' => [
                        'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day' => $schedule->day,
                        'time_slot' => $schedule->time_slot
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserSchedules()
    {
        try {
            $userId = auth()->id();
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $schedules = Schedule::with(['course', 'class', 'room'])
                ->where('period_id', $activePeriod->period_id)
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->get()
                ->groupBy('day');

            $formattedSchedules = [];
            foreach ($schedules as $day => $daySchedules) {
                $formattedSchedules[$day] = $daySchedules->map(function($schedule) {
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                        'course_code' => $schedule->course->course_code ?? '',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'time_slot' => $schedule->time_slot,
                        'building_name' => $schedule->room->building->building_name ?? 'Unknown Building'
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'semester' => $activePeriod->semester_type,
                    'academic_year' => $activePeriod->academic_year,
                    'formatted_period' => $activePeriod->formatted_period,
                    'schedules' => $formattedSchedules
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user schedules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal'
            ], 500);
        }
    }

    public function getAvailableTimeSlots(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $activePeriod = SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $bookedSlots = Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day_of_week)
                ->where('period_id', $activePeriod->period_id)
                ->where('status', 'active')
                ->pluck('time_slot')
                ->toArray();

            $allTimeSlots = [
                '08.00 - 08:50',
                '08:50 - 09:40',
                '09:40 - 10:30',
                '10:30 - 11:20',
                '11:20 - 12:10',
                '12:10 - 13:00',
                '13:00 - 13:50',
                '13:50 - 14:40',
                '14:40 - 15:30',
                '15:30 - 16:20'
            ];

            $availableSlots = array_diff($allTimeSlots, $bookedSlots);

            return response()->json([
                'success' => true,
                'data' => [
                    'booked_slots' => $bookedSlots,
                    'available_slots' => array_values($availableSlots),
                    'all_slots' => $allTimeSlots
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting available time slots: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil slot waktu yang tersedia'
            ], 500);
        }
    }

    public function getUserCourses()
    {
        try {
            $userId = auth()->id();
            $courses = DB::table('user_courses')
                ->join('course_classes', 'user_courses.class_id', '=', 'course_classes.class_id')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->where('user_courses.user_id', $userId)
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'course_classes.class_id',
                    'course_classes.class_name'
                )
                ->get()
                ->groupBy('course_id')
                ->map(function($classes) {
                    $first = $classes->first();
                    return [
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'classes' => $classes->map(function($class) {
                            return [
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name
                            ];
                        })->values()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user courses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata kuliah'
            ], 500);
        }
    }

    public function book(Request $request)
    {
        return $this->store($request);
    }
}