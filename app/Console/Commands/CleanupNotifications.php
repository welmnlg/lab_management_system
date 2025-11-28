<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup {--days=30 : Number of days to keep notifications}';
    protected $description = 'Delete old notifications to keep database clean';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("🧹 Cleaning up notifications older than {$days} days...");
        $this->info("Cutoff date: {$cutoffDate->format('Y-m-d H:i:s')}");

        try {
            $deletedCount = Notification::where('created_at', '<', $cutoffDate)->delete();

            $this->info("✅ Deleted {$deletedCount} old notifications");
            Log::info('Notifications cleanup completed', [
                'deleted_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->format('Y-m-d H:i:s')
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Error during cleanup: {$e->getMessage()}");
            Log::error('Notifications cleanup failed', [
                'error' => $e->getMessage()
            ]);
            return Command::FAILURE;
        }
    }
}