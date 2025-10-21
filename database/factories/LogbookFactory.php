<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Room;
use App\Models\Course;
use App\Models\Schedule;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logbook>
 */
class LogbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $loginTime = $this->faker->time('H:i:s', '16:00:00');
        $logoutTime = $this->faker->optional(0.9)->time('H:i:s', '18:00:00');

        return [
            'user_id' => User::factory(),
            'schedule_id' => $this->faker->optional(0.7)->randomElement(Schedule::pluck('schedule_id')),
            'override_id' => null,
            'room_id' => Room::factory(),
            'course_id' => Course::factory(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'login' => $loginTime,
            'logout' => $logoutTime,
            'activity' => $this->faker->randomElement(['MENGAJAR']),
            'status' => $this->faker->optional(0.7)->randomElement(['GANTI RUANGAN', 'SELESAI']),
        ];
    }

    /**
     * Indicate that the logbook is still active (no logout time).
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'logout' => null,
                'status' => null,
            ];
        });
    }

    /**
     * Indicate that the logbook is for teaching activity.
     */
    public function teaching()
    {
        return $this->state(function (array $attributes) {
            return [
                'activity' => 'MENGAJAR',
            ];
        });
    }

    /**
     * Indicate that the logbook is for learning activity.
     */
    public function learning()
    {
        return $this->state(function (array $attributes) {
            return [
                'activity' => 'BELAJAR',
            ];
        });
    }
}
