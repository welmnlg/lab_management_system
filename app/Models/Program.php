<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $primaryKey = 'program_id';

    public function users()
    {
        return $this->hasMany(User::class, 'program_studi', 'program_id');
    }
}