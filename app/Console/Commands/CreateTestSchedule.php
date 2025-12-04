<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\SemesterPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateTestSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-schedule 
                            {minutes=30 : Minutes from now when the schedule should start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test schedule for notification testing (starts X minutes from now)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutesFromNow = (int) $this->argument('minutes');
        
        $this->info("🧪 Creating test schedule...");
        
        // Get active period
        $activePeriod = SemesterPeriod::getActivePeriod();
        
        if (!$activePeriod) {
            $this->error('❌ No active period found. Please activate a semester period first.');
            return Command::FAILURE;
        }
        
        $this->info("✅ Using active period: {$activePeriod->semester_type} {$activePeriod->academic_year}");
        
        // Get current time + X minutes
        $now = Carbon::now();
        $startTime = $now->copy()->addMinutes($minutesFromNow);
        $endTime = $startTime->copy()->addMinutes(100); // Standard 100-minute class
        
        // Get Indonesian day name
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $dayIndo = $dayMap[$startTime->format('l')];
        
        // Get a random user, class, and room for testing
        // ✅ Get specific user (Kim Mingyu / ID 2)
        $user = DB::table('users')->where('user_id', 2)->first();
        
        // Fallback if ID 2 not found
        if (!$user) {
            $user = DB::table('users')->first();
            $this->warn("⚠️ User ID 2 not found, using {$user->name} instead.");
        }

        $class = DB::table('course_classes')->first();
        $room = DB::table('rooms')->first();
        
        if (!$user || !$class || !$room) {
            $this->error('❌ Missing required data. Please ensure you have users, classes, and rooms in the database.');
            return Command::FAILURE;
        }
        
        // Create test schedule
        try {
            $schedule = Schedule::create([
                'period_id' => $activePeriod->period_id,
                'user_id' => $user->user_id,
                'class_id' => $class->class_id,
                'room_id' => $room->room_id,
                'day' => $dayIndo,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => 'terjadwal',
            ]);
            
            $this->newLine();
            $this->info("✅ Test schedule created successfully!");
            $this->newLine();
            
            $this->table(
                ['Field', 'Value'],
                [
                    ['Schedule ID', $schedule->schedule_id],
                    ['User', $user->name],
                    ['Class', $class->class_name],
                    ['Room', $room->room_name],
                    ['Day', $dayIndo],
                    ['Start Time', $startTime->format('H:i')],
                    ['End Time', $endTime->format('H:i')],
                    ['Status', $schedule->status],
                ]
            );
            
            $this->newLine();
            $this->info("📅 Current time: {$now->format('Y-m-d H:i:s')}");
            $this->info("⏰ Schedule starts at: {$startTime->format('Y-m-d H:i:s')}");
            $this->info("🔔 Notification should be sent at: {$startTime->copy()->subMinutes(30)->format('Y-m-d H:i:s')}");
            
            if ($minutesFromNow == 30) {
                $this->newLine();
                $this->warn("⚠️  This schedule will start in 30 minutes!");
                $this->info("💡 Run the reminder command now to test:");
                $this->info("   php artisan schedule:send-reminders");
            } else {
                $this->newLine();
                $this->info("💡 To test notification immediately, create a schedule starting in 30 minutes:");
                $this->info("   php artisan test:create-schedule 30");
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create test schedule: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
