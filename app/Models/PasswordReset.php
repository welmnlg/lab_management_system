<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordReset extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'otp',
        'token',
        'otp_expires_at',
        'created_at',
        'is_verified',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'created_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Generate OTP 6 digit
     */
    public static function generateOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate token untuk security
     */
    public static function generateToken()
    {
        return \Illuminate\Support\Str::random(60);
    }

    /**
     * Check apakah OTP masih valid (belum expired)
     */
    public function isOTPValid()
    {
        return Carbon::now()->lessThan($this->otp_expires_at);
    }

    /**
     * Check sisa waktu OTP dalam detik
     */
    public function getOTPRemainingSeconds()
    {
        $remaining = Carbon::now()->diffInSeconds($this->otp_expires_at, false);
        return max(0, (int)$remaining); // Cast to integer untuk hilangkan desimal
    }

    /**
     * Create atau update password reset record
     */
    public static function createOrUpdate($email)
    {
        return self::updateOrCreate(
            ['email' => $email],
            [
                'otp' => self::generateOTP(),
                'token' => self::generateToken(),
                'otp_expires_at' => Carbon::now()->addMinutes(3), // UBAH JADI 3 MENIT
                'created_at' => Carbon::now(),
                'is_verified' => false,
            ]
        );
    }

    /**
     * Verify OTP
     */
    public function verifyOTP($otp)
    {
        if (!$this->isOTPValid()) {
            return false; // OTP sudah expired
        }

        if ($this->otp !== $otp) {
            return false; // OTP tidak cocok
        }

        // Mark sebagai verified
        $this->update(['is_verified' => true]);
        return true;
    }

    /**
     * Reset password dan hapus record
     */
    public function completeReset()
    {
        $this->delete();
    }
}