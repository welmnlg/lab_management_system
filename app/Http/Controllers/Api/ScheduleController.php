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
            
            // Get active semester period
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'semester' => 'Ganjil',
                        'academic_year' => $this->getCurrentAcademicYear(),
                        'schedules' => [],
                        'total_schedules' => 0,
                        'message' => 'Belum ada periode semester aktif'
                    ]
                ]);
            }
            
            // Query schedules ONLY from active semester period
            $schedules = Schedule::where('user_id', $user->user_id ?? $user->id)
                ->where('period_id', $activePeriod->period_id) // ✅ Filter by active period
                ->with(['class', 'class.course', 'room', 'user'])
                ->orderBy('day')
                ->orderBy('start_time')
                ->get();

            // Auto-cancel jadwal yang sudah lewat waktu konfirmasi
            $schedules = $this->autoCancelExpiredSchedules($schedules);

            // Group schedules by day
            $groupedSchedules = $this->groupSchedulesByDay($schedules);

            $semesterType = $activePeriod->semester_type;
            $academicYear = $activePeriod->academic_year;

            return response()->json([
                'success' => true,
                'data' => [
                    'semester' => $semesterType,  // Changed from 1 to semester_type (Ganjil/Genap)
                    'academic_year' => $academicYear,  // From active period
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
            
            // Map day names to Carbon day indices
            $dayNames = ['Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6];
            
            if (!isset($dayNames[$schedule->day])) {
                continue;
            }
            
            $scheduleDayIndex = $dayNames[$schedule->day];
            $currentDayIndex = $now->dayOfWeek;
            
            // Calculate days difference
            $daysUntilSchedule = $scheduleDayIndex - $currentDayIndex;
            
            // Create the schedule datetime for this week
            $scheduleDateTime = $now->copy()->addDays($daysUntilSchedule);
            $scheduleDateTime->setTimeFromTimeString($schedule->start_time);
            
            // Calculate 15 minutes after schedule start
            $fifteenMinAfter = $scheduleDateTime->copy()->addMinutes(15);
            
            // Only auto-cancel if NOW is AFTER the 15-minute window
            // This means the schedule is truly in the past
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
                // Extract data dari relasi nested
                $courseName = 'N/A';
                $className = 'N/A';
                $roomName = 'N/A';
                $roomId = $schedule->room_id;
                
                // Get course name dari class.course
                if ($schedule->class && $schedule->class->course) {
                    $courseName = $schedule->class->course->course_name;
                }
                
                // Get class name
                if ($schedule->class) {
                    $className = $schedule->class->class_name;
                }
                
                // Get room name dari room
                if ($schedule->room) {
                    // Try both 'name' dan 'room_name' (tergantung column di database)
                    $roomName = $schedule->room->name ?? $schedule->room->room_name;
                }

                // 🔧 PERBAIKAN: Check for child override SEBELUM menambahkan parent schedule
                $hasActiveChildOverride = false;
                $override = null;
                
                if ($schedule->status == 'pindah_ruangan') {
                    $override = \App\Models\ScheduleOverride::where('schedule_id', $schedule->schedule_id)
                        ->whereBetween('date', [
                            now()->startOfWeek(),          // Senin
                            now()->startOfWeek()->addDays(4) // Jumat
                        ])
                        ->whereIn('status', ['sedang_berlangsung', 'selesai'])
                        ->with('room')
                        ->first();
                    
                    // flag: ada child override yang sudah aktif/selesai
                    if ($override) {
                        $hasActiveChildOverride = true;
                    }
                }

                // 1. Add Original Schedule with proper flags
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
                    'status' => $schedule->status,
                    'confirmed_at' => $schedule->confirmed_at,
                    'is_override' => false,
                    'has_active_child_override' => $hasActiveChildOverride, // ✅ Flag baru
                    'schedule_override_id' => null
                ];

                // 2. Add Child Override as separate entry (if exists)
                if ($hasActiveChildOverride && $override && $override->room) {
                    $grouped[$day][] = [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $courseName,
                        'class_name' => $className,
                        'room_name' => $override->room->room_name,
                        'room_id' => $override->room_id,
                        'day' => $schedule->day,
                        'time_slot' => $this->formatTimeSlot($override->start_time, $override->end_time),
                        'start_time' => $override->start_time,
                        'end_time' => $override->end_time,
                        'status' => $override->status, // sedang_berlangsung atau selesai
                        'confirmed_at' => $schedule->confirmed_at,
                        'is_override' => true,
                        'override_id' => $override->id,
                        'has_active_child_override' => false,
                        'schedule_override_id' => $override->schedule_override_id
                    ];
                }
            }


            }

        // 3. Ambil Kelas Ganti (schedule_id IS NULL)
        $substituteClasses = \App\Models\ScheduleOverride::where('user_id', auth()->id())
            ->whereNull('schedule_id') // Hanya yang murni kelas ganti (Top Level)
            ->whereNull('schedule_override_id') // Bukan child override
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->startOfWeek()->addDays(4)
            ])
            // Fetch all relevant statuses
            ->whereIn('status', ['active', 'dikonfirmasi', 'sedang_berlangsung', 'selesai', 'pindah_ruangan', 'cancelled'])
            ->with(['room', 'class.course', 'childOverride.room']) // Eager load child override
            ->get();

        foreach ($substituteClasses as $sub) {
            $day = $sub->day;
            
            if (in_array($day, $days)) {
                
                // DATA UTAMA (Parent)
                $courseName = $sub->class && $sub->class->course ? $sub->class->course->course_name : 'N/A';
                $className = $sub->class ? $sub->class->class_name : 'N/A';
                
                // 1. Add Parent Substitute Class
                $grouped[$day][] = [
                    'schedule_id' => null,
                    'course_name' => $courseName,
                    'class_name' => $className,
                    'room_name' => $sub->room ? $sub->room->room_name : 'N/A',
                    'room_id' => $sub->room_id,
                    'day' => $sub->day,
                    'time_slot' => $this->formatTimeSlot($sub->start_time, $sub->end_time),
                    'start_time' => $sub->start_time,
                    'end_time' => $sub->end_time,
                    'status' => $sub->status, // active, dikonfirmasi, sedang_berlangsung, selesai, pindah_ruangan, cancelled
                    'confirmed_at' => null,
                    'is_override' => true,
                    'is_substitute' => true,
                    'override_id' => $sub->id,
                    'child_override' => $sub->childOverride ? true : false,
                    'schedule_override_id' => $sub->schedule_override_id
                ];

                // 2. Check for Child Override (Pindah Ruangan)
                if ($sub->status == 'pindah_ruangan' && $sub->childOverride) {
                    $child = $sub->childOverride;
                    $grouped[$day][] = [
                        'schedule_id' => null,
                        'course_name' => $courseName,
                        'class_name' => $className,
                        'room_name' => $child->room ? $child->room->room_name : 'N/A',
                        'room_id' => $child->room_id,
                        'day' => $child->day,
                        'time_slot' => $this->formatTimeSlot($child->start_time, $child->end_time),
                        'start_time' => $child->start_time,
                        'end_time' => $child->end_time,
                        'status' => $child->status === 'active' ? 'sedang_berlangsung' : $child->status, // Child active = waiting for QR (sedang berlangsung contextually or menungggu scan) -> let's keep it 'active' or map to 'dikonfirmasi' logic? 
                        // Actually for moved room, 'active' usually means 'Menunggu Scan QR' in frontend logic if we reuse 'dikonfirmasi' logic or 'active' logic.
                        // Let's pass raw status and handle in frontend.
                        'confirmed_at' => null,
                        'is_override' => true,
                        'is_substitute' => true,
                        'is_substitute' => true,
                        'override_id' => $child->id,
                        'child_override' => false,
                        'schedule_override_id' => $child->schedule_override_id
                    ];
                }
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
            $schedule = Schedule::where('schedule_id', $id)
                ->with(['class.course', 'room', 'user'])
                ->firstOrFail();

            // Format time slot
            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H.i');
            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H.i');
            $timeSlot = "{$startTime} - {$endTime}";

            return response()->json([
                'success' => true,
                'data' => [
                    'schedule_id' => $schedule->schedule_id,
                    'course_name' => $schedule->class && $schedule->class->course 
                        ? $schedule->class->course->course_name 
                        : 'Unknown Course',
                    'course_code' => $schedule->class && $schedule->class->course 
                        ? $schedule->class->course->course_code 
                        : '',
                    'class_name' => $schedule->class->class_name ?? '',
                    'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                    'room_id' => $schedule->room_id,
                    'building_name' => $schedule->room->location ?? 'Gedung C', // Auto-fill from room location
                    'day_of_week' => $schedule->day,
                    'time_slot' => $timeSlot,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status ?? 'terjadwal',
                    'user_id' => $schedule->user_id
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting schedule detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found',
                'error' => config('app.debug') ? $e->getMessage() : null
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

            // Validasi: Hanya bisa konfirmasi jika status terjadwal
            if (!$schedule->canConfirm()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah dikonfirmasi atau dibatalkan',
                    'current_status' => $schedule->status
                ], 400);
            }
            
            // Validasi: Hanya buka jendela konfirmasi (1 jam sebelum - 15 min sesudah)
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
     * Complete override (Selesai Kelas Ganti)
     * Route: POST /api/schedules/override/{id}/complete
     */
    public function completeOverride($id)
    {
        try {
            $user = Auth::user();

            $override = \App\Models\ScheduleOverride::where('id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            $override->update([
                'status' => 'selesai',
                'end_time' => now()->format('H:i:s')
            ]);

            // Close Logbook for override
            \App\Models\Logbook::where('override_id', $override->id)
                ->whereNull('logout')
                ->update(['logout' => now()->format('H:i:s'), 'status' => 'SELESAI']);

            // Close Occupancy for override room
            \App\Models\RoomOccupancyStatus::where('room_id', $override->room_id)
                ->where('is_active', true)
                ->update(['is_active' => false, 'ended_at' => now()]);

            \App\Models\RoomAccessLog::where('user_id', $user->user_id ?? $user->id)
                ->where('room_id', $override->room_id)
                ->whereNull('exit_time')
                ->latest()
                ->first()
                ?->update(['exit_time' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil diselesaikan',
                'data' => [
                    'id' => $id,
                    'status' => 'selesai',
                    'end_time' => now()->format('H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyelesaikan kelas ganti',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm override (Konfirmasi Kelas Ganti)
     * Route: POST /api/schedules/override/{id}/confirm
     */
    public function confirmOverride($id)
    {
        try {
            $user = Auth::user();

            $override = \App\Models\ScheduleOverride::where('id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            if ($override->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak dapat dikonfirmasi'
                ], 400);
            }

            $override->update([
                'status' => 'dikonfirmasi'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil dikonfirmasi',
                'data' => $override
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi kelas ganti',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel override (Batalkan Kelas Ganti)
     * Route: POST /api/schedules/override/{id}/cancel
     */
    public function cancelOverride($id)
    {
        try {
            $user = Auth::user();

            $override = \App\Models\ScheduleOverride::where('id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();

            $override->update([
                'status' => 'cancelled' // Menggunakan 'cancelled' sesuai enum
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas ganti berhasil dibatalkan',
                'data' => $override
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan kelas ganti',
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

            // Handle Regular Schedule
            $schedule = Schedule::where('schedule_id', $id)
                ->where('user_id', $user->user_id ?? $user->id)
                ->firstOrFail();
            
            // Handle penyelesaian override (Pindah Ruangan)
            if ($schedule->status == 'pindah_ruangan') {
                $override = \App\Models\ScheduleOverride::where('schedule_id', $id)
                    ->where('status', 'active')
                    ->first();
                    
                if ($override) {
                    $override->update([
                        'status' => 'selesai',
                        'end_time' => now()->format('H:i:s')
                    ]);
                    
                    // Close Logbook for override
                    \App\Models\Logbook::where('override_id', $override->id)
                        ->whereNull('logout')
                        ->update(['logout' => now()->format('H:i:s'), 'status' => 'SELESAI']);
                        
                    // Close Occupancy for override room
                    \App\Models\RoomOccupancyStatus::where('room_id', $override->room_id)
                        ->where('is_active', true)
                        ->update(['is_active' => false, 'ended_at' => now()]);

                    // Update RoomAccessLog exit_time
                    \App\Models\RoomAccessLog::where('user_id', $user->user_id ?? $user->id)
                        ->where('room_id', $override->room_id)
                        ->whereNull('exit_time')
                        ->latest()
                        ->first()
                        ?->update(['exit_time' => now()]);
                        
                    return response()->json([
                        'success' => true,
                        'message' => 'Kelas (Pindah Ruangan) berhasil diselesaikan',
                        'data' => [
                            'id' => $schedule->schedule_id,
                            'status' => 'selesai'
                        ]
                    ]);
                } else {
                    // Tidak ada override aktif - sudah selesai atau tidak valid
                    return response()->json([
                        'success' => true,
                        'message' => 'Kelas pengganti sudah diselesaikan',
                        'data' => [
                            'id' => $schedule->schedule_id,
                            'status' => 'selesai'
                        ]
                    ]);
                }
            }

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

            \App\Models\RoomOccupancyStatus::where('room_id', $schedule->room_id)
                ->where('is_active', true)
                ->update(['is_active' => false, 'ended_at' => now()]);

            // Update RoomAccessLog exit_time
            \App\Models\RoomAccessLog::where('user_id', $user->user_id ?? $user->id)
                ->where('room_id', $schedule->room_id)
                ->whereNull('exit_time')
                ->latest()
                ->first()
                ?->update(['exit_time' => now()]);

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
            $request->validate([
                'reason' => 'nullable|string'
            ]);

            $user = Auth::user();
            $reason = $request->reason;

            // CHECK IF THIS IS AN OVERRIDE (SUBSTITUTE CLASS)
            if (str_starts_with($id, 'override-')) {
                $overrideId = str_replace('override-', '', $id);
                $parentOverride = \App\Models\ScheduleOverride::where('id', $overrideId)
                    ->where('user_id', $user->user_id ?? $user->id)
                    ->firstOrFail();

                // Update Parent Status to pindah_ruangan
                // The actual new room assignment (child override) will be handled by QR Scan
                $parentOverride->update([
                    'status' => 'pindah_ruangan',
                    'reason' => $reason
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status berubah menjadi Pindah Ruangan. Silakan scan QR di ruangan baru.',
                    'data' => $parentOverride
                ]);
            }

            // REGULAR SCHEDULE LOGIC
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

    // ==========================================
    // ADDITIONAL METHODS FROM ScheduleController
    // ==========================================

    /**
     * Get schedules for a specific room
     */
    public function getSchedulesByRoom($roomId)
    {
        try {
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $schedules = Schedule::with(['user', 'class.course', 'room'])
                ->where('period_id', $activePeriod->period_id)
                ->where('room_id', $roomId)
                ->where('status', 'terjadwal')
                ->get()
                ->map(function($schedule) {
                    return [
                        'schedule_id' => $schedule->schedule_id,
                        'course_name' => $schedule->class && $schedule->class->course 
                            ? $schedule->class->course->course_name 
                            : 'Unknown Course',
                        'course_code' => $schedule->class && $schedule->class->course 
                            ? $schedule->class->course->course_code 
                            : '',
                        'class_name' => $schedule->class->class_name ?? '',
                        'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                        'day_of_week' => $schedule->day,
                        'time_slot' => $this->formatTimeSlot($schedule->start_time, $schedule->end_time),
                        'building_name' => $schedule->room->location ?? 'Unknown Location',
                        'user_id' => $schedule->user_id,
                        'can_edit' => $schedule->user_id === auth()->id()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $schedules->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting schedules by room: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal'
            ], 500);
        }
    }

    /**
     * Get specific schedule (show)
     */
    public function show($scheduleId)
    {
        try {
            $schedule = Schedule::with(['user', 'class.course', 'room'])
                ->where('schedule_id', $scheduleId)
                ->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'schedule_id' => $schedule->schedule_id,
                    'course_id' => $schedule->class_id,
                    'course_code' => $schedule->class && $schedule->class->course 
                        ? $schedule->class->course->course_code 
                        : '',
                    'course_name' => $schedule->class && $schedule->class->course 
                        ? $schedule->class->course->course_name 
                        : 'Unknown Course',
                    'class_id' => $schedule->class_id,
                    'class_name' => $schedule->class->class_name ?? '',
                    'room_id' => $schedule->room_id,
                    'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                    'building_name' => $schedule->room->location ?? 'Unknown Location',
                    'day_of_week' => $schedule->day,
                    'time_slot' => $this->formatTimeSlot($schedule->start_time, $schedule->end_time),
                    'user_id' => $schedule->user_id,
                    'can_edit' => $schedule->user_id === auth()->id()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jadwal'
            ], 500);
        }
    }

    /**
     * Get user's courses for active semester
     */
    public function getUserCoursesActive()
    {
        try {
            $userId = auth()->id();
            
            // Get all user courses with their classes
            $courses = \DB::table('user_courses')
                ->join('course_classes', 'user_courses.class_id', '=', 'course_classes.class_id')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->where('user_courses.user_id', $userId)
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'course_classes.class_id',
                    'course_classes.class_name'
                )
                ->get();
            
            // Group by course_id
            $grouped = $courses->groupBy('course_id')
                ->map(function($classes) {
                    $first = $classes->first();
                    return [
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'classes' => $classes->map(function($class) {
                            return [
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name
                            ];
                        })->values()
                    ];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => $grouped
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting user courses: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata kuliah',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get ALL courses available for active semester
     * Specifically for form-ambil-jadwal - shows courses based on current semester (Ganjil/Genap)
     * Route: GET /api/schedules/active-courses
     */
    public function getActiveSemesterCourses()
    {
        try {
            // Get authenticated user
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get active period to determine current semester
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode semester aktif'
                ], 404);
            }
            
            // Get current semester type (Ganjil/Genap)
            $currentSemester = $activePeriod->semester_type;
            
            // Get user's assigned class IDs from user_courses
            $userClassIds = \DB::table('user_courses')
                ->where('user_id', $user->user_id ?? $user->id)
                ->pluck('class_id');
            
            if ($userClassIds->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'meta' => [
                        'current_semester' => $currentSemester,
                        'academic_year' => $activePeriod->academic_year,
                        'period' => $activePeriod->formatted_period,
                        'message' => 'User belum memiliki mata kuliah yang di-assign'
                    ]
                ]);
            }
            
            // Get courses that match BOTH:
            // 1. Current semester (Ganjil/Genap)
            // 2. User's assigned courses (via course_classes -> user_courses)
            $courses = \DB::table('courses')
                ->join('course_classes', 'courses.course_id', '=', 'course_classes.course_id')
                ->whereIn('course_classes.class_id', $userClassIds) // Filter by user's classes
                ->where('courses.semester', $currentSemester) // Filter by active semester
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'courses.semester',
                    'course_classes.class_id',
                    'course_classes.class_name'
                )
                ->orderBy('courses.course_code')
                ->orderBy('course_classes.class_name')
                ->get();
            
            // Group by course_id to get courses with their classes
            $grouped = $courses->groupBy('course_id')
                ->map(function($classes) {
                    $first = $classes->first();
                    return [
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'semester' => $first->semester,
                        'classes' => $classes->map(function($class) {
                            return [
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name
                            ];
                        })->values()
                    ];
                })
                ->values();
            
            \Log::info('📚 getActiveSemesterCourses (User-filtered)', [
                'user_id' => $user->user_id ?? $user->id,
                'current_semester' => $currentSemester,
                'total_user_classes' => $userClassIds->count(),
                'total_courses_available' => $grouped->count(),
                'total_classes_available' => $courses->count(),
                'period' => $activePeriod->formatted_period
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $grouped,
                'meta' => [
                    'current_semester' => $currentSemester,
                    'academic_year' => $activePeriod->academic_year,
                    'period' => $activePeriod->formatted_period,
                    'total_courses' => $grouped->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting active semester courses: ' . $e->getMessage());
            \Log::error('Stack: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata kuliah semester aktif',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    /**
     * Get buildings with rooms
     */
    public function getBuildingsWithRooms()
    {
        try {
            $rooms = \DB::table('rooms')
                ->select('room_id', 'room_name', 'location')
                ->orderBy('room_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'rooms' => $rooms
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting rooms: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data ruangan'
            ], 500);
        }
    }

    /**
     * Store new schedule
     */
    public function store(\Illuminate\Http\Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'class_id' => 'required|exists:course_classes,class_id',
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'time_slot' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }


            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            // 🔍 DEBUG: Log active period details
            \Log::info('📅 Schedule Taking Check', [
                'period_id' => $activePeriod->period_id,
                'semester' => $activePeriod->semester_type,
                'academic_year' => $activePeriod->academic_year,
                'is_active' => $activePeriod->is_active,
                'is_schedule_open' => $activePeriod->is_schedule_open,
                'is_schedule_taking_open' => $activePeriod->is_schedule_taking_open,
                'user_id' => auth()->id()
            ]);

            // ✅ FIXED: Check if schedule taking is allowed (using computed attribute)
            if (!$activePeriod->is_schedule_taking_open) {
                \Log::warning('❌ Schedule taking BLOCKED', [
                    'is_schedule_open' => $activePeriod->is_schedule_open,
                    'is_schedule_taking_open' => $activePeriod->is_schedule_taking_open,
                    'period_id' => $activePeriod->period_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Pengambilan jadwal sedang ditutup. Anda tidak dapat mengambil jadwal saat ini.',
                    'error_type' => 'schedule_taking_closed',
                    'debug' => [
                        'period_id' => $activePeriod->period_id,
                        'is_schedule_taking_open' => $activePeriod->is_schedule_taking_open,
                        'semester' => $activePeriod->semester_type . ' ' . $activePeriod->academic_year
                    ]
                ], 403);
            }


            // Parse time slot
            $timeSlot = $request->time_slot;
            $timeParts = explode(' - ', $timeSlot);
            if (count($timeParts) !== 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format waktu tidak valid'
                ], 422);
            }
            $startTime = str_replace('.', ':', trim($timeParts[0])) . ':00';
            $endTime = str_replace('.', ':', trim($timeParts[1])) . ':00';

            // NEW: Check duplicate class booking (same user + same class + same period)
            $duplicateClass = Schedule::where('user_id', auth()->id())
                ->where('class_id', $request->class_id)
                ->where('period_id', $activePeriod->period_id)
                ->exists();

            if ($duplicateClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengambil kelas ini! Tidak bisa booking kelas yang sama dua kali.'
                ], 422);
            }

            // Check room+time conflicts
            $conflict = Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day_of_week)
                ->where('start_time', $startTime)
                ->where('period_id', $activePeriod->period_id)
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bentrok! Ruangan sudah dibooking pada waktu tersebut.'
                ], 422);
            }

            $schedule = Schedule::create([
                'period_id' => $activePeriod->period_id,
                'user_id' => auth()->id(),
                'class_id' => $request->class_id,
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'terjadwal'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dibooking!',
                'data' => ['schedule_id' => $schedule->schedule_id]
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error storing schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membooking jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule
     */
    public function update(\Illuminate\Http\Request $request, $scheduleId)
    {
        try {
            $schedule = Schedule::find($scheduleId);
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            if ($schedule->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit jadwal ini'
                ], 403);
            }

            // ✅ FIXED: Check if schedule taking is allowed (using computed attribute)
            $activePeriod = \App\Models\SemesterPeriod::find($schedule->period_id);
            if ($activePeriod && !$activePeriod->is_schedule_taking_open) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengambilan jadwal sedang ditutup. Anda tidak dapat mengedit jadwal saat ini.',
                    'error_type' => 'schedule_taking_closed'
                ], 403);
            }

            $validator = \Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,room_id',
                'day_of_week' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'time_slot' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Parse time slot
            $timeParts = explode(' - ', $request->time_slot);
            $startTime = str_replace('.', ':', trim($timeParts[0])) . ':00';
            $endTime = str_replace('.', ':', trim($timeParts[1])) . ':00';

            $schedule->update([
                'room_id' => $request->room_id,
                'day' => $request->day_of_week,
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jadwal'
            ], 500);
        }
    }

    /**
     * Delete schedule
     */
    public function destroy($scheduleId)
    {
        try {
            $schedule = Schedule::find($scheduleId);
            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal tidak ditemukan'
                ], 404);
            }

            if ($schedule->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus jadwal ini'
                ], 403);
            }

            // ✅ FIXED: Check if schedule taking is allowed (using computed attribute)
            $activePeriod = \App\Models\SemesterPeriod::find($schedule->period_id);
            if ($activePeriod && !$activePeriod->is_schedule_taking_open) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengambilan jadwal sedang ditutup. Anda tidak dapat menghapus jadwal saat ini.',
                    'error_type' => 'schedule_taking_closed'
                ], 403);
            }

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal'
            ], 500);
        }
    }

    /**
     * Get user schedules
     */
    public function getUserSchedules()
    {
        try {
            $userId = auth()->id();
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            $schedules = Schedule::with(['class.course', 'room'])
                ->where('period_id', $activePeriod->period_id)
                ->where('user_id', $userId)
                ->get()
                ->groupBy('day');

            return response()->json([
                'success' => true,
                'data' => [
                    'schedules' => $schedules
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jadwal'
            ], 500);
        }
    }

    /**
     * Get available time slots
     */
    public function getAvailableTimeSlots(\Illuminate\Http\Request $request)
    {
        try {
            // Get active period
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            $query = Schedule::where('room_id', $request->room_id)
                ->where('day', $request->day_of_week);
            
            // Filter by active period if exists
            if ($activePeriod) {
                $query->where('period_id', $activePeriod->period_id);
            }
            
            $bookedSlots = $query->pluck('start_time')->toArray();

            $allTimeSlots = [
                '08.00 - 09:40',
                '09:40 - 11:20',
                '11:20 - 13:00',
                '13:00 - 14:40',
                '14:40 - 16:20'
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'available_slots' => array_diff($allTimeSlots, $bookedSlots)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil slot waktu'
            ], 500);
        }
    }

    /**
     * Get user courses
     */
    public function getUserCourses()
    {
        return $this->getUserCoursesActive();
    }

    /**
     * Get user's booked class IDs for a specific course
     * Used to filter dropdown and prevent duplicate bookings
     */
    public function getUserBookedClasses($courseId)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get active period
            $activePeriod = \App\Models\SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => true,
                    'data' => ['booked_class_ids' => []]
                ]);
            }

            // Get class IDs that user has already booked for this course in active semester
            $bookedClassIds = Schedule::where('user_id', $user->user_id ?? $user->id)
                ->where('period_id', $activePeriod->period_id)
                ->whereHas('class', function($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->pluck('class_id')
                ->toArray();

            \Log::info('getUserBookedClasses', [
                'user_id' => $user->user_id ?? $user->id,
                'course_id' => $courseId,
                'period_id' => $activePeriod->period_id,
                'booked_class_ids' => $bookedClassIds
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'booked_class_ids' => $bookedClassIds
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting booked classes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
}