<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\SemesterPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendScheduleReminders extends Command
{
    protected $signature = 'schedule:send-reminders';
    protected $description = 'Send notification reminders 30 minutes before scheduled classes';

    public function handle()
    {
        $this->info('🔔 Starting schedule reminder check...');

        try {
            // Get active period
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                $this->warn('⚠️  No active period found. Skipping notification check.');
                return Command::SUCCESS;
            }

            // Get current time
            $now = Carbon::now();
            
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
            
            // Calculate time window for notifications (25-35 minutes from now)
            // Wider window to ensure we don't miss any due to second differences
            $targetTimeStart = $now->copy()->addMinutes(25);
            $targetTimeEnd = $now->copy()->addMinutes(35);
            
            $this->info("📅 Current time: {$now->format('Y-m-d H:i:s')}");
            $this->info("📅 Current day: {$currentDay}");
            $this->info("🔍 Looking for classes starting between {$targetTimeStart->format('H:i:s')} - {$targetTimeEnd->format('H:i:s')}");

            // Find schedules that start in ~30 minutes
            $schedules = Schedule::with(['user', 'class', 'room'])
                ->where('period_id', $activePeriod->period_id)
                ->where('day', $currentDay)
                ->whereIn('status', ['terjadwal', 'dikonfirmasi']) // Only active schedules
                ->whereBetween('start_time', [
                    $targetTimeStart->format('H:i:s'),
                    $targetTimeEnd->format('H:i:s')
                ])
                ->get();

            $this->info("📊 Found {$schedules->count()} schedule(s) starting in ~30 minutes");

            $sentCount = 0;
            $skippedCount = 0;

            foreach ($schedules as $schedule) {
                try {
                    // Check if notification already sent for this schedule today
                    $existingNotif = Notification::where('schedule_id', $schedule->schedule_id)
                        ->whereDate('created_at', $now->toDateString())
                        ->first();

                    if ($existingNotif) {
                        $this->warn("⏭️  Notification already sent for schedule #{$schedule->schedule_id}");
                        $skippedCount++;
                        continue;
                    }

                    // Get schedule details
                    $className = $schedule->class->class_name ?? 'Kelas tidak diketahui';
                    $courseName = $schedule->class->course->course_name ?? 'Mata kuliah tidak diketahui';
                    $roomName = $schedule->room->room_name ?? 'Ruangan tidak diketahui';
                    $startTime = Carbon::parse($schedule->start_time)->format('H:i');
                    $endTime = Carbon::parse($schedule->end_time)->format('H:i');
                    $userName = $schedule->user->name ?? 'User';

                    // Create notification record
                    $notif = Notification::create([
                        'user_id' => $schedule->user_id,
                        'schedule_id' => $schedule->schedule_id,
                        'title' => 'Pengingat Jadwal Praktikum',
                        'message' => "Pengingat: Anda memiliki jadwal praktikum {$courseName} ({$className}) di {$roomName} pada pukul {$startTime}-{$endTime}. Silahkan konfirmasi kehadiran Anda di halaman Profil.",
                        'class_status' => 'waiting',
                        'notified_at' => $now
                    ]);

                    $sentCount++;
                    $this->info("✅ Notification sent to {$userName} for {$className} at {$startTime}");

                    // Log for debugging
                    Log::info('Schedule reminder sent', [
                        'user_id' => $schedule->user_id,
                        'user_name' => $userName,
                        'schedule_id' => $schedule->schedule_id,
                        'notification_id' => $notif->notification_id,
                        'class' => $className,
                        'room' => $roomName,
                        'time' => "{$startTime}-{$endTime}",
                        'day' => $currentDay
                    ]);

                } catch (\Exception $e) {
                    $this->error("❌ Error sending notification for schedule #{$schedule->schedule_id}: {$e->getMessage()}");
                    Log::error('Error sending schedule reminder', [
                        'schedule_id' => $schedule->schedule_id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            $this->newLine();
            $this->info("✅ Process completed!");
            $this->info("   📤 Sent: {$sentCount}");
            $this->info("   ⏭️  Skipped: {$skippedCount}");
            $this->info("   📊 Total: {$schedules->count()}");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Fatal error in SendScheduleReminders: {$e->getMessage()}");
            Log::error('SendScheduleReminders command error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
}