<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'room_id',
        'encrypted_token',
        'qr_image_path',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    // Scope untuk get active QR codes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get URL yang di-embed di QR code
    public function getQrUrl()
    {
        return route('lab.qr-verify') . '?token=' . urlencode($this->encrypted_token);
    }
}
