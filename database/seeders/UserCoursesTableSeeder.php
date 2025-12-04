<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('user_courses')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get users dynamically
        $users = DB::table('users')->get()->keyBy('name');
        
        // Get courses by code
        $courses = DB::table('courses')->get()->keyBy('course_code');
        
        // Get class IDs helper function
        $getClassIds = function($courseCode, $komNames) use ($courses) {
            if (!isset($courses[$courseCode])) return [];
            
            $courseId = $courses[$courseCode]->course_id;
            $classIds = [];
            
            foreach ($komNames as $komName) {
                $class = DB::table('course_classes')
                    ->where('course_id', $courseId)
                    ->where('class_name', $komName)
                    ->first();
                    
                if ($class) {
                    $classIds[] = $class->class_id;
                }
            }
            
            return $classIds;
        };

        $assignments = [];

        // Austin Butler (user_id: 1) - Admin/BPH + Aslab TI
        // Ganjil semester courses
        if (isset($users['Austin Butler'])) {
            $userId = $users['Austin Butler']->user_id;
            
            // Praktikum Pemrograman Web (Ganjil) - Kom A1, A2
            foreach ($getClassIds('TIF2201', ['Kom A1', 'Kom A2']) as $classId) {
                $assignments[] = [
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Praktikum MSBD (Ganjil) - Kom B1
            foreach ($getClassIds('TIF2203', ['Kom B1']) as $classId) {
                $assignments[] = [
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Kim Mingyu (user_id: 2) - Aslab TI
        if (isset($users['Kim Mingyu'])) {
            $userId = $users['Kim Mingyu']->user_id;
            
            // Praktikum Mobile Hacking (Ganjil) - Kom A1, A2, C1
            foreach ($getClassIds('TIF2205', ['Kom A1', 'Kom A2', 'Kom C1']) as $classId) {
                $assignments[] = [
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Karina - Aslab TI
        if (isset($users['Karina'])) {
            $userId = $users['Karina']->user_id;
            
            // Praktikum MSBD (Ganjil) - Kom A1, A2
            foreach ($getClassIds('TIF2203', ['Kom A1', 'Kom A2']) as $classId) {
                $assignments[] = [
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Hermione Granger - Aslab TI
        if (isset($users['Hermione Granger'])) {
            $userId = $users['Hermione Granger']->user_id;
            
            // Praktikum Mobile Hacking (Ganjil) - Kom B1, B2
            foreach ($getClassIds('TIF2205', ['Kom B1', 'Kom B2']) as $classId) {
                $assignments[] = [
                    'user_id' => $userId,
                    'class_id' => $classId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all assignments
        if (!empty($assignments)) {
            DB::table('user_courses')->insert($assignments);
        }

        $this->command->info('✅ User Courses assignments seeded successfully!');
        $this->command->info('   - ' . count($assignments) . ' course-class assignments created');
        $this->command->info('   - Focused on Ganjil semester courses (matching active period)');
    }
}