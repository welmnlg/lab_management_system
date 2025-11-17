<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
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
            'course_code' => 'TIF1206',
            'course_name' => 'Praktikum Sistem Basis Data',
            'semester' => 'Genap',
            'program_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'course_code' => 'ILK1206',
            'course_name' => 'Praktikum Computer Vision',
            'semester' => 'Genap',
            'program_id' => 2,
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
    ]);
    }
}
