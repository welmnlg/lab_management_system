<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            
            // Foreign keys
            $table->foreignId('period_id')->constrained('semester_periods', 'period_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses', 'course_id')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('course_classes', 'class_id')->onDelete('cascade');
            
            // Schedule details
            $table->enum('day', ['Senin','Selasa','Rabu','Kamis','Jumat']);
            $table->string('time_slot'); // Untuk menyimpan format "08.00 - 08:50"
            $table->time('start_time');  // Untuk menyimpan waktu mulai
            $table->time('end_time');    // Untuk menyimpan waktu selesai
            $table->enum('status', ['active', 'cancelled'])->default('active');
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['period_id', 'room_id', 'day']);
            $table->index(['period_id', 'user_id']);
            $table->index(['period_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};