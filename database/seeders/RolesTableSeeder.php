<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
        [
            'status' => 'aslab',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'status' => 'bph',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
    }
}
