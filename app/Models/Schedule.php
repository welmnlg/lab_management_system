<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'class_id',
        'user_id',
        'room_id',
        'day',
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

    // Relasi ke CourseClass
    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'class_id', 'class_id');
    }

    // Relasi ke User (instructor/asisten)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Relasi ke Logbooks
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'schedule_id', 'schedule_id');
    }

    // Relasi ke ScheduleOverrides
    public function overrides()
    {
        return $this->hasMany(ScheduleOverride::class, 'schedule_id', 'schedule_id');
    }

    // Scope untuk filter berdasarkan hari
    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    // Scope untuk filter berdasarkan ruangan
    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
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
