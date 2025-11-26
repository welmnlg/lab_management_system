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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schedule_id'); // menyimpan jadwal terkait
            $table->text('message'); // Isi pesan notifikasi
            $table->string('title')->default('Pengingat Jadwal');
            $table->enum('class_status', ['waiting', 'confirmed', 'canceled'])->default('waiting');
            $table->timestamp('notified_at')->nullable(); // waktu notif muncul
            $table->timestamp('confirmed_at')->nullable(); // waktu user konfirmasi
            $table->timestamps();
            
            //foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
