<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomOccupancyStatus extends Model
{
    protected $table = 'room_occupancy_status';
    protected $primaryKey = 'occupancy_id';
    
    protected $fillable = [
        'room_id',
        'current_user_id',
        'schedule_id',
        'is_active',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Relasi ke User (current user yang sedang menggunakan)
    public function user()
    {
        return $this->belongsTo(User::class, 'current_user_id', 'user_id');
    }

    // Scope untuk get active rooms
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    // Scope untuk get empty rooms
    public function scopeEmpty($query)
    {
        return $query->where('is_active', false);
    }

    // Get duration penggunaan (jika masih active)
    public function getCurrentDuration()
    {
        if ($this->started_at) {
            $start = \Carbon\Carbon::parse($this->started_at);
            $now = \Carbon\Carbon::now();
            return $start->diff($now)->format('%H:%I:%S');
        }

        return null;
    }

    // Get duration penggunaan (jika sudah selesai)
    public function getTotalDuration()
    {
        if ($this->started_at && $this->ended_at) {
            $start = \Carbon\Carbon::parse($this->started_at);
            $end = \Carbon\Carbon::parse($this->ended_at);
            return $start->diff($end)->format('%H:%I:%S');
        }

        return null;
    }
}
