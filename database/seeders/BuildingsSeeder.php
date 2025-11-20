<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingsSeeder extends Seeder
{
    public function run(): void
    {
        $faculty = DB::table('programs')
            ->where('faculty', 'Ilmu Komputer dan Teknologi Informasi')
            ->first();

        DB::table('buildings')->insert([
            [
                'building_name' => 'Gedung C',
                'building_code' => 'C',
                'faculty_id' => $faculty->id, // sekarang id, bukan building_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'building_name' => 'Gedung D', 
                'building_code' => 'D',
                'faculty_id' => $faculty->id, // sekarang id, bukan building_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}