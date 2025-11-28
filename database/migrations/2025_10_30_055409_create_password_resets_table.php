<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop yang lama jika ada
        Schema::dropIfExists('password_reset_tokens');
        
        // Buat tabel baru untuk password reset dengan OTP
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('otp', 6); // Kode OTP 6 digit
            $table->string('token'); // Token untuk security
            $table->timestamp('otp_expires_at'); // Waktu kadaluarsa OTP (berlaku 10 menit)
            $table->timestamp('created_at')->nullable();
            $table->boolean('is_verified')->default(false); // Flag apakah OTP sudah diverifikasi
            
            // Index untuk query yang lebih cepat
            $table->index('email');
            $table->index('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};