<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunSchedulerLoop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:run-loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Laravel scheduler in a loop (Smart Sync Mode)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("🔄 Scheduler Loop Started...");
        $this->info("⚡ Mode: Smart Sync (Mimics Production Server)");
        $this->info("� The scheduler will run exactly at the start of every minute (:00)");
        $this->newLine();

        while (true) {
            // 1. Hitung detik saat ini
            $currentSecond = (int) date('s');
            
            // 2. Jika kita tidak di detik 00, hitung sisa waktu ke menit berikutnya
            // Contoh: Sekarang 10:05:15 -> Tunggu 45 detik biar jadi 10:06:00
            if ($currentSecond > 0) {
                $secondsToWait = 60 - $currentSecond;
                $this->line("⏳ Waiting {$secondsToWait} seconds to align with next minute...");
                sleep($secondsToWait);
            }

            // 3. SEKARANG SUDAH DETIK :00 -> JALANKAN SCHEDULER!
            $timestamp = date('H:i:s');
            $this->info("[$timestamp] 🚀 Running Scheduler...");
            
            $this->call('schedule:run');
            
            $this->line("✅ Done. Waiting for next cycle...");
            $this->newLine();

            // 4. Tidur sebentar (2 detik) biar ga double run di detik 00 yang sama
            sleep(2);
        }
    }
}
