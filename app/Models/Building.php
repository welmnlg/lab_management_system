<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings';
    protected $primaryKey = 'building_id';

    protected $fillable = [
        'building_name',
        'building_code'
    ];

    /**
     * Relationship with rooms
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'building_id', 'building_id');
    }

    /**
     * Get formatted building name
     */
    public function getFormattedNameAttribute()
    {
        return "{$this->building_name} ({$this->building_code})";
    }
}