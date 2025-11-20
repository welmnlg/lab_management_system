<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semester_periods', function (Blueprint $table) {
            $table->id('period_id');
            $table->enum('semester_type', ['Ganjil', 'Genap']);
            $table->string('academic_year', 20); // Format: 2024/2025
            $table->date('start_date');
            $table->date('end_date');
            $table->date('schedule_start_date')->nullable();
            $table->date('schedule_end_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_schedule_open')->default(false);
            $table->json('allowed_users')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semester_periods');
    }
};