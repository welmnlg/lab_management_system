<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('schedules')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get active period
        $activePeriod = DB::table('semester_periods')
            ->where('is_active', true)
            ->first();

        if (!$activePeriod) {
            $this->command->warn('⚠️  No active period found. Skipping schedule seeding.');
            return;
        }

        // Get users
        $users = DB::table('users')->get()->keyBy('name');
        
        // Get some class IDs for schedule creation
        $getClassId = function($courseCode, $komName) {
            $course = DB::table('courses')->where('course_code', $courseCode)->first();
            if (!$course) return null;
            
            $class = DB::table('course_classes')
                ->where('course_id', $course->course_id)
                ->where('class_name', $komName)
                ->first();
                
            return $class ? $class->class_id : null;
        };

        // Get room IDs
        $rooms = DB::table('rooms')->pluck('room_id', 'room_name');

        $schedules = [];

        // Austin Butler schedules
        if (isset($users['Austin Butler']) && isset($rooms['C101'])) {
            $userId = $users['Austin Butler']->user_id;
            $classId = $getClassId('TIF2201', 'Kom A1'); // Pemrograman Web A1
            
            if ($classId) {
                $schedules[] = [
                    'period_id' => $activePeriod->period_id,
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'room_id' => $rooms['C101'],
                    'day' => 'Senin',
                    'start_time' => '08:00:00',
                    'end_time' => '09:40:00',
                    'status' => 'terjadwal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $classId2 = $getClassId('TIF2203', 'Kom B1'); // MSBD B1
            if ($classId2 && isset($rooms['C102'])) {
                $schedules[] = [
                    'period_id' => $activePeriod->period_id,
                    'user_id' => $userId,
                    'class_id' => $classId2,
                    'room_id' => $rooms['C102'],
                    'day' => 'Selasa',
                    'start_time' => '09:40:00',
                    'end_time' => '11:20:00',
                    'status' => 'terjadwal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Kim Mingyu schedules
        if (isset($users['Kim Mingyu']) && isset($rooms['C101'])) {
            $userId = $users['Kim Mingyu']->user_id;
            $classId = $getClassId('TIF2205', 'Kom A1'); // Mobile Hacking A1
            
            if ($classId) {
                $schedules[] = [
                    'period_id' => $activePeriod->period_id,
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'room_id' => $rooms['C101'],
                    'day' => 'Senin',
                    'start_time' => '09:40:00',
                    'end_time' => '11:20:00',
                    'status' => 'terjadwal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Karina schedules
        if (isset($users['Karina']) && isset($rooms['C103'])) {
            $userId = $users['Karina']->user_id;
            $classId = $getClassId('TIF2203', 'Kom A1'); // MSBD A1
            
            if ($classId) {
                $schedules[] = [
                    'period_id' => $activePeriod->period_id,
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'room_id' => $rooms['C103'],
                    'day' => 'Rabu',
                    'start_time' => '08:00:00',
                    'end_time' => '09:40:00',
                    'status' => 'terlewat',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert schedules
        if (!empty($schedules)) {
            DB::table('schedules')->insert($schedules);
        }

        $this->command->info('✅ Schedules seeded successfully!');
        $this->command->info('   - Period: ' . $activePeriod->semester_type . ' ' . $activePeriod->academic_year);
        $this->command->info('   - ' . count($schedules) . ' sample schedules created');
    }
}