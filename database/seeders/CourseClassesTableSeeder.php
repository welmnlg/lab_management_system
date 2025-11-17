<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('course_classes')->insert([
        // Austin Butler, BPH+ASLAB, TI, TIF2207 - Minerva Mcgonaggal - Selasa
        ['class_name' => 'Kom A1', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A2', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B1', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B2', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],

        // Kim Mingyu, ASLAB only, TI, TIF2209 - Filius Flitwick - Rabu
        ['class_name' => 'Kom A1', 'course_id' => 2, 'lecturer' => 'Filius Flitwick', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A2', 'course_id' => 2, 'lecturer' => 'Filius Flitwick', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom C1', 'course_id' => 2, 'lecturer' => 'Filius Flitwick', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom C2', 'course_id' => 2, 'lecturer' => 'Filius Flitwick', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A1', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A2', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B1', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B2', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom C1', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom C2', 'course_id' => 5, 'lecturer' => 'Sarah Purnawati', 'created_at' => now(), 'updated_at' => now()],

        // Wil Ohmsford, ASLAB only, TI, TIF2209 - Albus Dumbledore - Selasa
        ['class_name' => 'Kom B1', 'course_id' => 2, 'lecturer' => 'Albus Dumbledore', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B2', 'course_id' => 2, 'lecturer' => 'Albus Dumbledore', 'created_at' => now(), 'updated_at' => now()],

        // Karina, BPH+ASLAB, TI, TIF1206 - Severus Snape
        ['class_name' => 'Kom A1', 'course_id' => 3, 'lecturer' => 'Severus Snape', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A2', 'course_id' => 3, 'lecturer' => 'Severus Snape', 'created_at' => now(), 'updated_at' => now()],

        // Hermione, BPH+ASLAB, TI, TIF1206 & TIF2207 - Severus Snape & Minerva Mcgonaggal
        ['class_name' => 'Kom B1', 'course_id' => 3, 'lecturer' => 'Severus Snape', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B2', 'course_id' => 3, 'lecturer' => 'Severus Snape', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A1', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom A2', 'course_id' => 1, 'lecturer' => 'Minerva Mcgonaggal', 'created_at' => now(), 'updated_at' => now()],

        // Feyd Rautha, Ilmu Komputer, ILK1206 - Albus Dumbledore
        ['class_name' => 'Kom B1', 'course_id' => 4, 'lecturer' => 'Albus Dumbledore', 'created_at' => now(), 'updated_at' => now()],
        ['class_name' => 'Kom B2', 'course_id' => 4, 'lecturer' => 'Albus Dumbledore', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
