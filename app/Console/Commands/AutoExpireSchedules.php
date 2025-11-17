<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\RoomOccupancyStatus;
use App\Models\Logbook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoExpireSchedules extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'schedules:auto-expire';

    /**
     * The console command description.
     */
    protected $description = 'Automatically expire schedules and release rooms when schedule time has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting auto-expire schedules process...');
        
        $now = Carbon::now();
        $currentDayName = $this->getDayNameInIndonesian($now->dayOfWeek);
        $currentTime = $now->format('H:i:s');
        
        $this->info("Current time: {$currentDayName}, {$currentTime}");

        // ===== STEP 1: Find all schedules for today that have passed =====
        $expiredSchedules = Schedule::where('day', $currentDayName)
            ->whereTime('end_time', '<', $currentTime)
            ->with(['user', 'room', 'courseClass.course'])
            ->get();

        if ($expiredSchedules->isEmpty()) {
            $this->info('✅ No expired schedules found.');
            return 0;
        }

        $this->info("Found {$expiredSchedules->count()} expired schedule(s).");

        $releasedRooms = 0;
        $updatedLogbooks = 0;
        $createdAbsentLogbooks = 0;

        foreach ($expiredSchedules as $schedule) {
            try {
                DB::beginTransaction();

                // ===== Check if room is still occupied by this schedule =====
                $occupancy = RoomOccupancyStatus::where('room_id', $schedule->room_id)
                    ->where('current_user_id', $schedule->user_id)
                    ->where('is_active', true)
                    ->first();

                if ($occupancy) {
                    // ✅ Release room
                    $occupancy->update([
                        'is_active' => false,
                        'ended_at' => now()
                    ]);

                    $this->warn("  ⚠️  Released room: {$schedule->room->room_name} (User: {$schedule->user->name})");
                    $releasedRooms++;
                }

                // ===== Check if there's a logbook entry for today =====
                $logbook = Logbook::where('user_id', $schedule->user_id)
                    ->where('room_id', $schedule->room_id)
                    ->where('schedule_id', $schedule->schedule_id)
                    ->whereDate('date', today())
                    ->first();

                if ($logbook) {
                    // ✅ Logbook exists - mark as completed if not already
                    if (is_null($logbook->logout)) {
                        $logbook->update([
                            'logout' => $schedule->end_time,
                            'status' => 'SELESAI',
                            'notes' => 'Auto-closed by system after schedule ended'
                        ]);

                        $this->info("  ✅ Updated logbook for {$schedule->user->name} - {$schedule->room->room_name}");
                        $updatedLogbooks++;
                    }
                } else {
                    // ❌ No logbook = User did NOT attend
                    Logbook::create([
                        'user_id' => $schedule->user_id,
                        'room_id' => $schedule->room_id,
                        'schedule_id' => $schedule->schedule_id,
                        'course_id' => $schedule->courseClass->course_id ?? null,
                        'date' => today(),
                        'login' => null,
                        'logout' => null,
                        'activity' => 'TIDAK HADIR',
                        'status' => 'TIDAK HADIR',
                        'notes' => 'Auto-generated: No attendance recorded for this schedule',
                        'entry_method' => 'AUTO_SYSTEM'
                    ]);

                    $this->error("  ❌ Created TIDAK HADIR logbook for {$schedule->user->name} - {$schedule->room->room_name}");
                    $createdAbsentLogbooks++;
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("  ❌ Error processing schedule ID {$schedule->schedule_id}: {$e->getMessage()}");
                Log::error('Auto-expire schedule error', [
                    'schedule_id' => $schedule->schedule_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // ===== Summary =====
        $this->newLine();
        $this->info('📊 Summary:');
        $this->info("  - Released rooms: {$releasedRooms}");
        $this->info("  - Updated logbooks: {$updatedLogbooks}");
        $this->info("  - Created absent logbooks: {$createdAbsentLogbooks}");
        $this->newLine();
        $this->info('✅ Auto-expire process completed!');

        return 0;
    }

    /**
     * Helper: Get Indonesian day name from day number
     */
    private function getDayNameInIndonesian(int $dayNumber): string
    {
        $days = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];

        return $days[$dayNumber] ?? 'Senin';
    }
}