<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
        // User yang punya peran BPH + ASLAB, password 'admin123'
        ['name' => 'Austin Butler', 'nim' => '221402001', 'email' => 'austin@example.com', 'password' => bcrypt('admin123'), 'program_studi' => 1, 'created_at' => now(),'updated_at' => now()],
        ['name' => 'Kim Mingyu', 'nim' => '221402002', 'email' => 'kim@example.com', 'password' => bcrypt('admin123'), 'program_studi' => 1, 'created_at' => now(),'updated_at' => now()],

        // User yang hanya ASLAB, password 'aslab123'
        ['name' => 'Karina', 'nim' => '221402003', 'email' => 'karina@example.com', 'password' => bcrypt('aslab123'), 'program_studi' => 1, 'created_at' => now(),'updated_at' => now()],
        ['name' => 'Hermione Granger', 'nim' => '221402005', 'email' => 'hermione@example.com', 'password' => bcrypt('aslab123'), 'program_studi' => 1, 'created_at' => now(),'updated_at' => now()],
    ]);
    }
}
