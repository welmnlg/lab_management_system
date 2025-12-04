<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ViewNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:view 
                            {--today : Show only today\'s notifications}
                            {--limit=10 : Number of notifications to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View notifications from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = $this->option('today');
        $limit = (int) $this->option('limit');
        
        $this->info('📬 Fetching notifications...');
        $this->newLine();
        
        // Build query
        $query = Notification::with(['user', 'schedule.class', 'schedule.room'])
            ->orderBy('created_at', 'desc');
        
        if ($today) {
            $query->whereDate('created_at', Carbon::today());
            $this->info("📅 Showing notifications from today");
        } else {
            $this->info("📅 Showing latest {$limit} notifications");
        }
        
        $notifications = $query->limit($limit)->get();
        
        if ($notifications->isEmpty()) {
            $this->warn('⚠️  No notifications found.');
            return Command::SUCCESS;
        }
        
        $this->newLine();
        
        // Prepare data for table
        $tableData = [];
        
        foreach ($notifications as $notif) {
            $schedule = $notif->schedule;
            $tableData[] = [
                $notif->notification_id,
                $notif->user->name ?? 'N/A',
                $schedule->class->class_name ?? 'N/A',
                $schedule->room->room_name ?? 'N/A',
                $schedule->day ?? 'N/A',
                Carbon::parse($schedule->start_time)->format('H:i') ?? 'N/A',
                $notif->class_status,
                $notif->created_at->format('Y-m-d H:i:s'),
            ];
        }
        
        $this->table(
            ['ID', 'User', 'Class', 'Room', 'Day', 'Start Time', 'Status', 'Sent At'],
            $tableData
        );
        
        $this->newLine();
        
        // Statistics
        $totalToday = Notification::whereDate('created_at', Carbon::today())->count();
        $totalThisWeek = Notification::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        $this->info("📊 Statistics:");
        $this->info("   Today: {$totalToday} notification(s)");
        $this->info("   This week: {$totalThisWeek} notification(s)");
        $this->info("   Total in database: " . Notification::count() . " notification(s)");
        
        return Command::SUCCESS;
    }
}
