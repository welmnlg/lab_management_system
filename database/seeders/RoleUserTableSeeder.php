<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_user')->insert([
            // Austin Butler (user_id=1) → BPH + Aslab
            ['user_id' => 1, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            ['user_id' => 1, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()], // bph
            
            // Feyd-Rautha Harkonnen (user_id=2) → BPH + Aslab
            ['user_id' => 2, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            ['user_id' => 2, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()], // bph
            
            // Kim Mingyu (user_id=3) → Aslab only
            ['user_id' => 3, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            
            // Wil Ohmsford (user_id=4) → Aslab only
            ['user_id' => 4, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            
            // Karina (user_id=5) → BPH + Aslab
            ['user_id' => 5, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            ['user_id' => 5, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()], // bph
            
            // Hermione Granger (user_id=6) → BPH + Aslab
            ['user_id' => 6, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()], // aslab
            ['user_id' => 6, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()], // bph
        ]);
    }
}