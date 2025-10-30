<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'room_id',
        'date',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    // Relasi ke Schedule (original schedule yang di-override)
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Room (nullable)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Relasi ke CourseClass
    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'class_id', 'class_id');
    }

    // Relasi ke Logbooks
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'override_id', 'id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    // Scope untuk filter berdasarkan schedule
    public function scopeBySchedule($query, $scheduleId)
    {
        return $query->where('schedule_id', $scheduleId);
    }
}
