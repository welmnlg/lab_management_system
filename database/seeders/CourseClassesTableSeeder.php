<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('course_classes')->insert([
            // Praktikum Kecerdasan Buatan (course_id=1)
            ['course_id' => 1, 'class_name' => 'A1', 'lecturer' => 'Minerva Mcgonaggal'],
            ['course_id' => 1, 'class_name' => 'A2', 'lecturer' => 'Minerva Mcgonaggal'],
            ['course_id' => 1, 'class_name' => 'B1', 'lecturer' => 'Minerva Mcgonaggal'],
            ['course_id' => 1, 'class_name' => 'B2', 'lecturer' => 'Minerva Mcgonaggal'],
            ['course_id' => 1, 'class_name' => 'C1', 'lecturer' => 'Minerva Mcgonaggal'],
            ['course_id' => 1, 'class_name' => 'C2', 'lecturer' => 'Minerva Mcgonaggal'],

            // Praktikum Keamanan Server dan Jaringan (course_id=2)
            ['course_id' => 2, 'class_name' => 'A1', 'lecturer' => 'Filius Flitwick'],
            ['course_id' => 2, 'class_name' => 'A2', 'lecturer' => 'Filius Flitwick'],
            ['course_id' => 2, 'class_name' => 'B1', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 2, 'class_name' => 'B2', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 2, 'class_name' => 'C1', 'lecturer' => 'Filius Flitwick'],
            ['course_id' => 2, 'class_name' => 'C2', 'lecturer' => 'Filius Flitwick'],

            // Praktikum Sistem Basis Data (course_id=3)
            ['course_id' => 3, 'class_name' => 'A1', 'lecturer' => 'Severus Snape'],
            ['course_id' => 3, 'class_name' => 'A2', 'lecturer' => 'Severus Snape'],
            ['course_id' => 3, 'class_name' => 'B1', 'lecturer' => 'Severus Snape'],
            ['course_id' => 3, 'class_name' => 'B2', 'lecturer' => 'Severus Snape'],
            ['course_id' => 3, 'class_name' => 'C1', 'lecturer' => 'Severus Snape'],
            ['course_id' => 3, 'class_name' => 'C2', 'lecturer' => 'Severus Snape'],

            // Praktikum Computer Vision (course_id=4)
            ['course_id' => 4, 'class_name' => 'A1', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 4, 'class_name' => 'A2', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 4, 'class_name' => 'B1', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 4, 'class_name' => 'B2', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 4, 'class_name' => 'C1', 'lecturer' => 'Albus Dumbledore'],
            ['course_id' => 4, 'class_name' => 'C2', 'lecturer' => 'Albus Dumbledore'],
        ]);
    }
}