<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_types', function (Blueprint $table) {
            $table->id();
            $table->string('class_name', 10)->unique();
            $table->timestamps();
        });

        DB::table('class_types')->insert([
            ['class_name' => 'A1'],
            ['class_name' => 'A2'],
            ['class_name' => 'B1'],
            ['class_name' => 'B2'],
            ['class_name' => 'C1'],
            ['class_name' => 'C2'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('class_types');
    }
};