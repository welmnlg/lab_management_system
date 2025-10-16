<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; // pakai kunci primary custom

    protected $fillable = [
        'name',
        'email',
        'password',
        // tambahkan fields lain jika ada
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke user_courses (user punya banyak kelas praktikum)
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class, 'user_id', 'user_id');
    }

    // Relasi many-to-many ke roles lewat tabel pivot role_user
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    // Relasi ke Program studi untuk program user
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_studi', 'program_id');
    }

    // Relasi ke Logbooks (user memiliki banyak logbook)
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'user_id', 'user_id');
    }

    // Relasi ke Schedules (user memiliki banyak jadwal)
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'user_id');
    }

    // Relasi ke ScheduleOverrides
    public function scheduleOverrides()
    {
        return $this->hasMany(ScheduleOverride::class, 'user_id', 'user_id');
    }

    // Method untuk get active logbook (yang belum logout)
    public function getActiveLogbook()
    {
        return $this->logbooks()
                    ->whereDate('date', today())
                    ->whereNull('logout')
                    ->first();
    }

    // Method untuk check apakah user sedang menggunakan ruangan
    public function isUsingRoom()
    {
        return $this->getActiveLogbook() !== null;
    }

}
