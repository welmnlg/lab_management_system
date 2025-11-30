<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCoursesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_courses')->insert([
            // Austin Butler (user_id=1) ngajar Praktikum Kecerdasan Buatan (course_id=1)
            // Kom A1 (class_id=1), A2 (2), B1 (3), B2 (4)
            ['user_id' => 1, 'class_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Feyd-Rautha (user_id=2) ngajar Praktikum Computer Vision (course_id=4)
            // Kom B1 (class_id=21), B2 (22)
            ['user_id' => 2, 'class_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'class_id' => 22, 'created_at' => now(), 'updated_at' => now()],

            // Kim Mingyu (user_id=3) ngajar Praktikum Keamanan Server dan Jaringan (course_id=2)
            // Kom A1 (7), A2 (8), C1 (11), C2 (12)
            ['user_id' => 3, 'class_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 12, 'created_at' => now(), 'updated_at' => now()],

            // Wil Ohmsford (user_id=4) ngajar Keamanan Server (course_id=2) Kom B1 (9), B2 (10)
            ['user_id' => 4, 'class_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'class_id' => 10, 'created_at' => now(), 'updated_at' => now()],

            // Karina (user_id=5) ngajar Praktikum Sistem Basis Data (course_id=3), Kom A1 (13), A2 (14)
            ['user_id' => 5, 'class_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 5, 'class_id' => 14, 'created_at' => now(), 'updated_at' => now()],

            // Hermione (user_id=6) ngajar Sistem Basis Data (course_id=3) Kom B1 (15), B2 (16)
            ['user_id' => 6, 'class_id' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'class_id' => 16, 'created_at' => now(), 'updated_at' => now()],

            // Hermione juga ngajar Praktikum Kecerdasan Buatan (course_id=1) Kom A1 (1), A2 (2)
            ['user_id' => 6, 'class_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'class_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}