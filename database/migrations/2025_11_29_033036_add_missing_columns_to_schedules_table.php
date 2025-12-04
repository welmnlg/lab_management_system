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
        Schema::table('schedules', function (Blueprint $table) {
            // Add only missing period_id column
            $table->unsignedBigInteger('period_id')->nullable()->after('schedule_id');
            
            // Add index for better performance
            $table->index('period_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['period_id']);
            $table->dropColumn('period_id');
        });
    }
};
