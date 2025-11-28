<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        // No need to check for buildings as we use location string directly

        DB::table('rooms')->insert([
        ['room_name' => 'Jaringan 1', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 2', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 3', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 4', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}