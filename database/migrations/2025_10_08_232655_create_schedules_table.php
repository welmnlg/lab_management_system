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
            
            // Indexes for better performance (only for columns that exist)
            $table->index(['room_id', 'day']);
            $table->index('user_id');
            $table->index('class_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};