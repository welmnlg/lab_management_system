<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedules')->insert([
            // LAB JARINGAN 1
            [
                'class_id' => 1,
                'user_id' => 1, // Austin Butler
                'room_id' => 1,
                'day' => 'Senin',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 2,
                'user_id' => 1,
                'room_id' => 1,
                'day' => 'Selasa',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 3,
                'user_id' => 1,
                'room_id' => 1,
                'day' => 'Rabu',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            

            // === LAB 2 ===
            [
                'class_id' => 5,
                'user_id' => 2,
                'room_id' => 4,
                'day' => 'Selasa',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 6,
                'user_id' => 2,
                'room_id' => 2,
                'day' => 'Rabu',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 7,
                'user_id' => 2,
                'room_id' => 2,
                'day' => 'Kamis',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 8,
                'user_id' => 2,
                'room_id' => 2,
                'day' => 'Jumat',
                'start_time' => '14:40:00',
                'end_time' => '16:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 9,
                'user_id' => 3, // Kim Mingyu
                'room_id' => 2, // Jaringan 2
                'day' => 'Senin',
                'start_time' => '07:30:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'class_id' => 10,
                'user_id' => 3, // Kim Mingyu
                'room_id' => 2, // Jaringan 2
                'day' => 'Senin',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'class_id' => 11,
                'user_id' => 3, // Kim Mingyu
                'room_id' => 2, // Jaringan 2
                'day' => 'Senin',
                'start_time' => '11:20:00',
                'end_time' => '13:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'class_id' => 12,
                'user_id' => 3, // Kim Mingyu
                'room_id' => 2, // Jaringan 2
                'day' => 'Senin',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'class_id' => 13,
                'user_id' => 3, // Kim Mingyu
                'room_id' => 2, // Jaringan 2
                'day' => 'Senin',
                'start_time' => '20:50:00',
                'end_time' => '23:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // === LAB 3 ===
            [
                'class_id' => 14,
                'user_id' => 3,
                'room_id' => 4,
                'day' => 'Rabu',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 15,
                'user_id' => 3,
                'room_id' => 3,
                'day' => 'Kamis',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 16,
                'user_id' => 4,
                'room_id' => 2,
                'day' => 'Kamis',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 17,
                'user_id' => 4,
                'room_id' => 3,
                'day' => 'Jumat',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // === LAB 5 ===
            [
                'class_id' => 18,
                'user_id' => 5,
                'room_id' => 4,
                'day' => 'Jumat',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 19,
                'user_id' => 5,
                'room_id' => 4,
                'day' => 'Senin',
                'start_time' => '09:40:00',
                'end_time' => '11:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],          
            [
                'class_id' => 20,
                'user_id' => 6,
                'room_id' => 1,
                'day' => 'Kamis',
                'start_time' => '13:00:00',
                'end_time' => '14:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 21,
                'user_id' => 6,
                'room_id' => 4,
                'day' => 'Kamis',
                'start_time' => '14:40:00',
                'end_time' => '16:20:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}