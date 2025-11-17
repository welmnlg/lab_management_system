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
        Schema::create('room_occupancy_status', function (Blueprint $table) {
            $table->id('occupancy_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('current_user_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable(); 
            $table->boolean('is_active')->default(false);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('room_id')->references('room_id')->on('rooms')->onDelete('cascade');
            $table->foreign('current_user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('set null');
            
            // Index
            $table->index(['room_id', 'schedule_id', 'is_active']);
            $table->index(['current_user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_occupancy_status');
    }
};
