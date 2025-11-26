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
        Schema::create('schedule_overrides', function (Blueprint $table) {
            $table->id();
            
            // PERBAIKAN: schedule_id nullable untuk kelas tambahan
            $table->unsignedBigInteger('schedule_id')->nullable();
            // TAMBAHAN: Recursive override (untuk pindah ruangan dari kelas ganti)
            $table->unsignedBigInteger('schedule_override_id')->nullable();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->date('date');
            $table->enum('day', ['Senin','Selasa','Rabu','Kamis','Jumat']);
            $table->time('start_time');
            $table->time('end_time');
            
            // TAMBAHAN: field reason dan status
            $table->string('reason', 500)->nullable();
            $table->enum('status', ['active', 'dikonfirmasi', 'cancelled', 'selesai', 'sedang_berlangsung', 'pindah_ruangan'])->default('active');
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('cascade');
            $table->foreign('schedule_override_id')->references('id')->on('schedule_overrides')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('class_id')->references('class_id')->on('course_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_overrides');
    }
};