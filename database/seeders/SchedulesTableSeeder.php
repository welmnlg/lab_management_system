<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada periode aktif, gedung, ruangan, courses, dan users terlebih dahulu
        $activePeriod = DB::table('semester_periods')->where('is_active', true)->first();
        
        if (!$activePeriod) {
            $this->command->info('Tidak ada periode aktif. Skipping schedules seeder.');
            return;
        }

        // Get sample data
        $user = DB::table('users')->where('email', 'austin@example.com')->first();
        $room = DB::table('rooms')->first();
        $course = DB::table('courses')->first();
        $courseClass = DB::table('course_classes')->first();

        if (!$user || !$room || !$course || !$courseClass) {
            $this->command->info('Data sample tidak lengkap. Skipping schedules seeder.');
            return;
        }

        $schedules = [
            [
                'period_id' => $activePeriod->period_id,
                'user_id' => $user->user_id,
                'room_id' => $room->room_id,
                'course_id' => $course->course_id,
                'class_id' => $courseClass->class_id,
                'day_of_week' => 'Senin',
                'time_slot' => '08:00 - 09:40',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'period_id' => $activePeriod->period_id,
                'user_id' => $user->user_id,
                'room_id' => $room->room_id,
                'course_id' => $course->course_id,
                'class_id' => $courseClass->class_id,
                'day_of_week' => 'Rabu',
                'time_slot' => '10:20 - 12:00',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('schedules')->insert($schedules);
        
        $this->command->info('Sample schedules created successfully.');
    }
}