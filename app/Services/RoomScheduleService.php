<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleOverride;
use App\Models\CourseClass;
use App\Models\SemesterPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class RoomScheduleService
{
    /**
     * Get weekly calendar for a specific room
     * 
     * @param int $roomId
     * @param string $weekStart Format: Y-m-d (Monday of the week)
     * @return array
     */
    public function getWeeklyCalendar($roomId, $weekStart)
    {
        $room = Room::find($roomId);
        
        if (!$room) {
            throw new \Exception('Room not found');
        }
        
        // Parse week start
        $monday = Carbon::parse($weekStart)->startOfWeek(Carbon::MONDAY);
        $friday = $monday->copy()->addDays(4); // Friday
        
        // Get all schedules for this room - ONLY dari semester aktif
        $regularSchedules = Schedule::where('room_id', $roomId)
            ->whereHas('period', function($query) {
                $query->where('is_active', true);
            })
            ->with(['class.course', 'user', 'period'])  // Fixed: use class instead of courseClass
            ->get();
        
        // Get schedule overrides for this week
        $overrides = ScheduleOverride::where('room_id', $roomId)
            ->whereBetween('date', [$monday->format('Y-m-d'), $friday->format('Y-m-d')])
            ->with(['courseClass.course', 'user'])  // Fixed: ScheduleOverride uses 'courseClass' relationship
            ->get();
        
        // Build calendar structure
        $calendar = $this->buildWeeklyCalendar($regularSchedules, $overrides, $monday);
        
        return [
            'room' => [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'location' => $room->location
            ],
            'week_start' => $monday->format('d F Y'),
            'week_end' => $friday->format('d F Y'),
            'week_start_date' => $monday->format('Y-m-d'),
            'week_end_date' => $friday->format('Y-m-d'),
            'calendar' => $calendar
        ];
    }
    
    /**
     * Build weekly calendar structure
     */
    private function buildWeeklyCalendar($regularSchedules, $overrides, $monday)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $timeSlots = [
            '08:00 - 09:40' => ['start' => '08:00:00', 'end' => '09:40:00'],
            '09:40 - 11:20' => ['start' => '09:40:00', 'end' => '11:20:00'],
            '11:20 - 13:00' => ['start' => '11:20:00', 'end' => '13:00:00'],
            '13:00 - 14:40' => ['start' => '13:00:00', 'end' => '14:40:00'],
            '14:40 - 16:20' => ['start' => '14:40:00', 'end' => '16:20:00'],
        ];
        
        $calendar = [];
        
        foreach ($days as $index => $day) {
            $currentDate = $monday->copy()->addDays($index);
            
            $calendar[$day] = [
                'date' => $currentDate->format('Y-m-d'),
                'schedules' => []
            ];
            
            // Get regular schedules for this day
            $daySchedules = $regularSchedules->where('day', $day);
            
            // Get overrides for this specific date
            // Fix: Compare formatted date string to avoid Carbon object vs String mismatch
            $dayOverrides = $overrides->filter(function ($override) use ($currentDate) {
                return $override->date->format('Y-m-d') === $currentDate->format('Y-m-d');
            });
            
            /// Process regular schedules
            foreach ($daySchedules as $schedule) {
                $timeSlot = $this->formatTimeSlot($schedule->start_time, $schedule->end_time);
                
                // ✅ Check if override exists for this schedule
                $override = $dayOverrides->where('schedule_id', $schedule->schedule_id)->first();
                
                if ($override && $override->start_time && $override->end_time) {
                    // ✅ Use override time, but keep course info from original schedule
                    $overrideTimeSlot = $this->formatTimeSlot($override->start_time, $override->end_time);
                    $calendar[$day]['schedules'][] = $this->formatScheduleForCalendar($schedule, $overrideTimeSlot, true);
                } else {
                    // Use regular schedule
                    $calendar[$day]['schedules'][] = $this->formatScheduleForCalendar($schedule, $timeSlot, false);
                }
            }
            
            // Add standalone overrides (overrides without schedule_id)
            // Fix: Ensure we check for null schedule_id explicitly
            foreach ($dayOverrides as $override) {
                if ($override->schedule_id === null) {
                    $timeSlot = $this->formatTimeSlot(
                        $override->start_time ?? '00:00:00',
                        $override->end_time ?? '00:00:00'
                    );
                    $calendar[$day]['schedules'][] = $this->formatScheduleForCalendar($override, $timeSlot, true);
                }
            }
        }
        
        return $calendar;
    }
    
    /**
     * Format schedule for calendar display
     */
    private function formatScheduleForCalendar($schedule, $timeSlot, $isOverride = false)
    {
        // FIXED: Schedule model uses 'class' while ScheduleOverride uses 'courseClass'
        if ($isOverride) {
            $courseClass = $schedule->courseClass;
        } else {
            $courseClass = $schedule->class;
        }
        
        $course = $courseClass ? $courseClass->course : null;
        $user = $schedule->user ?? null;
        
        return [
            'time_slot' => $timeSlot,
            'start_time' => substr($schedule->start_time ?? '00:00:00', 0, 5),
            'end_time' => substr($schedule->end_time ?? '00:00:00', 0, 5),
            'course_name' => $course ? $course->course_name : 'N/A',
            'course_code' => $course ? $course->course_code : null,
            'class_name' => $courseClass ? $courseClass->class_name : 'N/A',
            'instructor' => $user ? $user->name : 'N/A',
            'is_override' => $isOverride,
            'reason' => $isOverride && isset($schedule->reason) ? $schedule->reason : null,
            'status' => $schedule->status ?? null
        ];
    }
    
    /**
     * Format time slot string
     */
    private function formatTimeSlot($startTime, $endTime)
    {
        $start = $startTime ?? '00:00:00';
        $end = $endTime ?? '00:00:00';
        
        return substr($start, 0, 5) . ' - ' . substr($end, 0, 5);
    }
    
    
    /**
     * Get all rooms with current status
     */
    public function getAllRoomsStatus()
    {
        $rooms = Room::all();
        $now = Carbon::now();
        $currentDay = $this->getIndonesianDay($now);
        $currentTime = $now->format('H:i:s');
        
        return $rooms->map(function($room) use ($currentDay, $currentTime, $now) {
            // ✅ PERBAIKAN: CEK OCCUPANCY STATUS DULU!
            $occupancy = \App\Models\RoomOccupancyStatus::where('room_id', $room->room_id)
                ->where('is_active', true)
                ->first();
            
            // ✅ Jika ada occupancy aktif, ruangan PASTI sedang digunakan (hijau)
            if ($occupancy) {
                // Ambil data user yang sedang pakai
                $user = \App\Models\User::find($occupancy->current_user_id);
                
                // Cari schedule yang sedang berjalan - ONLY dari semester aktif
                $currentSchedule = Schedule::where('room_id', $room->room_id)
                    ->where('day', $currentDay)
                    ->where('start_time', '<=', $currentTime)
                    ->where('end_time', '>=', $currentTime)
                    ->where('user_id', $occupancy->current_user_id)
                    ->whereHas('period', function($query) {
                        $query->where('is_active', true);
                    })
                    ->with(['class.course', 'user'])  // Fixed: Schedule uses 'class' relationship
                    ->first();
                
                // Cek override juga
                $override = ScheduleOverride::where('room_id', $room->room_id)
                    ->where('date', $now->format('Y-m-d'))
                    ->where('start_time', '<=', $currentTime)
                    ->where('end_time', '>=', $currentTime)
                    ->where('user_id', $occupancy->current_user_id)
                    ->with(['courseClass.course', 'user'])
                    ->first();
                
                $activeSchedule = $override ?? $currentSchedule;
                
                return [
                    'room_id' => $room->room_id,
                    'room_name' => $room->room_name,
                    'location' => $room->location,
                    'status' => 'occupied', // ✅ STATUS: DIGUNAKAN (hijau)
                    'current_schedule' => $activeSchedule ? $this->formatCurrentSchedule($activeSchedule) : null,
                    'current_user' => $user ? $user->name : 'Unknown'
                ];
            }
            
            // ✅ Jika TIDAK ada occupancy, cek apakah ada jadwal - ONLY dari semester aktif
            $currentSchedule = Schedule::where('room_id', $room->room_id)
                ->where('day', $currentDay)
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->whereHas('period', function($query) {
                    $query->where('is_active', true);
                })
                ->with(['class.course', 'user'])  // Fixed: Schedule uses 'class' relationship
                ->first();
            
            $override = ScheduleOverride::where('room_id', $room->room_id)
                ->where('date', $now->format('Y-m-d'))
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->with(['courseClass.course', 'user'])
                ->first();
            
            $activeSchedule = $override ?? $currentSchedule;
            
            // ✅ Ada jadwal TAPI belum ada occupancy = available (abu-abu)
            return [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'location' => $room->location,
                'status' => 'available', // ✅ STATUS: KOSONG (abu-abu)
                'current_schedule' => $activeSchedule ? $this->formatCurrentSchedule($activeSchedule) : null
            ];
        });
    }

    
    /**
     * Format current schedule
     */
    private function formatCurrentSchedule($schedule)
    {
        // FIXED: Detect model type and use correct relationship
        $isOverride = get_class($schedule) === 'App\Models\ScheduleOverride';
        
        if ($isOverride) {
            $courseClass = $schedule->courseClass;
        } else {
            $courseClass = $schedule->class;
        }
        
        $course = $courseClass ? $courseClass->course : null;
        $user = $schedule->user;
        
        return [
            'course_name' => $course ? $course->course_name : 'N/A',
            'instructor' => $user ? $user->name : 'N/A',
            'start_time' => substr($schedule->start_time, 0, 5),
            'end_time' => substr($schedule->end_time, 0, 5)
        ];
    }
    
    /**
     * Get Indonesian day name
     */
    private function getIndonesianDay($date)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $days[$date->format('l')] ?? 'Senin';
    }
    
    /**
     * Get room schedules for a specific day
     */
    public function getRoomSchedulesForDay($roomId, $date)
    {
        $room = Room::find($roomId);
        
        if (!$room) {
            throw new \Exception('Room not found');
        }
        
        $dateCarbon = Carbon::parse($date);
        $day = $this->getIndonesianDay($dateCarbon);
        
        // Get regular schedules - ONLY dari semester aktif
        $schedules = Schedule::where('room_id', $roomId)
            ->where('day', $day)
            ->whereHas('period', function($query) {
                $query->where('is_active', true);
            })
            ->with(['class.course', 'user', 'period'])  // Fixed: Schedule uses 'class' relationship
            ->get();
        
        // Get overrides for this date
        $overrides = ScheduleOverride::where('room_id', $roomId)
            ->where('date', $dateCarbon->format('Y-m-d'))
            ->whereIn('status', ['active', 'dikonfirmasi', 'pindah_ruangan', 'sedang_berlangsung', 'selesai'])
            ->with(['courseClass.course', 'user'])
            ->get();
        
        // Merge and format
        $formattedSchedules = [];
        
        $processedOverrideIds = [];
        
        foreach ($schedules as $schedule) {
            // Cek apakah ada override untuk schedule ini di ruangan ini (misal ganti jam di ruangan sama)
            $override = $overrides->where('schedule_id', $schedule->schedule_id)->first();
            
            if ($override) {
                // Jika ada override, gunakan override
                $formattedSchedules[] = $this->formatSchedule($override, $dateCarbon, true);
                $processedOverrideIds[] = $override->id;
            } else {
                // Jika tidak ada, gunakan jadwal asli
                $formattedSchedules[] = $this->formatSchedule($schedule, $dateCarbon, false);
            }
        }
        
        // Tambahkan override yang belum diproses (misal pindahan dari ruangan lain atau jadwal baru)
        foreach ($overrides as $override) {
            if (!in_array($override->id, $processedOverrideIds)) {
                $formattedSchedules[] = $this->formatSchedule($override, $dateCarbon, true);
            }
        }
        
        // Sort by start time
        usort($formattedSchedules, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });
        
        return [
            'room' => [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'location' => $room->location
            ],
            'date' => $dateCarbon->format('d F Y'),
            'day' => $day,
            'schedules' => $formattedSchedules,
            'total_schedules' => count($formattedSchedules)
        ];
    }
    
    /**
 * Format schedule
 */
