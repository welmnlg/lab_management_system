<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    // Migration uses default id() which creates 'id' column
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'faculty'];

    public function users()
    {
        return $this->hasMany(User::class, 'program_studi', 'id');
    }
}