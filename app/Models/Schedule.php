<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',           // ← ADD
        'confirmed_at',     // ← ADD
        'cancelled_at',     // ← ADD
        'started_at',       // ← ADD
        'completed_at',     // ← ADD
        'moved_at',         // ← ADD
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
        'confirmed_at' => 'datetime',   // ← ADD
        'cancelled_at' => 'datetime',   // ← ADD
        'started_at' => 'datetime',     // ← ADD
        'completed_at' => 'datetime',   // ← ADD
        'moved_at' => 'datetime',       // ← ADD
    ];

    // ✅ Status Validation Methods
    public function canConfirm(): bool
    {
        // Can confirm only if status is 'terjadwal'
        return $this->status === 'terjadwal';
    }

    public function canScanQR(): bool
    {
        // Can scan only if confirmed or pindah_ruangan
        return in_array($this->status, ['dikonfirmasi', 'pindah_ruangan']);
    }

    public function hasExpiredConfirmationWindow(): bool
    {
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($this->start_time);
        
        // If more than 15 minutes after start time
        return $now->diffInMinutes($startTime, false) > 15;
    }

    public function isConfirmationWindowOpen(): bool
    {
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($this->start_time);
        
        // 1 hour before start until 15 min after start
        $oneHourBefore = $startTime->copy()->subHours(1);
        $fifteenMinAfter = $startTime->copy()->addMinutes(15);
        
        return $now->isBetween($oneHourBefore, $fifteenMinAfter);
    }

    /**
     * Relationship with semester period
     */
    public function period()
    {
        return $this->belongsTo(SemesterPeriod::class, 'period_id', 'period_id');
    }

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
    // public function building()
    // {
    //     return $this->hasOneThrough(Building::class, Room::class, 'room_id', 'building_id', 'room_id', 'building_id');
    // }

    /**
     * Relationship with class
     */
    public function class()
    {
        return $this->belongsTo(CourseClass::class, 'class_id', 'class_id');
    }

    /**
     * Relationship with course (via class)
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            CourseClass::class,
            'class_id',      // Foreign key on CourseClass table
            'course_id',     // Foreign key on Course table
            'class_id',      // Local key on Schedule table
            'course_id'      // Local key on CourseClass table
        );
    }

    /**
     * Scope for active period schedules
     */
    public function scopeActivePeriod($query)
    {
        return $query->whereHas('period', function($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Relationship dengan notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'schedule_id', 'schedule_id');
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

    public function isActiveNow()
    {
        $now = \Carbon\Carbon::now();
        $currentDay = $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');
        
        $startTime = \Carbon\Carbon::parse($this->start_time)->format('H:i:s');
        $endTime = \Carbon\Carbon::parse($this->end_time)->format('H:i:s');
        $bufferTime = \Carbon\Carbon::parse($this->start_time)
            ->subMinutes(15)->format('H:i:s');
        
        return $this->day_of_week == $currentDay 
            && $currentTime >= $bufferTime 
            && $currentTime <= $endTime;
    }

    // Check apakah schedule valid untuk scan pada waktu tertentu (dengan buffer 15 menit)
public function isValidForScan($dateTime = null)
{
    if (!$dateTime) {
        $dateTime = \Carbon\Carbon::now();
    }
    
    $dayOfWeek = $dateTime->dayOfWeek;
    $currentTime = $dateTime->format('H:i:s');
    
    // Convert day enum ke number jika diperlukan
    $scheduleDayNumber = $this->convertDayToNumber($this->day);
    
    if ($scheduleDayNumber != $dayOfWeek) {
        return false;
    }
    
    $startTime = \Carbon\Carbon::parse($this->start_time)->format('H:i:s');
    $endTime = \Carbon\Carbon::parse($this->end_time)->format('H:i:s');
    $bufferTime = \Carbon\Carbon::parse($this->start_time)
        ->subMinutes(15)->format('H:i:s');
    
    return $currentTime >= $bufferTime && $currentTime <= $endTime;
}

    // Helper convert hari ke number
    private function convertDayToNumber($day)
    {
        $days = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 0
        ];
        
        return $days[$day] ?? null;
    }

    // Get formatted day name
    public function getDayName()
    {
        return $this->day;
    }

    // Get formatted time range
    public function getTimeRange()
    {
        return $this->start_time . ' - ' . $this->end_time;
    }
}
