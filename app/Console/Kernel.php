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
        // ✅ Send notification reminders - runs every minute
        // For production: use everyMinute() with Windows Task Scheduler
        // For testing: command can be called manually or via loop scheduler
        $schedule->command('schedule:send-reminders')
            ->everyMinute()
            ->withoutOverlapping();
        
        // ✅ Auto-expire schedules every 5 minutes
        $schedule->command('schedules:auto-expire')
            ->everyFiveMinutes()
            ->withoutOverlapping();
        
        // Process queued jobs every 5 minutes
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