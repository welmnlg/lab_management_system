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
        Schema::create('user_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');    // misal custom PK di tabel users
            $table->unsignedBigInteger('class_id');  // misal custom PK di tabel course_classes
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('class_id')->references('class_id')->on('course_classes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_courses');
    }
};
