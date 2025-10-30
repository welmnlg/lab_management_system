<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OctoberScheduleSeeder extends Seeder
{
    /**
     * Seeder untuk Oktober-November 2025
     * Minggu 1: 27-31 Oktober 2025 (Senin-Jumat)
     * Minggu 2: 3-7 November 2025 (Senin-Jumat)
     */
    public function run(): void
    {
        $this->command->info('🗓️  Seeding schedules for October-November 2025...');
        
        // Ensure base data exists
        $this->seedBaseData();
        
        // Seed regular weekly schedules (will repeat every week)
        $this->seedRegularSchedules();
        
        $this->command->info('✅ October-November schedules seeded successfully!');
        $this->command->info('📅 Minggu 1: 27-31 Oktober 2025');
        $this->command->info('📅 Minggu 2: 3-7 November 2025');
    }
    
    /**
     * Seed regular schedules (default schedules untuk setiap minggu)
     */
    private function seedRegularSchedules()
    {
        if (DB::table('schedules')->count() > 0) {
            $this->command->warn('⚠️  Schedules already exist, skipping...');
            return;
        }
        
        $schedules = [
            // LAB JARINGAN 1
            [
                'class_id' => 1,
                'user_id' => 1,
                'room_id' => 1,
                'day' => 'Selasa',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 3,
                'user_id' => 2,
                'room_id' => 1,
                'day' => 'Selasa',
                'start_time' => '11:20:00',
                'end_time' => '13:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 4,
                'user_id' => 3,
                'room_id' => 1,
                'day' => 'Rabu',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 5,
                'user_id' => 1,
                'room_id' => 1,
                'day' => 'Jumat',
                'start_time' => '14:40:00',
                'end_time' => '16:20:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // LAB JARINGAN 2
            [
                'class_id' => 2,
                'user_id' => 1,
                'room_id' => 2,
                'day' => 'Senin',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 3,
                'user_id' => 2,
                'room_id' => 2,
                'day' => 'Rabu',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 4,
                'user_id' => 3,
                'room_id' => 2,
                'day' => 'Kamis',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // LAB JARINGAN 3
            [
                'class_id' => 1,
                'user_id' => 1,
                'room_id' => 3,
                'day' => 'Kamis',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 5,
                'user_id' => 1,
                'room_id' => 3,
                'day' => 'Jumat',
                'start_time' => '11:20:00',
                'end_time' => '13:00:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // LAB JARINGAN 4
            [
                'class_id' => 2,
                'user_id' => 1,
                'room_id' => 4,
                'day' => 'Senin',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => 4,
                'user_id' => 3,
                'room_id' => 4,
                'day' => 'Rabu',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('schedules')->insert($schedules);
        $this->command->info('✅ Regular schedules seeded (' . count($schedules) . ' schedules)');
    }
    
    /**
     * Seed base data if not exists
     */
    private function seedBaseData()
    {
        // Programs
        if (DB::table('programs')->count() == 0) {
            DB::table('programs')->insert([
                ['name' => 'Informatika', 'faculty' => 'Teknik', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Sistem Informasi', 'faculty' => 'Teknik', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
        
        // Users
        if (DB::table('users')->count() == 0) {
            DB::table('users')->insert([
                ['name' => 'Dr. Aulia Halimatusyaddiah', 'email' => 'aulia@example.com', 'password' => bcrypt('password'), 'nim' => '221402130', 'program_studi' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Immanuel Manulang', 'email' => 'immanuel@example.com', 'password' => bcrypt('password'), 'nim' => '221402036', 'program_studi' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Nurul Aini', 'email' => 'nurul@example.com', 'password' => bcrypt('password'), 'nim' => '221402045', 'program_studi' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
        
        // Rooms
        if (DB::table('rooms')->count() == 0) {
            DB::table('rooms')->insert([
                ['room_name' => 'Lab Jaringan 1', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
                ['room_name' => 'Lab Jaringan 2', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
                ['room_name' => 'Lab Jaringan 3', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
                ['room_name' => 'Lab Jaringan 4', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
        
        // Courses
        if (DB::table('courses')->count() == 0) {
            DB::table('courses')->insert([
                ['course_code' => 'TIK301', 'course_name' => 'Praktikum Web Semantik', 'semester' => 'Ganjil', 'program_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['course_code' => 'TIK302', 'course_name' => 'Praktikum PBOL', 'semester' => 'Ganjil', 'program_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['course_code' => 'TIK303', 'course_name' => 'Praktikum Pemrograman Web', 'semester' => 'Ganjil', 'program_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['course_code' => 'TIK304', 'course_name' => 'Praktikum Kecerdasan Buatan', 'semester' => 'Ganjil', 'program_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['course_code' => 'TIK305', 'course_name' => 'Praktikum Basis Data', 'semester' => 'Ganjil', 'program_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
        
        // Course Classes
        if (DB::table('course_classes')->count() == 0) {
            DB::table('course_classes')->insert([
                ['course_id' => 1, 'class_name' => 'KOM A1', 'lecturer' => 'Dr. Aulia', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 1, 'class_name' => 'KOM A2', 'lecturer' => 'Dr. Aulia', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 2, 'class_name' => 'KOM B1', 'lecturer' => 'Immanuel', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 3, 'class_name' => 'KOM C1', 'lecturer' => 'Nurul', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 4, 'class_name' => 'KOM D1', 'lecturer' => 'Dr. Aulia', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}