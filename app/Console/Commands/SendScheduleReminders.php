<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\SemesterPeriod;
use App\Notifications\ScheduleReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendScheduleReminders extends Command
{
    protected $signature = 'schedule:send-reminders';
    protected $description = 'Send notification reminders 30 minutes before scheduled classes';

    public function handle()
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $this->info("🔔 [$timestamp] Starting schedule reminder check...");
        Log::info('=== SCHEDULE REMINDER CHECK START ===', ['timestamp' => $timestamp]);

        try {
            // Get active period
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                $this->warn('⚠️ No active period found');
                Log::warning('No active semester period found');
                return Command::SUCCESS;
            }

            $this->info("✅ Active period: {$activePeriod->formatted_period}");

            // Get current time
            $now = Carbon::now('Asia/Jakarta');
            
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

            $currentDay = $dayMap[$now->format('l')] ?? $now->format('l');
            
            $this->info("📅 Current time: {$now->format('Y-m-d H:i:s')} (Timezone: Asia/Jakarta)");
            $this->info("📋 Current day: {$currentDay}");
            Log::info('Current time: ' . $now->format('Y-m-d H:i:s'));
            Log::info('Current day: ' . $currentDay);

            // Get schedules for today
            $schedules = Schedule::with(['user', 'course', 'class', 'room.building'])
                ->where('period_id', $activePeriod->period_id)
                ->where('day', $currentDay)
                ->where('status', 'active')
                ->get();

            $this->info("📊 Found {$schedules->count()} schedules for {$currentDay}");
            Log::info("Total schedules found for today: {$schedules->count()}");

            if ($schedules->count() === 0) {
                $this->warn("⚠️ No schedules found for {$currentDay}");
                return Command::SUCCESS;
            }

            $notificationsSent = 0;

            foreach ($schedules as $schedule) {
                try {
                    $this->info("\n--- Processing Schedule ID: {$schedule->schedule_id} ---");

                    // Parse start_time
                    $scheduleStartTime = $this->parseStartTime($schedule, $now);
                    
                    if (!$scheduleStartTime) {
                        $this->error("❌ Failed to parse start_time for schedule {$schedule->schedule_id}");
                        continue;
                    }

                    // Calculate minutes until class
                    $minutesUntilClass = $now->diffInMinutes($scheduleStartTime, false);

                    $this->line("Time calculation:");
                    $this->line("  Now: {$now->format('Y-m-d H:i:s')}");
                    $this->line("  Schedule start: {$scheduleStartTime->format('Y-m-d H:i:s')}");
                    $this->line("  Minutes until: {$minutesUntilClass}");
                    $this->line("  In range (29-31)? " . (($minutesUntilClass >= 29 && $minutesUntilClass <= 31) ? 'YES ✅' : 'NO ❌'));

                    Log::info('Checking schedule', [
                        'schedule_id' => $schedule->schedule_id,
                        'course' => $schedule->course->course_name,
                        'user' => $schedule->user->name,
                        'start_time' => $scheduleStartTime->format('H:i'),
                        'current_time' => $now->format('H:i'),
                        'minutes_until_class' => $minutesUntilClass,
                        'in_range' => ($minutesUntilClass >= 29 && $minutesUntilClass <= 31)
                    ]);

                    // Check if in notification range (30 menit sebelum)
                    if ($minutesUntilClass < 29 || $minutesUntilClass > 31) {
                        $this->info("⏭️ Skipping: Outside 29-31 minute window (30 min before)");
                        continue;
                    }

                    $this->line("✅ In range! Checking for duplicates...");

                    // Check if notification already sent for this schedule today
                    $existingNotif = Notification::where('schedule_id', $schedule->schedule_id)
                        ->whereDate('created_at', $now->toDateString())
                        ->first();

                    if ($existingNotif) {
                        $this->info("⏭️ Notification already sent for this schedule today");
                        Log::info('Notification already sent', ['schedule_id' => $schedule->schedule_id]);
                        continue;
                    }

                    $this->line("No duplicate found. Creating notification...");

                    // Create notification record
                    $notif = Notification::create([
                        'user_id' => $schedule->user_id,
                        'schedule_id' => $schedule->schedule_id,
                        'title' => 'Pengingat Jadwal Mengajar',
                        'message' => "Pengingat: Anda mengajar {$schedule->course->course_name} ({$schedule->class->class_name}) di ruang {$schedule->room->room_name} pada pukul {$schedule->time_slot}",
                        'class_status' => 'waiting',
                        'notified_at' => $now
                    ]);

                    $this->line("✅ Notification record created: ID {$notif->notification_id}");

                    // Send notification via queue
                    $this->line("Sending notification broadcast...");
                    $schedule->user->notify(new ScheduleReminderNotification($schedule, $notif));

                    $this->line("✅ Notification sent!");
                    $notificationsSent++;

                    Log::info('Schedule reminder sent', [
                        'schedule_id' => $schedule->schedule_id,
                        'user_id' => $schedule->user_id,
                        'user_name' => $schedule->user->name,
                        'notification_id' => $notif->notification_id,
                        'course' => $schedule->course->course_name,
                        'time' => $schedule->time_slot,
                        'minutes_until_class' => $minutesUntilClass
                    ]);

                } catch (\Exception $e) {
                    $this->error("❌ Error processing schedule {$schedule->schedule_id}: " . $e->getMessage());
                    Log::error('Error sending schedule reminder', [
                        'schedule_id' => $schedule->schedule_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            $this->info("\n" . str_repeat("=", 50));
            $this->info("✅ Schedule reminder check completed. Sent {$notificationsSent} notifications.");
            Log::info('=== SCHEDULE REMINDER CHECK END ===', [
                'notifications_sent' => $notificationsSent
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error in SendScheduleReminders: ' . $e->getMessage());
            Log::error('SendScheduleReminders command error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Parse start_time dari berbagai format
     */
    private function parseStartTime($schedule, $now)
    {
        try {
            $startTime = $schedule->start_time;

            // Jika sudah Carbon object
            if ($startTime instanceof Carbon) {
                $this->line("✓ Detected: Carbon object");
                return $startTime->copy()->setTimezone('Asia/Jakarta');
            }

            // Jika DateTime object
            if ($startTime instanceof \DateTime) {
                $this->line("✓ Detected: DateTime object");
                return Carbon::instance($startTime)->setTimezone('Asia/Jakarta');
            }

            // Jika string
            $this->line("✓ Detected: String");
            $startTimeStr = (string) $startTime;

            // Format: "2025-11-27 08:00:00"
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $startTimeStr)) {
                $this->line("  Format: YYYY-MM-DD HH:mm:ss");
                return Carbon::createFromFormat('Y-m-d H:i:s', $startTimeStr, 'Asia/Jakarta');
            }

            // Format: "08:00:00"
            $this->line("  Format: HH:mm:ss");
            $parsed = Carbon::createFromFormat('H:i:s', $startTimeStr, 'Asia/Jakarta');
            $parsed->setDate($now->year, $now->month, $now->day);
            return $parsed;

        } catch (\Exception $e) {
            Log::error('Failed to parse start_time', [
                'schedule_id' => $schedule->schedule_id,
                'start_time' => $schedule->start_time,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}