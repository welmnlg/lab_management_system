<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'course_id';

    public function courseClasses()
    {
        return $this->hasMany(CourseClass::class, 'course_id', 'course_id');
    }
    // Relasi ke Logbooks (course memiliki banyak logbook)
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'course_id', 'course_id');
    }
}