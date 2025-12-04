<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('courses')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $courses = [
            // SEMESTER GANJIL - Teknologi Informasi
            [
                'course_code' => 'TIF2201',
                'course_name' => 'Praktikum Pemrograman Web',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'TIF2203',
                'course_name' => 'Praktikum Manajemen Sistem Basis Data',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'TIF2205',
                'course_name' => 'Praktikum Mobile Hacking',
                'semester' => 'Ganjil',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // SEMESTER GENAP - Teknologi Informasi
            [
                'course_code' => 'TIF2206',
                'course_name' => 'Praktikum Sistem Basis Data',
                'semester' => 'Genap',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'TIF2207',
                'course_name' => 'Praktikum Kecerdasan Buatan',
                'semester' => 'Genap',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'TIF2209',
                'course_name' => 'Praktikum Keamanan Server dan Jaringan',
                'semester' => 'Genap',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_code' => 'TIF1204',
                'course_name' => 'Manajemen Sistem Basis Data',
                'semester' => 'Genap',
                'program_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
    
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert($course);
        }

        $this->command->info('✅ Courses seeded successfully!');
        $this->command->info('   - 4 Ganjil courses (3 TIF + 1 ILK)');
        $this->command->info('   - 5 Genap courses (4 TIF + 1 ILK)');
    }
}
