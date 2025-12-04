<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('course_classes')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get course IDs dynamically
        $courses = DB::table('courses')->pluck('course_id', 'course_code');

        // Standard KOM classes for each course
        $komClasses = ['Kom A1', 'Kom A2', 'Kom B1', 'Kom B2', 'Kom C1', 'Kom C2'];
        
        $lecturers = [
            'Minerva McGonagall',
            'Severus Snape',
            'Albus Dumbledore',
            'Filius Flitwick',
            'Rubeus Hagrid',
            'Remus Lupin',
            'Sarah Purnawati',
            'Budi Santoso',
        ];

        $classes = [];

        // Create classes for each course
        foreach ($courses as $courseCode => $courseId) {
            // Randomly select a lecturer for this course
            $lecturer = $lecturers[array_rand($lecturers)];
            
            // Create all 6 KOM classes for this course
            foreach ($komClasses as $komName) {
                $classes[] = [
                    'class_name' => $komName,
                    'course_id' => $courseId,
                    'lecturer' => $lecturer,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all classes
        DB::table('course_classes')->insert($classes);

        $totalCourses = count($courses);
        $totalClasses = count($classes);

        $this->command->info('✅ Course Classes seeded successfully!');
        $this->command->info("   - $totalCourses courses");
        $this->command->info("   - $totalClasses classes (6 KOM per course)");
    }
}
