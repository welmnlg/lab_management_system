<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    protected $primaryKey = 'class_id';
    protected $fillable = ['course_id', 'class_name', 'lecturer'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    // Relasi ke class_types
    public function classType()
    {
        return $this->belongsTo(ClassType::class, 'class_name', 'class_name');
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class, 'class_id', 'class_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id', 'class_id');
    }

    // Accessor untuk tampilkan "Kom A1"
    public function getDisplayClassNameAttribute()
    {
        return 'Kom ' . $this->class_name;
    }
}