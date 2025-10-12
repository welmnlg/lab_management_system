<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->insert([
        ['room_name' => 'Multimedia 1', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Multimedia 2', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Multimedia 3', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Pemrograman 1', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Pemrograman 2', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Pemrograman 3', 'location' => 'Gedung C', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 1', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 2', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Jaringan 3', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
        ['room_name' => 'Keamanan 1', 'location' => 'Gedung D', 'created_at' => now(), 'updated_at' => now()],
    ]);
    }
}
