<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\SemesterPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendMissedNotifications extends Command
{
    protected $signature = 'notifications:send-missed 
                            {--hours=2 : Check schedules starting within this many hours}';

    protected $description = 'Send missed notifications for schedules that haven\'t been notified yet';

    public function handle()
    {
        $this->info('🔍 Checking for missed notifications...');
        
        $hours = (int) $this->option('hours');
        
        // Get active period
        $activePeriod = SemesterPeriod::getActivePeriod();
        
        if (!$activePeriod) {
            $this->warn('⚠️  No active period found.');
            return Command::SUCCESS;
        }
        
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
        
        // Find schedules starting soon (within X hours) that haven't been notified
        $timeStart = $now->format('H:i:s');
        $timeEnd = $now->copy()->addHours($hours)->format('H:i:s');
        
        $this->info("📅 Current day: {$currentDay}");
        $this->info("⏰ Current time: {$now->format('H:i')}");
        $this->info("🔍 Looking for schedules between now and {$now->copy()->addHours($hours)->format('H:i')}");
        $this->newLine();
        
        $schedules = Schedule::with(['user', 'class', 'room'])
            ->where('period_id', $activePeriod->period_id)
            ->where('day', $currentDay)
            ->whereIn('status', ['terjadwal', 'dikonfirmasi'])
            ->whereBetween('start_time', [$timeStart, $timeEnd])
            ->get();
        
        $this->info("📊 Found {$schedules->count()} upcoming schedule(s)");
        
        $sentCount = 0;
        $skippedCount = 0;
        
        foreach ($schedules as $schedule) {
            // Check if notification already exists for today
            $existingNotif = Notification::where('schedule_id', $schedule->schedule_id)
                ->whereDate('created_at', $now->toDateString())
                ->first();
            
            if ($existingNotif) {
                $this->info("⏭️  Already notified: Schedule #{$schedule->schedule_id}");
                $skippedCount++;
                continue;
            }
            
            // Create missed notification
            try {
                $className = $schedule->class->class_name ?? 'Kelas tidak diketahui';
                $courseName = $schedule->class->course->course_name ?? 'Mata kuliah tidak diketahui';
                $roomName = $schedule->room->room_name ?? 'Ruangan tidak diketahui';
                $startTime = Carbon::parse($schedule->start_time)->format('H:i');
                $endTime = Carbon::parse($schedule->end_time)->format('H:i');
                $userName = $schedule->user->name ?? 'User';
                
                // Check if schedule is very soon (less than 30 minutes)
                $minutesUntilStart = $now->diffInMinutes(Carbon::parse($schedule->start_time), false);
                
                if ($minutesUntilStart < 0) {
                    $this->warn("⏰ Schedule #{$schedule->schedule_id} already started, skipping");
                    continue;
                }
                
                $urgencyText = $minutesUntilStart < 30 
                    ? " ⚠️ SEGERA! Kelas dimulai dalam {$minutesUntilStart} menit!" 
                    : "";
                
                $notif = Notification::create([
                    'user_id' => $schedule->user_id,
                    'schedule_id' => $schedule->schedule_id,
                    'title' => 'Pengingat Jadwal Praktikum',
                    'message' => "Pengingat: Anda memiliki jadwal praktikum {$courseName} ({$className}) di {$roomName} pada pukul {$startTime}-{$endTime}.{$urgencyText} Silahkan konfirmasi kehadiran Anda di halaman Profil.",
                    'class_status' => 'waiting',
                    'notified_at' => $now
                ]);
                
                $sentCount++;
                $this->info("✅ Notification sent to {$userName} for {$className} at {$startTime} (in {$minutesUntilStart} mins)");
                
                Log::info('Missed notification sent', [
                    'user_id' => $schedule->user_id,
                    'schedule_id' => $schedule->schedule_id,
                    'class' => $className,
                    'minutes_until_start' => $minutesUntilStart
                ]);
                
            } catch (\Exception $e) {
                $this->error("❌ Error: {$e->getMessage()}");
                Log::error('Error sending missed notification', [
                    'schedule_id' => $schedule->schedule_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $this->newLine();
        $this->info("✅ Process completed!");
        $this->info("   📤 Sent: {$sentCount}");
        $this->info("   ⏭️  Skipped: {$skippedCount}");
        
        return Command::SUCCESS;
    }
}
