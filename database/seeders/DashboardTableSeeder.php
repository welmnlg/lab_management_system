<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\CourseClass;
use App\Models\Logbook;

class DashboardTableSeeder extends Seeder
{
    /**
     * Seed data untuk Dashboard dengan mengambil data dari tabel lain
     * Dashboard biasanya menampilkan agregasi data, bukan tabel tersendiri
     * Seeder ini akan membuat data tambahan yang spesifik untuk demo dashboard
     */
    public function run()
    {
        // Validasi: Pastikan data dari seeder lain sudah ada
        if (User::count() === 0 || Room::count() === 0 || Schedule::count() === 0) {
            $this->command->error('❌ Data belum ada! Jalankan seeder lain dulu (Users, Rooms, Schedules)');
            $this->command->info('💡 Jalankan: php artisan db:seed --class=UsersTableSeeder');
            $this->command->info('💡 Jalankan: php artisan db:seed --class=RoomsTableSeeder');
            $this->command->info('💡 Jalankan: php artisan db:seed --class=SchedulesTableSeeder');
            return;
        }

        $this->command->info('🎯 Seeding Dashboard Data...');

        // ============================================
        // 1. AMBIL DATA USERS YANG AKTIF (ASLAB)
        // ============================================
        $activeAslabs = User::whereHas('roles', function($query) {
            $query->where('status', 'aslab');
        })->get();

        if ($activeAslabs->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada aslab ditemukan!');
        } else {
            $this->command->info("✅ Found {$activeAslabs->count()} Aslab users");
        }

        // ============================================
        // 2. AMBIL SEMUA RUANGAN
        // ============================================
        $rooms = Room::all();
        $this->command->info("✅ Found {$rooms->count()} Rooms");

        // ============================================
        // 3. AMBIL JADWAL HARI INI (untuk simulasi)
        // ============================================
        $today = now()->format('l'); // Senin, Selasa, dll
        $todaySchedules = Schedule::where('day', $today)
            ->with(['courseClass', 'user', 'room'])
            ->orderBy('start_time')
            ->get();

        $this->command->info("✅ Found {$todaySchedules->count()} Schedules for today ($today)");

        // ============================================
        // 4. AMBIL JADWAL UPCOMING (7 hari ke depan)
        // ============================================
        $upcomingSchedules = Schedule::whereIn('day', $this->getNext7Days())
            ->with(['courseClass', 'user', 'room'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->limit(20)
            ->get();

        $this->command->info("✅ Found {$upcomingSchedules->count()} Upcoming Schedules");

        // ============================================
        // 5. AMBIL STATISTIK LOGBOOK
        // ============================================
        $totalLogbookEntries = Logbook::count();
        $todayLogbookEntries = Logbook::whereDate('date', now())->count();
        
        $this->command->info("✅ Total Logbook Entries: {$totalLogbookEntries}");
        $this->command->info("✅ Today's Logbook Entries: {$todayLogbookEntries}");

        // ============================================
        // 6. AMBIL STATUS RUANGAN (Available/Occupied)
        // ============================================
        $currentTime = now()->format('H:i:s');
        $occupiedRoomIds = Schedule::where('day', $today)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->pluck('room_id')
            ->unique();

        $occupiedCount = $occupiedRoomIds->count();
        $availableCount = $rooms->count() - $occupiedCount;

        $this->command->info("✅ Occupied Rooms: {$occupiedCount}");
        $this->command->info("✅ Available Rooms: {$availableCount}");

        // ============================================
        // 7. DISPLAY DASHBOARD SUMMARY
        // ============================================
        $this->command->newLine();
        $this->command->info('📊 DASHBOARD SUMMARY:');
        $this->command->table(
            ['Metric', 'Value'],
            [
                ['Total Aslab', $activeAslabs->count()],
                ['Total Rooms', $rooms->count()],
                ['Available Rooms', $availableCount],
                ['Occupied Rooms', $occupiedCount],
                ['Today\'s Schedules', $todaySchedules->count()],
                ['Upcoming Schedules', $upcomingSchedules->count()],
                ['Total Logbook Entries', $totalLogbookEntries],
                ['Today\'s Logbook', $todayLogbookEntries],
            ]
        );

        // ============================================
        // 8. DISPLAY TODAY'S SCHEDULE PREVIEW
        // ============================================
        if ($todaySchedules->isNotEmpty()) {
            $this->command->newLine();
            $this->command->info("📅 TODAY'S SCHEDULE ($today):");
            
            $scheduleData = $todaySchedules->take(5)->map(function($schedule) {
                return [
                    $schedule->room->room_name ?? 'N/A',
                    $schedule->courseClass->class_name ?? 'N/A',
                    $schedule->user->name ?? 'N/A',
                    $schedule->start_time,
                    $schedule->end_time,
                ];
            });

            $this->command->table(
                ['Room', 'Class', 'Aslab', 'Start', 'End'],
                $scheduleData
            );
        }

        // ============================================
        // 9. DISPLAY ROOM STATUS
        // ============================================
        $this->command->newLine();
        $this->command->info('🏢 ROOM STATUS:');
        
        $roomStatusData = $rooms->map(function($room) use ($occupiedRoomIds, $today, $currentTime) {
            $isOccupied = $occupiedRoomIds->contains($room->room_id);
            
            $currentSchedule = null;
            if ($isOccupied) {
                $currentSchedule = Schedule::where('room_id', $room->room_id)
                    ->where('day', $today)
                    ->where('start_time', '<=', $currentTime)
                    ->where('end_time', '>=', $currentTime)
                    ->with('courseClass')
                    ->first();
            }

            return [
                $room->room_name,
                $room->location,
                $isOccupied ? '🔴 Occupied' : '🟢 Available',
                $currentSchedule ? $currentSchedule->courseClass->class_name : '-',
            ];
        });

        $this->command->table(
            ['Room', 'Location', 'Status', 'Current Class'],
            $roomStatusData
        );

        // ============================================
        // SELESAI
        // ============================================
        $this->command->newLine();
        $this->command->info('✅ Dashboard Data Seeding Completed!');
        $this->command->info('💡 Data diambil dari: Users, Rooms, Schedules, Logbook');
    }

    /**
     * Get next 7 days name (Senin, Selasa, ...)
     */
    private function getNext7Days()
    {
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = now()->addDays($i)->format('l');
        }
        return $days;
    }
}