<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\SemesterPeriod;
use App\Notifications\ScheduleReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendScheduleReminders extends Command
{
    protected $signature = 'schedule:send-reminders';
    protected $description = 'Send notification reminders 30 minutes before scheduled classes';

    public function handle()
    {
        $this->info('Starting schedule reminder check...');

        try {
            // Get active period
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                $this->info('No active period found');
                return;
            }

            // Get current time
            $now = Carbon::now();
            $reminderTime = $now->copy()->addMinutes(30);

            // Get day name in Indonesian
            $dayMap = [
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            ];

            $currentDay = $dayMap[$now->format('l')];
            
            $this->info("Current time: {$now}");
            $this->info("Looking for classes at: {$reminderTime->format('H:i')} on {$currentDay}");

            // Find schedules that match the 30-minute reminder time
            // Convert time to format "HH.MM"
            $reminderTimeSlot = $reminderTime->format('H.i');

            $schedules = Schedule::with(['user', 'course', 'class', 'room.building'])
                ->where('period_id', $activePeriod->period_id)
                ->where('day', $currentDay)
                ->where('status', 'active')
                ->get()
                ->filter(function ($schedule) use ($reminderTimeSlot) {
                    // Parse time slot "08.00 - 08:50"
                    $parts = explode(' - ', $schedule->time_slot);
                    if (count($parts) === 2) {
                        $startTime = trim($parts[0]); // "08.00"
                        // Normalize to compare (convert 08.00 to 08:00 if needed)
                        $startTime = str_replace('.', ':', $startTime);
                        $reminderTimeCompare = str_replace('.', ':', $reminderTimeSlot);
                        
                        return $startTime === $reminderTimeCompare;
                    }
                    return false;
                });

            $this->info("Found " . count($schedules) . " schedules to notify");

            foreach ($schedules as $schedule) {
                try {
                    // Check if notification already sent for this schedule today
                    $existingNotif = Notification::where('schedule_id', $schedule->schedule_id)
                        ->whereDate('created_at', today())
                        ->first();

                    if ($existingNotif) {
                        $this->info("Notification already sent for schedule {$schedule->schedule_id}");
                        continue;
                    }

                    // Create notification record
                    $notif = Notification::create([
                        'user_id' => $schedule->user_id,
                        'schedule_id' => $schedule->schedule_id,
                        'title' => 'Pengingat Jadwal Mengajar',
                        'message' => "Pengingat: Anda mengajar {$schedule->course->course_name} ({$schedule->class->class_name}) di ruang {$schedule->room->room_name} pada pukul {$schedule->time_slot}",
                        'class_status' => 'waiting',
                        'notified_at' => $now
                    ]);

                    // Send notification via queue
                    $schedule->user->notify(new ScheduleReminderNotification($schedule, $notif));

                    $this->info("Notification sent for schedule {$schedule->schedule_id} to user {$schedule->user->name}");

                    Log::info('Schedule reminder sent', [
                        'schedule_id' => $schedule->schedule_id,
                        'user_id' => $schedule->user_id,
                        'notification_id' => $notif->notification_id,
                        'course' => $schedule->course->course_name,
                        'time' => $schedule->time_slot
                    ]);

                } catch (\Exception $e) {
                    $this->error("Error sending notification for schedule {$schedule->schedule_id}: " . $e->getMessage());
                    Log::error('Error sending schedule reminder', [
                        'schedule_id' => $schedule->schedule_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $this->info('Schedule reminder check completed');

        } catch (\Exception $e) {
            $this->error('Error in SendScheduleReminders: ' . $e->getMessage());
            Log::error('SendScheduleReminders command error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}