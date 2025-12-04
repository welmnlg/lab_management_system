<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SemesterPeriodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('semester_periods')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $periods = [
            // Semester Ganjil 2024/2025 (Completed)
            [
                'semester_type' => 'Ganjil',
                'academic_year' => '2024/2025',
                'start_date' => '2024-09-01',
                'end_date' => '2025-01-31',
                'schedule_start_date' => '2024-08-15',
                'schedule_end_date' => '2024-08-30',
                'is_active' => false,
                'is_schedule_open' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Semester Genap 2024/2025 (Completed)
            [
                'semester_type' => 'Genap',
                'academic_year' => '2024/2025',
                'start_date' => '2025-02-01',
                'end_date' => '2025-06-30',
                'schedule_start_date' => '2025-01-15',
                'schedule_end_date' => '2025-01-30',
                'is_active' => false,
                'is_schedule_open' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Semester Ganjil 2025/2026 (ACTIVE - Current Period)
            [
                'semester_type' => 'Ganjil',
                'academic_year' => '2025/2026',
                'start_date' => '2025-12-10',
                'end_date' => '2026-05-10',
                'schedule_start_date' => '2025-12-01',
                'schedule_end_date' => '2025-12-04',
                'is_active' => true,  // ✓ AKTIF
                'is_schedule_open' => false,  // Tidak manual override, tapi akan otomatis open berdasarkan tanggal
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];

        foreach ($periods as $period) {
            DB::table('semester_periods')->insert($period);
        }

        $this->command->info('✅ Semester Periods seeded successfully!');
        $this->command->info('   - 2 completed periods (2024/2025)');
        $this->command->info('   - 1 active period (Ganjil 2025/2026)');
    }
}
