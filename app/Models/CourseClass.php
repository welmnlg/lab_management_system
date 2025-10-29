<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    /**
     * Primary key
     */
    protected $primaryKey = 'class_id';

    /**
     * Fillable
     */
    protected $fillable = [
        'course_id',
        'class_name',
        'lecturer',
    ];

        /**
     * Relasi ke model Course (belongsTo)
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class, 'class_id', 'class_id');
    }
    public function schedules()
    {
    return $this->hasMany(Schedule::class, 'class_id', 'class_id');
    }
}