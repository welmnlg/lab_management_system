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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('room_id');
            $table->enum('day', ['Senin','Selasa','Rabu','Kamis','Jumat']);
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            
            //foreign key
            $table->foreign('class_id')->references('class_id')->on('course_classes')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
