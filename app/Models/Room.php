<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';

    protected $fillable = [
        'room_name',
        'location',
    ];

    // Relasi ke Schedules (room memiliki banyak jadwal)
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id', 'room_id');
    }

    // Relasi ke Logbooks (room memiliki banyak logbook)
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'room_id', 'room_id');
    }

    // Relasi ke ScheduleOverrides
    public function scheduleOverrides()
    {
        return $this->hasMany(ScheduleOverride::class, 'room_id', 'room_id');
    }

    // Scope untuk mencari ruangan berdasarkan nama
    public function scopeByName($query, $name)
    {
        return $query->where('room_name', 'like', '%' . $name . '%');
    }

    // Scope untuk mencari ruangan berdasarkan lokasi
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    public function occupancyStatus()
    {
        return $this->hasOne(RoomOccupancyStatus::class, 'room_id', 'room_id');
    }

    // Relasi ke QrCode (new table)
    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'room_id', 'room_id');
    }

    // Relasi ke RoomAccessLogs (new table)
    public function accessLogs()
    {
        return $this->hasMany(RoomAccessLog::class, 'room_id', 'room_id');
    }

    // Method untuk check status ruangan saat ini
    public function isCurrentlyActive()
    {
        return $this->occupancyStatus()
            ->where('is_active', true)
            ->exists();
    }

    // Method untuk get current user (aslab yang sedang menggunakan)
    public function getCurrentUser()
    {
        $status = $this->occupancyStatus()
            ->where('is_active', true)
            ->first();
        
        return $status ? $status->user : null;
    }

    // Scope untuk filter ruangan yang sedang digunakan
    public function scopeActive($query)
    {
        return $query->whereHas('occupancyStatus', function ($q) {
            $q->where('is_active', true);
        });
    }
}
