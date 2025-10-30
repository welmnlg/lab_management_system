<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        ProgramsTableSeeder::class,
        RolesTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
        CoursesTableSeeder::class,
        CourseClassesTableSeeder::class,
        UserCoursesTableSeeder::class,
        RoomsTableSeeder::class,
        SchedulesTableSeeder::class,
        ScheduleOverridesTableSeeder::class,
        OctoberScheduleSeeder::class,
        NotificationsTableSeeder::class,
        LogbookSeeder::class,
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
