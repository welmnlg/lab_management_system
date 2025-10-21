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
}
