<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class RunSlotScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:run-slots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scheduler ONLY at specific class intervals (every 100 mins starting 07:30)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🎓 SLOT BASED SCHEDULER STARTED");
        $this->info("Logic: Check every 100 minutes starting from 07:30");
        
        // --- FITUR CATCH UP (KEJAR KETERTINGGALAN) ---
        $this->newLine();
        $this->warn("🚀 Running Initial Check (Catch Up)...");
        $this->info("   Checking for missed notifications while system was offline.");
        
        // Jalankan command send-missed untuk handle notifikasi yang terlewat
        $this->call('notifications:send-missed');
        
        $this->info("✅ Catch Up Complete. Entering loop mode...");
        $this->newLine();
        // -----------------------------------------------

        while (true) {
            $now = Carbon::now();
            
            // 1. Generate Check Points untuk hari ini
            // Mulai 07:30, interval 100 menit, sampai jam 18:00
            $checkPoints = [];
            $startTime = Carbon::createFromTime(7, 30, 0); // 07:30:00
            
            // Generate 8 slot (cukup untuk seharian)
            for ($i = 0; $i < 8; $i++) {
                $slotTime = $startTime->copy()->addMinutes(100 * $i);
                // Hanya ambil yang belum lewat hari ini
                if ($slotTime->isAfter($now)) {
                    $checkPoints[] = $slotTime;
                }
            }

            // 2. Jika tidak ada slot tersisa hari ini (sudah malam)
            // Tunggu sampai besok pagi jam 07:30
            if (empty($checkPoints)) {
                $tomorrow = Carbon::tomorrow()->setTime(7, 30, 0);
                $secondsToWait = $now->diffInSeconds($tomorrow);
                
                $this->warn("🌙 No more slots today. Sleeping until tomorrow morning (07:30)...");
                $this->info("💤 Sleeping for " . gmdate("H:i:s", $secondsToWait));
                
                sleep($secondsToWait);
                continue; // Loop lagi besok pagi
            }

            // 3. Ambil slot terdekat berikutnya
            $nextCheck = $checkPoints[0];
            $secondsToWait = $now->diffInSeconds($nextCheck);

            // Tampilkan Info
            $this->table(
                ['Next Check Time', 'Time Until Check', 'Action'],
                [[
                    $nextCheck->format('H:i:s'), 
                    gmdate("H:i:s", $secondsToWait),
                    'Run schedule:send-reminders'
                ]]
            );

            $this->info("⏳ Waiting for next slot...");
            
            // 4. Tidur sampai waktu check tiba
            sleep($secondsToWait);

            // 5. WAKTUNYA CEK!
            $this->newLine();
            $this->info("🔔 IT'S TIME! Checking for classes starting in 30 mins...");
            $this->call('schedule:send-reminders');
            
            $this->info("✅ Check done. Calculating next slot...");
            $this->newLine();
            
            // Tidur 2 detik biar ga double trigger
            sleep(2);
        }
    }
}
