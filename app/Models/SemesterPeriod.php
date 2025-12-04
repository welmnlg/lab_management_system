<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SemesterPeriod extends Model
{
    use HasFactory;

    protected $table = 'semester_periods';
    protected $primaryKey = 'period_id';

    protected $fillable = [
        'semester_type',
        'academic_year',
        'start_date',
        'end_date',
        'is_active',
        'schedule_start_date',
        'schedule_end_date',
        'is_schedule_open',
        'allowed_users'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule_start_date' => 'date',
        'schedule_end_date' => 'date',
        'is_active' => 'boolean',
        'is_schedule_open' => 'boolean',
        'allowed_users' => 'array'
    ];

    /**
     * Get the active semester period
     */
    public static function getActivePeriod()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get formatted period string
     */
    public function getFormattedPeriodAttribute()
    {
        return "{$this->semester_type} {$this->academic_year}";
    }

    /**
     * Get remaining days until end date
     */
    public function getRemainingDaysAttribute()
    {
        if (!$this->end_date) {
            return 0;
        }
        
        $now = Carbon::now();
        $endDate = Carbon::parse($this->end_date);
        
        if ($endDate->isPast()) {
            return 0;
        }
        
        return $now->diffInDays($endDate, false);
    }

    /**
     * Get remaining days for schedule taking
     */
    public function getRemainingScheduleDaysAttribute()
    {
        if (!$this->schedule_end_date) {
            return 0;
        }
        
        $now = Carbon::now();
        $endDate = Carbon::parse($this->schedule_end_date);
        
        if ($endDate->isPast()) {
            return 0;
        }
        
        return $now->diffInDays($endDate, false);
    }

    /**
     * Get date range formatted
     */
    public function getDateRangeAttribute()
    {
        $startFormatted = Carbon::parse($this->start_date)->locale('id')->translatedFormat('j M Y');
        $endFormatted = Carbon::parse($this->end_date)->locale('id')->translatedFormat('j M Y');
        
        return "{$startFormatted} - {$endFormatted}";
    }

    /**
     * Get schedule date range formatted - FIXED: Handle null values properly
     */
    public function getScheduleDateRangeAttribute()
    {
        if (!$this->schedule_start_date || !$this->schedule_end_date) {
            return "Belum diatur";
        }
        
        try {
            $startFormatted = Carbon::parse($this->schedule_start_date)->locale('id')->translatedFormat('j M Y');
            $endFormatted = Carbon::parse($this->schedule_end_date)->locale('id')->translatedFormat('j M Y');
            
            return "{$startFormatted} - {$endFormatted}";
        } catch (\Exception $e) {
            return "Format tanggal tidak valid";
        }
    }

    /**
     * Check if schedule taking is currently open - ULTIMATE FIX
     */
    public function getIsScheduleTakingOpenAttribute()
    {
        if (!$this->is_active) {
            Log::info('🔒 Schedule taking CLOSED: Semester not active');
            return false;
        }

        // ✅ PRIORITY 1: Check manual override (is_schedule_open)
        // If admin opens schedule taking manually, this takes precedence
        if ($this->is_schedule_open) {
            Log::info('✅ Schedule taking OPEN: Manual override by admin');
            return true;
        }

        // ✅ PRIORITY 2: Check automatic date-based opening
        // Check if schedule dates are set
        if (!$this->schedule_start_date || !$this->schedule_end_date) {
            Log::info('🔒 Schedule taking CLOSED: Dates not set');
            return false;
        }

        // CRITICAL FIX: Use startOfDay and endOfDay for proper date comparison
        $now = Carbon::now();
        $startDate = Carbon::parse($this->schedule_start_date)->startOfDay();
        $endDate = Carbon::parse($this->schedule_end_date)->endOfDay();

        Log::info('📅 Date comparison:', [
            'now' => $now->toDateTimeString(),
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
            'is_between' => $now->between($startDate, $endDate)
        ]);

        $isOpen = $now->between($startDate, $endDate);
        
        if ($isOpen) {
            Log::info('✅ Schedule taking OPEN: Within date range');
        } else {
            Log::info('🔒 Schedule taking CLOSED: Outside date range');
        }

        return $isOpen;
    }

    /**
     * Check if user is allowed to edit schedule - FIXED VERSION
     */
    public function isUserAllowed($userId)
    {
        if (!$this->allowed_users || !is_array($this->allowed_users)) {
            return false;
        }

        return in_array($userId, $this->allowed_users);
    }

    /**
     * Relationship with schedules
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'period_id', 'period_id');
    }

    /**
     * Scope untuk periode aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Deactivate all other periods when activating this one
     */
    public function activate()
    {
        // Deactivate all other periods
        self::where('period_id', '!=', $this->period_id)->update(['is_active' => false]);
        
        // Activate this period
        $this->update(['is_active' => true]);
    }

    /**
     * Open schedule taking for specific users
     */
    public function openScheduleTaking($userIds = [])
    {
        $this->update([
            'is_schedule_open' => true,
            'allowed_users' => $userIds
        ]);
    }

    /**
     * Close schedule taking
     */
    public function closeScheduleTaking()
    {
        $this->update([
            'is_schedule_open' => false,
            'allowed_users' => null
        ]);
    }
}