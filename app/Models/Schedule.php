<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'period_id',
        'user_id',
        'room_id',
        'course_id',
        'class_id',
        'day',
        'time_slot',
        'start_time',
        'end_time',
        'date',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Relationship with semester period
     */
    public function period()
    {
        return $this->belongsTo(SemesterPeriod::class, 'period_id', 'period_id');
    }
    // public function semesterPeriod()
    // {
    //     return $this->belongsTo(SemesterPeriod::class, 'period_id', 'period_id');
    // }

    /**
     * Relationship with user (lecturer)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship with room
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    /**
     * Relationship with building through room
     */
    public function building()
    {
        return $this->hasOneThrough(Building::class, Room::class, 'room_id', 'building_id', 'room_id', 'building_id');
    }

    /**
     * Relationship with course
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    /**
     * Relationship with class
     */
    public function class()
    {
        return $this->belongsTo(CourseClass::class, 'class_id', 'class_id');
    }

    /**
     * Scope for active period schedules
     */
    public function scopeActivePeriod($query)
    {
        return $query->whereHas('semesterPeriod', function($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Scope for specific room
     */
    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for active schedules only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get formatted schedule info
     */
    public function getFormattedInfoAttribute()
    {
        return [
            'schedule_id' => $this->schedule_id,
            'course_name' => $this->course->course_name ?? 'Unknown Course',
            'course_code' => $this->course->course_code ?? '',
            'lecturer_name' => $this->user->name ?? 'Unknown Lecturer',
            'class_name' => $this->class->class_name ?? '',
            'room_name' => $this->room->room_name ?? 'Unknown Room',
            'day_of_week' => $this->day,
            'time_slot' => $this->time_slot,
            'building_name' => $this->room->building->building_name ?? 'Unknown Building'
        ];
    }

    /**
     * Check if schedule is in conflict with another schedule
     */
    public static function hasConflict($roomId, $day, $timeSlot, $periodId, $excludeScheduleId = null)
    {
        $query = self::where('room_id', $roomId)
            ->where('day', $day)
            ->where('time_slot', $timeSlot)
            ->where('period_id', $periodId)
            ->where('status', 'active');

        if ($excludeScheduleId) {
            $query->where('schedule_id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }

    /**
     * Get day of week attribute (alias for compatibility)
     */
    public function getDayOfWeekAttribute()
    {
        return $this->day;
    }

    /**
     * Check if user can edit this schedule - FIXED VERSION
     */
   /**
     * Check if the current user can edit this schedule - FIXED VERSION
     * This method should be called from the controller to determine edit permissions.
     */
    public function canUserEdit($userId)
    {
        // Ambil periode aktif
        $activePeriod = $this->period; // Gunakan relasi yang sudah dimuat

        if (!$activePeriod) {
            \Log::warning('Schedule has no active period', ['schedule_id' => $this->schedule_id]);
            return false;
        }

        // Logika utama: Cek apakah user saat ini adalah pemilik jadwal
        $isOwner = (int) $this->user_id === (int) $userId;

        // Jika user adalah pemilik jadwal, cek status periode
        if ($isOwner) {
            // Pemilik bisa edit jika masih dalam masa pengambilan jadwal
            if ($activePeriod->is_schedule_taking_open) {
                return true;
            }
            // Atau jika BPH sudah membuka akses manual
            if ($activePeriod->is_schedule_open && $activePeriod->isUserAllowed($userId)) {
                return true;
            }
            // Jika tidak, maka tidak bisa edit
            return false;
        }

                // Jika bukan pemilik, hanya bisa edit jika BPH telah memberi izin khusus
        if ($activePeriod->is_schedule_open && $activePeriod->isUserAllowed($userId)) {
            return true;
        }

        // Default: tidak bisa edit
        return false;
    }

    /**
     * Get schedule owner user ID
     */
    public function getScheduleUserIdAttribute()
    {
        return $this->user_id;
    }

    /**
     * Get schedule owner name
     */
    public function getScheduleUserNameAttribute()
    {
        return $this->user->name ?? 'Unknown User';
    }
}