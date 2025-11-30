<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_classes', function (Blueprint $table) {
            $table->id('class_id');
            $table->unsignedBigInteger('course_id');
            $table->string('class_name', 10); // Tetap 'class_name', nilai: 'A1', 'B2', dll
            $table->string('lecturer', 100);
            $table->timestamps();

            // Foreign key ke course_id
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            
            // Foreign key ke class_name di class_types (bukan ke id!)
            $table->foreign('class_name')->references('class_name')->on('class_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_classes');
    }
};