<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    /**
     * Primary key
     */
    protected $primaryKey = 'course_id';

    /**
     * Fillable
     */
    protected $fillable = [
        'course_code',
        'course_name',
        'semester',
        'program_id',
    ];

    public function courseClasses()
    {
        return $this->hasMany(CourseClass::class, 'course_id', 'course_id');
    }
    /**
     * Relasi ke model Program (belongsTo)
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }


    // Relasi ke Logbooks (course memiliki banyak logbook)
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'course_id', 'course_id');
    }
}