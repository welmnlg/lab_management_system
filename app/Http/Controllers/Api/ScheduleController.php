<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Get user's schedules grouped by day
     * Route: GET /api/schedules/my-schedules
     */
    
    public function getMySchedules()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            
            $schedules = Schedule::where('user_id', $user->user_id ?? $user->id)
                ->with(['courseClass', 'courseClass.course', 'room', 'user'])
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

         // ✅ AUTO-CANCEL expired schedules
            $schedules = $this->autoCancelExpiredSchedules($schedules);

            // Group schedules by day
            $groupedSchedules = $this->groupSchedulesByDay($schedules);

            return response()->json([
                'success' => true,
                'data' => [
                    'semester' => 1,
                    'academic_year' => $this->getCurrentAcademicYear(),
                    'schedules' => $groupedSchedules,
                    'total_schedules' => $schedules->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading user schedules: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load schedules',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
 * Check dan auto-cancel jadwal yang confirmation window-nya sudah expired
 * Dipanggil sebelum return schedules
 */
    private function autoCancelExpiredSchedules($schedules)
    {
        foreach ($schedules as $schedule) {
            // Hanya untuk jadwal yang statusnya masih 'terjadwal'
            if ($schedule->status !== 'terjadwal') {
                continue;
            }
            
            $now = \Carbon\Carbon::now();
            $startTime = \Carbon\Carbon::parse($schedule->start_time);
            $fifteenMinAfter = $startTime->copy()->addMinutes(15);
            
            // Jika sekarang sudah lewat 15 menit setelah jadwal mulai
            if ($now->isAfter($fifteenMinAfter)) {
                // Auto-cancel
                $schedule->update([
                    'status' => 'dibatalkan',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Waktu konfirmasi sudah lewat'
                ]);
            }
        }
        
        return $schedules;
    }

    /**
     * Group schedules by day name
     */
    private function groupSchedulesByDay($schedules)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; // Testing Sabtu Minggu
        $grouped = [];

        // Initialize setiap hari
        foreach ($days as $day) {
            $grouped[$day] = [];
        }

        // Group schedules
       foreach ($schedules as $schedule) {
            $day = $schedule->day;

            if (in_array($day, $days)) {
                // ✅ Extract data dari nested relationships
                $courseName = 'N/A';
                $className = 'N/A';
                $roomName = 'N/A';
                
                // Get course name dari courseClass.course
                if ($schedule->courseClass && $schedule->courseClass->course) {
                    $courseName = $schedule->courseClass->course->course_name;
                }
                
                // Get class name dari courseClass
                if ($schedule->courseClass) {
                    $className = $schedule->courseClass->class_name;
                }
                
                // Get room name dari room
                if ($schedule->room) {
                    // Try both 'name' dan 'room_name' (tergantung column di database)
                    $roomName = $schedule->room->name ?? $schedule->room->room_name;
                }
                
                $grouped[$day][] = [
                    'schedule_id' => $schedule->schedule_id,
                    'course_name' => $courseName,
                    'class_name' => $className,
                    'room_name' => $roomName,
                    'room_id' => $schedule->room_id,
                    'day' => $schedule->day,
                    'time_slot' => $this->formatTimeSlot($schedule->start_time, $schedule->end_time),
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status,              // ← ADD
                    'confirmed_at' => $schedule->confirmed_at,  // ← ADD
                ];
            }
        }

        // Sort each day by start_time
        foreach ($grouped as $day => &$daySchedules) {
            usort($daySchedules, function ($a, $b) {
                return strtotime($a['start_time']) - strtotime($b['start_time']);
            });
        }

        return $grouped;

    }

    /**
     * Format time slot dari 08:00:00 - 09:40:00 menjadi "08.00 - 09.40"
     */
    private function formatTimeSlot($startTime, $endTime)
    {
        $start = date('H.i', strtotime($startTime));
        $end = date('H.i', strtotime($endTime));
        return "$start - $end";
    }

    /**
     * Get current academic year
     * Format: 2024/2025
     */
    private function getCurrentAcademicYear()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Semester starts July (bulan 7)
        if ($currentMonth >= 7) {
            return $currentYear . '/' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '/' . $currentYear;
        }
    }

    /**
     * Get schedule details by ID
     * Route: GET /api/schedules/{id}
     */
    public function getScheduleDetail($id)
    {
        try {
            $user = Auth::user();

            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->with(['course', 'class', 'room', 'user'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $schedule->schedule_id,
                    'course_name' => $schedule->courseClass->course->course_name,
                    'class_name' => $schedule->class->name,
                    'room_name' => $schedule->room->name,
                    'room_id' => $schedule->room_id,
                    'day' => $schedule->day,
                    'time_slot' => $this->formatTimeSlot($schedule->start_time, $schedule->end_time),
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status ?? 'terjadwal'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Cancel schedule (Batalkan Kelas)
     * Route: POST /api/schedules/{id}/cancel
     */
    public function cancelSchedule($id)
    {
        try {
            $user = Auth::user();

            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            if (in_array($schedule->status, ['sedang_berlangsung', 'selesai'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa membatalkan jadwal yang sudah berlangsung'
                ], 400);
            }

            // Update status
            $schedule->status = 'dibatalkan';
            $schedule->cancelled_at = now();
            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dibatalkan',
                'data' => [
                    'id' => $schedule->schedule_id,
                    'status' => $schedule->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Membatalkan Jadwal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm schedule (Konfirmasi Mengajar)
     * Route: POST /api/schedules/{id}/confirm
     */
    public function confirmSchedule($id)
    {
        try {
            $user = Auth::user();

            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            // ✅ Validasi: Hanya bisa confirm jika status terjadwal
            if (!$schedule->canConfirm()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah dikonfirmasi atau dibatalkan',
                    'current_status' => $schedule->status
                ], 400);
            }
            
            // ✅ Validasi: Hanya buka confirm window (1 jam sebelum - 15 min sesudah)
            if (!$schedule->isConfirmationWindowOpen()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfirmasi hanya dapat dilakukan 1 jam sebelum sampai 15 menit setelah jam mulai'
                ], 400);
            }

            // Update status
            $schedule->status = 'dikonfirmasi';
            $schedule->confirmed_at = now();
            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dikonfirmasi',
                'data' => [
                    'id' => $schedule->schedule_id,
                    'status' => $schedule->status,
                    'confirmed_at' => $schedule->confirmed_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Konfirmasi Jadwal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete schedule (Selesai Kelas)
     * Route: POST /api/schedules/{id}/complete
     */
    public function completeSchedule($id)
    {
        try {
            $user = Auth::user();

            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();
            
            // Validasi: Hanya bisa complete jika sedang berlangsung
            if ($schedule->status !== 'sedang_berlangsung') {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal harus dalam status sedang berlangsung'
                ], 400);
            }

            // Update status
            $schedule->status = 'selesai';
            $schedule->completed_at = now();
            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diselesaikan',
                'data' => [
                    'id' => $schedule->schedule_id,
                    'status' => $schedule->status,
                    'completed_at' => $schedule->completed_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move to different room (Pindah Ruangan)
     * Route: POST /api/schedules/{id}/move-room
     */
    public function moveToRoom(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            // Validasi: Hanya bisa pindah jika sedang berlangsung
            if ($schedule->status !== 'sedang_berlangsung') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa pindah ruangan saat kelas sedang berlangsung'
                ], 400);
            }

            // Update status ke pindah_ruangan
            $schedule->status = 'pindah_ruangan';
            $schedule->moved_at = now();
            $schedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah ke pindah ruangan. Silakan scan QR ruangan baru.',
                'data' => [
                    'id' => $schedule->schedule_id,
                    'status' => $schedule->status,
                    'moved_at' => $schedule->moved_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}