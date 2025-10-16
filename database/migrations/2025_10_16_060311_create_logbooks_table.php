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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('schedule_id')->nullable(); 
            $table->unsignedBigInteger('override_id')->nullable(); 
            $table->unsignedBigInteger('room_id'); 
            $table->unsignedBigInteger('course_id'); 
            $table->date('date'); 
            $table->time('login'); 
            $table->time('logout')->nullable(); 
            $table->enum('activity', ['MENGAJAR', 'BELAJAR'])->default('MENGAJAR'); 
            $table->enum('status', ['GANTI RUANGAN', 'SELESAI'])->nullable(); 
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('set null');
            $table->foreign('override_id')->references('id')->on('schedule_overrides')->onDelete('set null');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');

            // Index untuk performa query
            $table->index(['user_id', 'date']);
            $table->index(['room_id', 'date']);
            $table->index(['course_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
