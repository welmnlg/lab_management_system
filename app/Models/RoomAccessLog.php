<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'scan_time',
        'validation_status',
        'entry_time',
        'exit_time',
        'notes'
    ];

    protected $casts = [
        'scan_time' => 'datetime',
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Scope untuk get success logs
    public function scopeSuccess($query)
    {
        return $query->where('validation_status', 'success');
    }

    // Scope untuk get failed logs
    public function scopeFailed($query)
    {
        return $query->where('validation_status', 'failed');
    }

    // Scope untuk get today logs
    public function scopeToday($query)
    {
        return $query->whereDate('scan_time', today());
    }

    // Scope untuk get by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk get by room
    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    // Get duration jika sudah exit
    public function getDurationAttribute()
    {
        if ($this->entry_time && $this->exit_time) {
            $entry = \Carbon\Carbon::parse($this->entry_time);
            $exit = \Carbon\Carbon::parse($this->exit_time);
            return $entry->diff($exit)->format('%H:%I:%S');
        }

        return null;
    }

    // Check apakah masih pending
    public function isPending()
    {
        return $this->validation_status === 'pending';
    }

    // Check apakah berhasil
    public function isSuccess()
    {
        return $this->validation_status === 'success';
    }

    // Check apakah gagal
    public function isFailed()
    {
        return $this->validation_status === 'failed';
    }
}
