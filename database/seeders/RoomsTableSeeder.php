<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Dapatkan building_id berdasarkan nama gedung
        $gedungC = DB::table('buildings')->where('building_code', 'C')->first();
        $gedungD = DB::table('buildings')->where('building_code', 'D')->first();

        if (!$gedungC || !$gedungD) {
            throw new \Exception('Buildings C or D not found');
        }

        DB::table('rooms')->insert([
            // Rooms di Gedung C - GUNAKAN building_id BUKAN id
            ['room_name' => 'Multimedia 1', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Multimedia 2', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Multimedia 3', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Pemrograman 1', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Pemrograman 2', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Pemrograman 3', 'building_id' => $gedungC->building_id, 'created_at' => now(), 'updated_at' => now()],
            
            // Rooms di Gedung D - GUNAKAN building_id BUKAN id
            ['room_name' => 'Jaringan 1', 'building_id' => $gedungD->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Jaringan 2', 'building_id' => $gedungD->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Jaringan 3', 'building_id' => $gedungD->building_id, 'created_at' => now(), 'updated_at' => now()],
            ['room_name' => 'Keamanan 1', 'building_id' => $gedungD->building_id, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}