<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                'name' => 'Teknologi Informasi',
                'faculty' => 'Ilmu Komputer dan Teknologi Informasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
