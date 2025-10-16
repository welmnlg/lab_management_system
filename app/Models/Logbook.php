<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'override_id',
        'room_id',
        'course_id',
        'date',
        'login',
        'logout',
        'activity',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'login' => 'datetime:H:i:s',
        'logout' => 'datetime:H:i:s',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Schedule (nullable)
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    // Relasi ke ScheduleOverride (nullable)
    public function scheduleOverride()
    {
        return $this->belongsTo(ScheduleOverride::class, 'override_id', 'id');
    }

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Relasi ke Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk filter berdasarkan ruangan
    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    // Accessor untuk mendapatkan durasi penggunaan ruangan
    public function getDurationAttribute()
    {
        if ($this->login && $this->logout) {
            $login = \Carbon\Carbon::parse($this->login);
            $logout = \Carbon\Carbon::parse($this->logout);
            return $login->diff($logout)->format('%H:%I:%S');
        }
        return null;
    }
}
