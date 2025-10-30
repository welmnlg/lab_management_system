<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Room;
use App\Models\Course;
use App\Models\Schedule;
use Carbon\Carbon;

class LogbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data referensi
        $users = User::all();
        $rooms = Room::all();
        $courses = Course::all();
        $schedules = Schedule::all();

        if ($users->isEmpty() || $rooms->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('Please run Users, Rooms, and Courses seeders first!');
            return;
        }

        // Data sample logbook untuk minggu ini
        $activities = ['MENGAJAR'];
        $statuses = ['GANTI RUANGAN', 'SELESAI', null];

        for ($i = 0; $i < 50; $i++) {
            $loginTime = $this->randomTime('08:00', '16:00');
            $logoutTime = $this->addRandomMinutes($loginTime, 60, 180);
            
            Logbook::create([
                'user_id' => $users->random()->user_id,
                'schedule_id' => $schedules->isNotEmpty() ? $schedules->random()->schedule_id : null,
                'override_id' => null, // Bisa diisi jika ada data schedule_overrides
                'room_id' => $rooms->random()->room_id,
                'course_id' => $courses->random()->course_id,
                'date' => Carbon::now()->subDays(rand(0, 14))->toDateString(),
                'login' => $loginTime,
                'logout' => rand(0, 10) === 0 ? null : $logoutTime, // 10% chance null (belum logout)
                'activity' => $activities[array_rand($activities)],
                'status' => $statuses[array_rand($statuses)],
            ]);
        }
    }

    /**
     * Generate random time between start and end time
     */
    private function randomTime($start, $end)
    {
        $startTime = strtotime($start);
        $endTime = strtotime($end);
        $randomTime = rand($startTime, $endTime);
        return date('H:i:s', $randomTime);
    }

    /**
     * Add random minutes to time
     */
    private function addRandomMinutes($time, $minMinutes, $maxMinutes)
    {
        $timestamp = strtotime($time);
        $additionalMinutes = rand($minMinutes, $maxMinutes);
        return date('H:i:s', $timestamp + ($additionalMinutes * 60));
    }
}