private function formatSchedule($schedule, $date, $isOverride)
{
    // FIXED: Schedule model uses 'class' while ScheduleOverride uses 'courseClass'
    if ($isOverride) {
        // ScheduleOverride uses courseClass relationship
        $courseClass = $schedule->courseClass;
    } else {
        // Schedule uses class relationship
        $courseClass = $schedule->class;
    }
    
    $course = $courseClass ? $courseClass->course : null;
    $user = $schedule->user;
    
    // Jika override tidak punya class_id, ambil dari schedule asli
    if ($isOverride && !$courseClass && $schedule->schedule_id) {
        $originalSchedule = Schedule::with(['class.course'])->find($schedule->schedule_id);
        if ($originalSchedule) {
            $courseClass = $originalSchedule->class;
            $course = $courseClass ? $courseClass->course : null;
        }
    }
        
        // Pastikan start_time dan end_time dalam format yang benar
        $startTime = $schedule->start_time;
        $endTime = $schedule->end_time;
        
        // Jika waktu adalah string lengkap dengan format H:i:s, ambil 5 karakter pertama
        if (is_string($startTime) && strlen($startTime) >= 5) {
            $startTime = substr($startTime, 0, 5);
        }
        
        if (is_string($endTime) && strlen($endTime) >= 5) {
            $endTime = substr($endTime, 0, 5);
        }
        
        $status = $this->determineScheduleStatus(
            $schedule->start_time, 
            $schedule->end_time, 
            $date,
            $schedule
        );
        
        return [
            'id' => $isOverride ? $schedule->id : $schedule->schedule_id,
            'course_name' => $course ? $course->course_name : 'N/A',
            'course_code' => $course ? $course->course_code : null,
            'class_name' => $courseClass ? $courseClass->class_name : 'N/A',
            'instructor' => $user ? $user->name : 'N/A',
            'start_time' => $startTime,
            'end_time' => $endTime,
            'date' => $date->format('d F Y'),
            'day' => $this->getIndonesianDay($date),
            'status' => $status,
            'is_override' => $isOverride
        ];
    }
    
    /**
     * Determine schedule status
     */
    private function determineScheduleStatus($startTime, $endTime, $date, $schedule = null)
    {
        $now = Carbon::now();
        $scheduleDate = Carbon::parse($date);
        $currentTime = $now->format('H:i:s');
        
        // PERBAIKAN 1: Cek apakah hari ini
        if (!$scheduleDate->isToday()) {
            // Jika sudah lewat, status completed
            if ($scheduleDate->isPast()) {
                // Check if it was moved
                if ($schedule && $schedule->status === 'pindah_ruangan') {
                    return 'moved';
                }
                return 'completed';
            }
            // Jika belum tiba, status scheduled
            return 'scheduled';
        }

        // PERBAIKAN 1.5: Cek status dari database untuk schedule dan override yang sudah selesai
        if ($schedule) {
            // Check database status for both Schedule and ScheduleOverride
            if ($schedule->status === 'selesai') {
                return 'completed';
            }
            if ($schedule->status === 'sedang_berlangsung') {
                return 'ongoing';
            }
            
            // Cek status Pindah Ruangan (Moved)
            if ($schedule->status === 'pindah_ruangan') {
                return 'moved';
            }
        }
        
        // ✅ PERBAIKAN 2: Untuk hari ini, CEK OCCUPANCY dulu
        if ($schedule) {
            $roomId = $schedule->room_id;
            $userId = $schedule->user_id;
            
            $isCurrentTimeInThisSchedule = ($currentTime >= $startTime && $currentTime <= $endTime);

            // Cek apakah ada occupancy aktif untuk ruangan dan user ini
            $occupancy = \App\Models\RoomOccupancyStatus::where('room_id', $roomId)
                ->where('is_active', true)
                ->where('current_user_id', $userId)
                ->where('schedule_id', $schedule->schedule_id)
                ->first();
            
            
            
            // ✅ JIKA ADA OCCUPANCY AKTIF (sudah scan QR + confirm)
            if ($isCurrentTimeInThisSchedule) {
                // ✅ SEKARANG dalam rentang jam jadwal ini
                // Cek apakah ada occupancy aktif untuk user ini di room ini
                $occupancy = \App\Models\RoomOccupancyStatus::where('room_id', $roomId)
                    ->where('current_user_id', $userId)
                    ->where('is_active', true)
                    ->where('schedule_id', $schedule->schedule_id)
                    ->first();
                
                // ✅ Jika ada occupancy untuk user ini → ongoing
                if ($occupancy) {
                    return 'ongoing'; // Sedang berlangsung
                }
                
                // ✅ Jika TIDAK ada occupancy → scheduled (belum scan)
                return 'scheduled';
            }
            
            // ✅ JIKA BELUM ADA OCCUPANCY (belum scan QR)
                // ✅ SEKARANG TIDAK dalam rentang jam jadwal ini
            if ($currentTime > $endTime) {
                return 'completed'; // Jadwal ini sudah selesai
            } else {
                return 'scheduled'; // Jadwal ini belum dimulai
            }
        }
        
        // ✅ Fallback jika tidak ada schedule object
        if ($currentTime > $endTime) {
            return 'completed';
        } elseif ($currentTime >= $startTime) {
            return 'scheduled'; // Default scheduled jika belum ada konfirmasi
        } else {
            return 'scheduled';
        }
    }


        /**
         * Check schedule conflict with improved logic
         * Allows same class to change time, but prevents overlapping with other classes
         */
        public function hasScheduleConflict($roomId, $date, $startTime, $endTime, $classId = null, $excludeOverrideId = null)
        {
            // Convert date to Carbon instance
            $dateObj = Carbon::parse($date);
            
            // Get Indonesian day name
            $dayName = $this->getIndonesianDay($dateObj);
            
            // Check regular schedule conflict
            // ✅ ONLY conflict if it's a DIFFERENT class
            $hasRegularConflict = Schedule::where('room_id', $roomId)
                ->where('day', $dayName)
                ->when($classId, function ($query, $id) {
                    // Skip if same class (allow same class to reschedule)
                    $query->where('class_id', '!=', $id);
                })
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function($q) use ($startTime, $endTime) {
                        // Check if times overlap
                        $q->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q2) use ($startTime, $endTime) {
                            $q2->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                    });
                })
                ->exists();

            // Check override schedule conflict
            // ✅ ONLY conflict if it's a DIFFERENT class
            $hasOverrideConflict = ScheduleOverride::where('room_id', $roomId)
                ->where('date', $dateObj->format('Y-m-d'))
                ->when($classId, function ($query, $id) {
                    // Skip if same class (allow same class to reschedule)
                    $query->where('class_id', '!=', $id);
                })
                ->when($excludeOverrideId, function ($query, $id) {
                    $query->where('id', '!=', $id);
                })
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function($q) use ($startTime, $endTime) {
                        // Check if times overlap
                        $q->whereBetween('start_time', [$startTime, $endTime])
                        ->orWhereBetween('end_time', [$startTime, $endTime])
                        ->orWhere(function ($q2) use ($startTime, $endTime) {
                            $q2->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                    });
                })
                ->exists();

            return $hasRegularConflict || $hasOverrideConflict;
        }
    }