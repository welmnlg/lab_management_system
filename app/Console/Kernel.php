<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run every minute to check for schedules
        $schedule->command('schedule:send-reminders')
            ->everyMinute()
            ->name('schedule_reminders')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('SendScheduleReminders command failed');
            })
            ->onSuccess(function () {
                \Log::info('SendScheduleReminders command executed successfully');
            });

        // Process queued jobs
        $schedule->command('queue:work --max-jobs=1000 --max-time=3600')
            ->everyFiveMinutes()
            ->name('queue_worker')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}