<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('room_id');
            $table->string('room_name', 100);
            // PASTIKAN: kolom namanya 'building_id' bukan 'buildings'
            $table->unsignedBigInteger('building_id'); // Tambah ini dulu
            $table->timestamps();

            // LALU tambah foreign key constraint
            $table->foreign('building_id')->references('building_id')->on('buildings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};