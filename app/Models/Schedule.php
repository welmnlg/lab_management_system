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
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time' => 'string',
    ];

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
}
