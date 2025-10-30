<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleOverride;
use App\Models\CourseClass;
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
        
        // Get all schedules for this room
        $regularSchedules = Schedule::where('room_id', $roomId)
            ->with(['courseClass.course', 'user'])
            ->get();
        
        // Get schedule overrides for this week
        $overrides = ScheduleOverride::where('room_id', $roomId)
            ->whereBetween('date', [$monday->format('Y-m-d'), $friday->format('Y-m-d')])
            ->with(['courseClass.course', 'user'])
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
            $dayOverrides = $overrides->where('date', $currentDate->format('Y-m-d'));
            
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
            
            // // Add standalone overrides (overrides without schedule_id)
            // foreach ($dayOverrides->where('schedule_id', null) as $override) {
            //     $timeSlot = $this->formatTimeSlot(
            //         $override->start_time ?? '00:00:00',
            //         $override->end_time ?? '00:00:00'
            //     );
            //     $calendar[$day]['schedules'][] = $this->formatScheduleForCalendar($override, $timeSlot, true);
            // }
        }
        
        return $calendar;
    }
    
    /**
     * Format schedule for calendar display
     */
    private function formatScheduleForCalendar($schedule, $timeSlot, $isOverride = false)
    {
        $courseClass = $schedule->courseClass;
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
            'reason' => $isOverride && isset($schedule->reason) ? $schedule->reason : null
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
            // Check if room is currently occupied
            $currentSchedule = Schedule::where('room_id', $room->room_id)
                ->where('day', $currentDay)
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->with(['courseClass.course', 'user'])
                ->first();
            
            // Check for override on today
            $override = ScheduleOverride::where('room_id', $room->room_id)
                ->where('date', $now->format('Y-m-d'))
                ->where('start_time', '<=', $currentTime)
                ->where('end_time', '>=', $currentTime)
                ->with(['courseClass.course', 'user'])
                ->first();
            
            $activeSchedule = $override ?? $currentSchedule;
            
            return [
                'room_id' => $room->room_id,
                'room_name' => $room->room_name,
                'location' => $room->location,
                'status' => $activeSchedule ? 'occupied' : 'available',
                'current_schedule' => $activeSchedule ? $this->formatCurrentSchedule($activeSchedule) : null
            ];
        });
    }
    
    /**
     * Format current schedule
     */
    private function formatCurrentSchedule($schedule)
    {
        $courseClass = $schedule->courseClass;
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
        
        // Get regular schedules
        $schedules = Schedule::where('room_id', $roomId)
            ->where('day', $day)
            ->with(['courseClass.course', 'user'])
            ->get();
        
        // Get overrides for this date
        $overrides = ScheduleOverride::where('room_id', $roomId)
            ->where('date', $date)
            ->with(['courseClass.course', 'user'])
            ->get();
        
        // Merge and format
        $formattedSchedules = [];
        
        foreach ($schedules as $schedule) {
            $override = $overrides->where('schedule_id', $schedule->schedule_id)->first();
            
            if (!$override) {
                $formattedSchedules[] = $this->formatSchedule($schedule, $dateCarbon, false);
            }
        }
        
        foreach ($overrides as $override) {
            $formattedSchedules[] = $this->formatSchedule($override, $dateCarbon, true);
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
        $courseClass = $schedule->courseClass;
        $course = $courseClass ? $courseClass->course : null;
        $user = $schedule->user;
        
        $status = $this->determineScheduleStatus($schedule->start_time, $schedule->end_time, $date);
        
        return [
            'id' => $isOverride ? $schedule->id : $schedule->schedule_id,
            'course_name' => $course ? $course->course_name : 'N/A',
            'course_code' => $course ? $course->course_code : null,
            'class_name' => $courseClass ? $courseClass->class_name : 'N/A',
            'instructor' => $user ? $user->name : 'N/A',
            'start_time' => substr($schedule->start_time, 0, 5),
            'end_time' => substr($schedule->end_time, 0, 5),
            'date' => $date->format('d F Y'),
            'day' => $this->getIndonesianDay($date),
            'status' => $status,
            'is_override' => $isOverride
        ];
    }
    
    /**
     * Determine schedule status
     */
        private function determineScheduleStatus($startTime, $endTime, $date)
        {
            $now = Carbon::now();
            $scheduleDate = Carbon::parse($date);
            
            if ($scheduleDate->isToday()) {
                $currentTime = $now->format('H:i:s');
                
                if ($currentTime >= $startTime && $currentTime <= $endTime) {
                    return 'ongoing';
                } elseif ($currentTime > $endTime) {
                    return 'completed';
                } else {
                    return 'scheduled';
                }
            } elseif ($scheduleDate->isPast()) {
                return 'completed';
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