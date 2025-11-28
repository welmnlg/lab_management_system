<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Status column - AFTER end_time
            $table->string('status')
                ->default('terjadwal')
                ->after('end_time')
                ->comment('terjadwal, dikonfirmasi, pindah_ruangan, sedang_berlangsung, selesai, dibatalkan');
            
            // Timestamps untuk track state changes
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
            $table->timestamp('started_at')->nullable()->after('cancelled_at');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->timestamp('moved_at')->nullable()->after('completed_at');
            
            // Index untuk query performa
            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index(['room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'confirmed_at',
                'cancelled_at',
                'started_at',
                'completed_at',
                'moved_at'
            ]);
            
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['room_id', 'status']);
        });
    }
};
