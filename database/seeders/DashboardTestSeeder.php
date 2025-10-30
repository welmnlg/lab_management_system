<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardTestSeeder extends Seeder
{
    /**
     * Seeder untuk testing dashboard - FIXED VERSION
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting DashboardTestSeeder...');

        // ========================================
        // 1. PROGRAMS (No Dependencies)
        // ========================================
        $programs = [
            ['name' => 'Informatika', 'faculty' => 'Teknik', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sistem Informasi', 'faculty' => 'Teknik', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('programs')->insert($programs);
        $this->command->info('✅ Programs seeded (2)');

        // ========================================
        // 2. ROOMS (No Dependencies) - PENTING!
        // ========================================
        $rooms = [
            ['room_name' => 'Lab Jaringan 1', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Jaringan 2', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Jaringan 3', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Jaringan 4', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Multimedia 1', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Multimedia 2', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Pemrograman 1', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Lab Pemrograman 2', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('rooms')->insert($rooms);
        $this->command->info('✅ Rooms seeded (8)');

        // ========================================
        // 3. USERS (Depends on: programs)
        // ========================================
        $users = [
            [
                'name' => 'Dr. Aulia Halimatusyaddiah',
                'email' => 'aulia@example.com',
                'password' => bcrypt('password'),
                'nim' => null,
                'program_studi' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Immanuel Manulang',
                'email' => 'immanuel@example.com',
                'password' => bcrypt('password'),
                'nim' => '221402036',
                'program_studi' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nurul Aini',
                'email' => 'nurul@example.com',
                'password' => bcrypt('password'),
                'nim' => '221402045',
                'program_studi' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('users')->insert($users);
        $this->command->info('✅ Users seeded (3)');

        // ========================================
        // 4. COURSES (Depends on: programs)
        // ========================================
        $courses = [
            [
                'course_code' => 'TIK301',
                'course_name' => 'Praktikum Web Semantik',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_code' => 'TIK302',
                'course_name' => 'Praktikum PBOL',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_code' => 'TIK303',
                'course_name' => 'Praktikum Pemrograman Web',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_code' => 'TIK304',
                'course_name' => 'Praktikum Kecerdasan Buatan',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('courses')->insert($courses);
        $this->command->info('✅ Courses seeded (4)');

        // ========================================
        // 5. COURSE CLASSES (Depends on: courses)
        // ========================================
        $courseClasses = [
            ['course_id' => 1, 'class_name' => 'KOM A1', 'lecturer' => 'Dr. Aulia', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 1, 'class_name' => 'KOM A2', 'lecturer' => 'Dr. Aulia', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 2, 'class_name' => 'KOM B1', 'lecturer' => 'Dr. John', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 3, 'class_name' => 'KOM C1', 'lecturer' => 'Dr. Jane', 'created_at' => now(), 'updated_at' => now()],
            ['course_id' => 4, 'class_name' => 'KOM D1', 'lecturer' => 'Dr. Smith', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('course_classes')->insert($courseClasses);
        $this->command->info('✅ Course classes seeded (5)');

        // ========================================
        // 6. SCHEDULES (Depends on: course_classes, users, rooms)
        // ========================================
        $schedules = [
            // Lab Jaringan 1
            ['class_id' => 1, 'user_id' => 1, 'room_id' => 1, 'day' => 'Senin', 'start_time' => '08:00:00', 'end_time' => '09:40:00', 'created_at' => now(), 'updated_at' => now()],
            ['class_id' => 2, 'user_id' => 1, 'room_id' => 1, 'day' => 'Selasa', 'start_time' => '08:00:00', 'end_time' => '09:40:00', 'created_at' => now(), 'updated_at' => now()],
            ['class_id' => 3, 'user_id' => 2, 'room_id' => 1, 'day' => 'Rabu', 'start_time' => '13:00:00', 'end_time' => '14:40:00', 'created_at' => now(), 'updated_at' => now()],
            
            // Lab Jaringan 2
            ['class_id' => 3, 'user_id' => 2, 'room_id' => 2, 'day' => 'Selasa', 'start_time' => '11:20:00', 'end_time' => '13:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['class_id' => 4, 'user_id' => 3, 'room_id' => 2, 'day' => 'Kamis', 'start_time' => '09:40:00', 'end_time' => '11:20:00', 'created_at' => now(), 'updated_at' => now()],
            
            // Lab Jaringan 3
            ['class_id' => 5, 'user_id' => 1, 'room_id' => 3, 'day' => 'Jumat', 'start_time' => '14:40:00', 'end_time' => '16:20:00', 'created_at' => now(), 'updated_at' => now()],
            
            // Lab Jaringan 4
            ['class_id' => 1, 'user_id' => 1, 'room_id' => 4, 'day' => 'Kamis', 'start_time' => '13:00:00', 'end_time' => '14:40:00', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('schedules')->insert($schedules);
        $this->command->info('✅ Schedules seeded (7)');

        // ========================================
        // 7. SCHEDULE OVERRIDES (Depends on: schedules, users, rooms)
        // ========================================
        $today = Carbon::now();
        $mondayThisWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        
        // Override untuk hari ini
        if ($today->dayOfWeek >= Carbon::MONDAY && $today->dayOfWeek <= Carbon::FRIDAY) {
            DB::table('schedule_overrides')->insert([
                'schedule_id' => 1,
                'user_id' => 1,
                'room_id' => 1,
                'class_id' => 1,
                'date' => $today->format('Y-m-d'),
                'day' => $this->getIndonesianDay($today),
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'reason' => 'Kelas ganti karena dosen berhalangan minggu lalu',
                'status' => 'active',
                'created_at' => $mondayThisWeek,
                'updated_at' => now()
            ]);
            $this->command->info('✅ Override untuk hari ini seeded');
        }

        // Override untuk hari Rabu
        $wednesday = $mondayThisWeek->copy()->addDays(2);
        DB::table('schedule_overrides')->insert([
            'schedule_id' => null, // Kelas tambahan (bukan pengganti)
            'user_id' => 2,
            'room_id' => 2,
            'class_id' => 3,
            'date' => $wednesday->format('Y-m-d'),
            'day' => 'Rabu',
            'start_time' => '09:40:00',
            'end_time' => '11:20:00',
            'reason' => 'Kelas tambahan untuk remedial',
            'status' => 'active',
            'created_at' => $mondayThisWeek,
            'updated_at' => now()
        ]);
        $this->command->info('✅ Schedule overrides seeded (2)');

        $this->command->info('');
        $this->command->info('🎉 ======================================');
        $this->command->info('🎉 SEEDING COMPLETED SUCCESSFULLY!');
        $this->command->info('🎉 ======================================');
        $this->command->info('📅 Override minggu: ' . $mondayThisWeek->format('d M Y') . ' - ' . $mondayThisWeek->copy()->addDays(4)->format('d M Y'));
        $this->command->info('');
        $this->command->info('📊 Total data yang di-seed:');
        $this->command->info('   Programs: ' . DB::table('programs')->count());
        $this->command->info('   Rooms: ' . DB::table('rooms')->count());
        $this->command->info('   Users: ' . DB::table('users')->count());
        $this->command->info('   Courses: ' . DB::table('courses')->count());
        $this->command->info('   Course Classes: ' . DB::table('course_classes')->count());
        $this->command->info('   Schedules: ' . DB::table('schedules')->count());
        $this->command->info('   Overrides: ' . DB::table('schedule_overrides')->count());
    }

    private function getIndonesianDay(Carbon $date): string
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        return $days[$date->format('l')] ?? 'Senin';
    }
}