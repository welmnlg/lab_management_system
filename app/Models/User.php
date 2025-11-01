<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Primary key custom
     */
    protected $primaryKey = 'user_id';

    /**
     * Route key name untuk route model binding
     */
    public function getRouteKeyName()
    {
        return 'user_id';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'program_studi',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke user_courses (user punya banyak kelas praktikum)
     */
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class, 'user_id', 'user_id');
    }

    /**
     * Relasi many-to-many ke roles lewat tabel pivot role_user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Relasi ke Program studi untuk program user
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_studi', 'program_id');
    }

    /**
     * Relasi ke Logbooks (user memiliki banyak logbook)
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Schedules (user memiliki banyak jadwal)
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke ScheduleOverrides
     */
    public function scheduleOverrides()
    {
        return $this->hasMany(ScheduleOverride::class, 'user_id', 'user_id');
    }

    /**
     * Method untuk get active logbook (yang belum logout)
     */
    public function getActiveLogbook()
    {
        return $this->logbooks()
                    ->whereDate('date', today())
                    ->whereNull('logout')
                    ->first();
    }

    /**
     * Method untuk check apakah user sedang menggunakan ruangan
     */
    public function isUsingRoom()
    {
        return $this->getActiveLogbook() !== null;
    }

    /**
     * Helper method untuk check apakah user memiliki role tertentu
     * 
     * @param string $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('status', strtolower($roleName))->exists();
    }

    /**
     * Helper method untuk get role pertama user
     * 
     * @return string|null
     */
    public function getRole()
    {
        $role = $this->roles()->first();
        return $role ? $role->status : null;
    }

    // Relasi ke RoomAccessLogs (new table)
    public function roomAccessLogs()
    {
        return $this->hasMany(RoomAccessLog::class, 'user_id', 'user_id');
    }

    // Relasi ke RoomOccupancyStatus (untuk current active session)
    public function currentRoomSession()
    {
        return $this->hasOne(RoomOccupancyStatus::class, 'current_user_id', 'user_id')
            ->where('is_active', true);
    }
}