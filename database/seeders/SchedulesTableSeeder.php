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
            [
                'class_id' => 1,
                'user_id' => 1, // Austin Butler
                'room_id' => 1,
                'day' => 'Selasa',
                'start_time' => '08:00:00',
                'end_time' => '09:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'class_id' => 13,
                'user_id' => 6, // Hermione Granger
                'room_id' => 2,
                'day' => 'Rabu',
                'start_time' => '10:00:00',
                'end_time' => '11:40:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}