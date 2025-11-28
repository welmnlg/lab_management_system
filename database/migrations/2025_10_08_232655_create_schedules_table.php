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
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id');
            // $table->enum('day', ['Senin','Selasa','Rabu','Kamis','Jumat']);
            $table->enum('day', ['Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu', 'Minggu']); //testing Sabtu Minggu
            $table->time('start_time');
            $table->time('end_time');
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