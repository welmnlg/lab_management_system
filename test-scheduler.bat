@echo off
echo ========================================
echo Testing Scheduler & Notifications
echo ========================================
echo.

echo [1/4] Checking current time...
php -r "echo 'Current Time: ' . date('Y-m-d H:i:s') . PHP_EOL;"
echo.

echo [2/4] Getting schedules for today...
php artisan tinker --execute="$now = \Carbon\Carbon::now('Asia/Jakarta'); $day = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat'][$now->format('l')]; \App\Models\Schedule::where('day', $day)->where('status', 'active')->get(['schedule_id', 'time_slot', 'start_time'])->each(function($s) use ($now) { $diff = $now->diffInMinutes($s->start_time, false); echo \"Schedule ID: {$s->schedule_id} | Time: {$s->time_slot} | Minutes until: {$diff}\n\"; });"
echo.

echo [3/4] Running notification checker manually...
php artisan schedule:send-reminders
echo.

echo [4/4] Checking if scheduler is configured correctly...
php artisan schedule:list
echo.

echo ========================================
echo Testing completed!
echo ========================================
pause