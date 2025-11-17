<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserCoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_courses')->insert([
            // Austin Butler (user_id=1) ngajar Praktikum Kecerdasan Buatan (course_id=1)
            // ngajar Kom A1, A2, B1, B2 kelas dengan class_id 1,2,3,4
            ['user_id' => 1, 'class_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'class_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Feyd-Rautha (user_id=2) ngajar Praktikum Computer Vision (course_id=4) Kom B1,B2 class_id 15,16
            ['user_id' => 2, 'class_id' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 2, 'class_id' => 16, 'created_at' => now(), 'updated_at' => now()],
            
            // Kim Mingyu (user_id=3) ngajar Praktikum Keamanan Server dan Jaringan (course_id=2)
            // ngajar Kom A1,A2,C1,C2 class_id 5,6,7,8
            ['user_id' => 3, 'class_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 3, 'class_id' => 9, 'created_at' => now(), 'updated_at' => now()], // Kom A1
            ['user_id' => 3, 'class_id' => 10, 'created_at' => now(), 'updated_at' => now()], // Kom A2
            ['user_id' => 3, 'class_id' => 11, 'created_at' => now(), 'updated_at' => now()], // Kom C1
            ['user_id' => 3, 'class_id' => 12, 'created_at' => now(), 'updated_at' => now()], // Kom C2
            ['user_id' => 3, 'class_id' => 13, 'created_at' => now(), 'updated_at' => now()], // Kom B1 


            // Wil Ohmsford (user_id=4) ngajar Keamanan Server Kom B1,B2 class_id 9,10
            ['user_id' => 4, 'class_id' => 14, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 4, 'class_id' => 15, 'created_at' => now(), 'updated_at' => now()],

            // Karina (user_id=5) ngajar Praktikum Sistem Basis Data (course_id=3), Kom A1,A2 class_id 11,12
            ['user_id' => 5, 'class_id' => 16, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 5, 'class_id' => 17, 'created_at' => now(), 'updated_at' => now()],

            // Hermione (user_id=6) ngajar Sistem Basis Data Kom B1,B2 class_id 13,14
            ['user_id' => 6, 'class_id' => 18, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'class_id' => 19, 'created_at' => now(), 'updated_at' => now()],

            // Hermione juga ngajar Praktikum Kecerdasan Buatan kelas Kom A1,A2 class_id 1,2
            ['user_id' => 6, 'class_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 6, 'class_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}