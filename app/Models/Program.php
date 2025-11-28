<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'programs';

    protected $fillable = [
        'name',
        'faculty',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'program_studi', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'program_id', 'id');
    }
}