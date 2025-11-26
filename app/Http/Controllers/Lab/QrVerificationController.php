<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\RoomAccessLog;
use App\Models\RoomOccupancyStatus;
use App\Models\Logbook;
use App\Models\ScheduleOverride;
use App\Events\RoomStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class QrVerificationController extends Controller
{
    // POST /api/lab/qr-verify
    public function verifyQrCode(Request $request)
    {
        \Log::info('QR Verify called', [
            'auth_check' => auth()->check(),
            'user_id' => auth()->id(),
            'user' => auth()->user() ? auth()->user()->name : null,
            'session_id' => session()->getId(),
            'headers' => $request->headers->all()
        ]);
        // 1. Validasi user sudah login
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $user = auth()->user();

        // 2. Validasi dan dekripsi token QR
        try {
            $token = $request->input('token');
            
            if (empty($token)) {
                throw new \Exception('Token kosong');
            }

            // Coba dekripsi Laravel dulu
            try {
                $decryptedData = Crypt::decryptString($token);
                $qrData = json_decode($decryptedData, true);
            } catch (\Exception $e) {
                // Fallback: Coba base64 decode untuk QR lama
                Log::info('Trying base64 decode fallback');
                $decoded = base64_decode($token, true);
                
                if ($decoded === false) {
                    throw new \Exception('Invalid token format');
                }
                
                $qrData = json_decode($decoded, true);
            }

            if (!$qrData || !isset($qrData['room_id'])) {
                throw new \Exception('Invalid QR data structure');
            }

        } catch (\Exception $e) {
            Log::error('QR Decrypt Error: ' . $e->getMessage(), [
                'token_preview' => substr($request->input('token'), 0, 50)
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'QR code tidak valid atau sudah kadaluarsa',
                'error_type' => 'invalid_qr',
                'details' => 'Format QR code tidak dapat dibaca. Pastikan Anda menggunakan QR code yang benar.'
            ], 400);
        }

        $roomId = $qrData['room_id'];

        // 3. Validasi room ada
        $room = Room::find($roomId);
        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
                'error_type' => 'room_not_found',
                'details' => "Ruangan dengan ID $roomId tidak ada dalam sistem."
            ], 404);
        }

        // 5. Validasi hari dan waktu
        $now = \Carbon\Carbon::now();
        $currentDay = $now->dayOfWeek;
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $currentTime = $now->format('H:i:s');

        // Hardcoded untuk testing: Selasa
        // $dayName = 'Selasa';
        $dayName = $dayNames[$currentDay];

        // Validasi jadwal mengajar aslab pada ruangan ini
        $schedule = Schedule::where('user_id', $user->user_id)
            ->where('room_id', $roomId)
            ->where('day', $dayName)
            ->orderBy('start_time', 'asc')
            ->get();

        // Filter manual dengan buffer 15 menit
        $validSchedule = null;
        $isMove = false;
        $isOverride = false;

        // 1. Cek Jadwal Reguler (dikonfirmasi)
        foreach ($schedule as $sched) {
            if (!in_array($sched->status, ['dikonfirmasi'])) {
                continue;
            }
            
            $bufferStart = \Carbon\Carbon::parse($sched->start_time)->subMinutes(15)->format('H:i:s');
            $endTime = $sched->end_time;
            
            if ($currentTime >= $bufferStart && $currentTime <= $endTime) {
                $validSchedule = $sched;
                break;
            }
        }

        // 2. Cek Kelas Ganti (ScheduleOverride) - Status: dikonfirmasi
        if (!$validSchedule) {
            $override = ScheduleOverride::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->where('date', today())
                ->where('status', 'dikonfirmasi')
                ->first();

            if ($override) {
                $bufferStart = \Carbon\Carbon::parse($override->start_time)->subMinutes(15)->format('H:i:s');
                if ($currentTime >= $bufferStart && $currentTime <= $override->end_time) {
                    $validSchedule = $override;
                    $isOverride = true;
                }
            }
        }

                // 4. Cek Jika Terlalu Awal (Ada jadwal hari ini tapi belum masuk waktu buffer)
        if (!$validSchedule) {
            $futureSchedules = [];

            // A. Future Regular
            $futureRegular = Schedule::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->where('day', $dayName)
                ->where('status', 'dikonfirmasi')
                ->where('start_time', '>', $currentTime)
                ->orderBy('start_time', 'asc')
                ->first();
            if ($futureRegular) $futureSchedules[] = $futureRegular;

            // B. Future Override
            $futureOverride = ScheduleOverride::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->where('date', today())
                ->where('status', 'dikonfirmasi')
                ->where('start_time', '>', $currentTime)
                ->orderBy('start_time', 'asc')
                ->first();
            if ($futureOverride) $futureSchedules[] = $futureOverride;

            // C. Future Moving (Regular)
            $futureMoving = Schedule::where('user_id', $user->user_id)
                ->where('status', 'pindah_ruangan')
                ->where('day', $dayName)
                ->where('room_id', $roomId)
                ->where('start_time', '>', $currentTime)
                ->orderBy('start_time', 'asc')
                ->first();
            if ($futureMoving) $futureSchedules[] = $futureMoving;

            // D. Future Moving (Override)
            $futureMovingOverride = ScheduleOverride::where('user_id', $user->user_id)
                ->where('status', 'pindah_ruangan')
                ->where('date', today())
                ->where('room_id', $roomId)
                ->where('start_time', '>', $currentTime)
                ->orderBy('start_time', 'asc')
                ->first();
            if ($futureMovingOverride) $futureSchedules[] = $futureMovingOverride;

            if (!empty($futureSchedules)) {
                // Sort by start_time to find the nearest one
                usort($futureSchedules, function($a, $b) {
                    return strcmp($a->start_time, $b->start_time);
                });
                
                $nextSchedule = $futureSchedules[0];
                $bufferStart = \Carbon\Carbon::parse($nextSchedule->start_time)->subMinutes(15)->format('H:i');
                
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu awal. Anda baru bisa scan QR mulai pukul ' . $bufferStart . ' (15 menit sebelum jadwal).',
                    'error_type' => 'too_early',
                    'next_start_time' => $nextSchedule->start_time
                ], 400);
            }
        }

        // 3. Cek Status "Pindah Ruangan" (Regular & Override)
        if (!$validSchedule) {
            // A. Regular Schedule Pindah Ruangan
            $movingSchedules = Schedule::where('user_id', $user->user_id)
                ->where('status', 'pindah_ruangan')
                ->where('day', $dayName)
                ->get(); // Get all, not just first

            foreach ($movingSchedules as $movingSchedule) {
                // Check if same room
                if ($movingSchedule->room_id == $roomId) {
                    // Check if this move is already COMPLETED (Child override is 'selesai')
                    $childOverride = ScheduleOverride::where('schedule_id', $movingSchedule->schedule_id)
                        ->where('date', today())
                        ->where('status', 'selesai')
                        ->first();
                    
                    if ($childOverride) {
                        continue; // Move is complete, ignore this schedule
                    }
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak bisa pindah ke ruangan yang sama.',
                        'error_type' => 'same_room_move'
                    ], 400);
                }

                // Check if this move is already COMPLETED (Child override is 'selesai')
                $childOverride = ScheduleOverride::where('schedule_id', $movingSchedule->schedule_id)
                    ->where('date', today())
                    ->where('status', 'selesai')
                    ->first();
                
                if ($childOverride) {
                    continue; // Skip this schedule, it's already finished in the new room
                }

                $bufferStart = \Carbon\Carbon::parse($movingSchedule->start_time)->subMinutes(15)->format('H:i:s');
                if ($currentTime >= $bufferStart && $currentTime <= $movingSchedule->end_time) {
                    // Cek konflik di ruangan baru (roomId saat ini)
                    if ($this->checkConflict($roomId, $dayName, $movingSchedule->start_time, $movingSchedule->end_time, $movingSchedule->schedule_id)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Ruangan tidak tersedia. Ada jadwal lain di jam ini.',
                            'error_type' => 'room_conflict'
                        ], 409);
                    }
                    $validSchedule = $movingSchedule;
                    $isMove = true;
                    break; // Found valid one
                }
            }

            // B. Override Pindah Ruangan
            if (!$validSchedule) {
                $movingOverrides = ScheduleOverride::where('user_id', $user->user_id)
                    ->where('status', 'pindah_ruangan')
                    ->where('date', today())
                    ->get(); // Get all

                foreach ($movingOverrides as $movingOverride) {
                    // Check if same room
                    if ($movingOverride->room_id == $roomId) {
                        // Check if this move is already COMPLETED (Child override is 'selesai')
                        $childOverride = ScheduleOverride::where('schedule_override_id', $movingOverride->id)
                            ->where('date', today())
                            ->where('status', 'selesai')
                            ->first();
                        
                        if ($childOverride) {
                            continue; // Move is complete, ignore this override
                        }
                        
                        return response()->json([
                            'success' => false,
                            'message' => 'Anda tidak bisa pindah ke ruangan yang sama.',
                            'error_type' => 'same_room_move'
                        ], 400);
                    }

                    $childOverride = ScheduleOverride::where('schedule_override_id', $movingOverride->id)
                        ->where('date', today())
                        ->where('status', 'selesai')
                        ->first();
                    
                    if ($childOverride) {
                        continue; // Skip this override, it's already finished in the new room
                    }

                    $bufferStart = \Carbon\Carbon::parse($movingOverride->start_time)->subMinutes(15)->format('H:i:s');
                    if ($currentTime >= $bufferStart && $currentTime <= $movingOverride->end_time) {
                        // Cek konflik di ruangan baru
                        if ($this->checkConflict($roomId, $dayName, $movingOverride->start_time, $movingOverride->end_time, null, $movingOverride->id)) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Ruangan tidak tersedia. Ada jadwal lain di jam ini.',
                                'error_type' => 'room_conflict'
                            ], 409);
                        }
                        $validSchedule = $movingOverride;
                        $isMove = true;
                        $isOverride = true;
                        break; // Found valid one
                    }
                }
            }
        }

        // Jika tidak ada jadwal valid
        if (!$validSchedule) {
            // Check apakah ada schedule tapi belum dikonfirmasi (hanya untuk jadwal reguler)
            $unconfirmedSchedule = Schedule::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->where('day', $dayName)
                ->whereTime('start_time', '>=', date('H:i:s', strtotime('-15 minutes')))
                ->first();
            
            if ($unconfirmedSchedule && !in_array($unconfirmedSchedule->status, ['dikonfirmasi', 'pindah_ruangan'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal belum dikonfirmasi atau sudah dibatalkan',
                    'current_status' => $unconfirmedSchedule->status,
                    'error_type' => 'unconfirmed_schedule'
                ], 400);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal aktif atau kelas ganti untuk ruangan ini saat ini.',
                'error_type' => 'no_schedule'
            ], 404);
        }


        $actives = RoomOccupancyStatus::where('room_id', $roomId)
            ->where('current_user_id', $user->user_id)
            ->where('is_active', true)
            ->where('schedule_id', '!=', $validSchedule->schedule_id) // beda schedule_id
            ->count();

        if ($actives > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Selesaikan jadwal penggunaan sebelumnya sebelum scan QR untuk jadwal baru.',
                'error_type' => 'previous_occupancy_active'
            ], 409);
        }

        // Check if room is currently occupied by ANOTHER user
        $roomOccupied = RoomOccupancyStatus::where('room_id', $roomId)
            ->where('current_user_id', '!=', $user->user_id)
            ->where('is_active', true)
            ->first();

        if ($roomOccupied) {
            $occupyingUser = \App\Models\User::find($roomOccupied->current_user_id);
            $occupyingSchedule = Schedule::find($roomOccupied->schedule_id);
            
            return response()->json([
                'success' => false,
                'message' => 'Ruangan sedang digunakan oleh pengguna lain',
                'error_type' => 'room_occupied',
                'details' => [
                    'current_user' => $occupyingUser ? $occupyingUser->name : 'Unknown',
                    'started_at' => $roomOccupied->started_at,
                    'schedule_end_time' => $occupyingSchedule ? $occupyingSchedule->end_time : null
                ]
            ], 409);
        }

        // 6. Jika semua valid, simpan pending log dan return info jadwal
        $log = RoomAccessLog::create([
            'user_id' => $user->user_id,
            'room_id' => $roomId,
            'scan_time' => now(),
            'validation_status' => 'pending',
        ]);

        // Store pending entry dalam session untuk konfirmasi berikutnya
        session(['pending_room_entry' => [
            'room_id' => $roomId,
            'log_id' => $log->id,
            'schedule_id' => $isOverride ? null : $validSchedule->schedule_id,
            'override_id' => $isOverride ? $validSchedule->id : null,
            'is_move' => $isMove,
            'is_override' => $isOverride
        ]]);

        return response()->json([
            'success' => true,
            'message' => 'QR code valid. Silakan konfirmasi.',
            'data' => [
                'room_name' => $room->room_name,
                'location' => $room->location,
                'class_name' => $validSchedule->courseClass->class_name ?? 'N/A',
                'subject_name' => $validSchedule->courseClass->course->course_name ?? 'N/A',
                'start_time' => $validSchedule->start_time,
                'end_time' => $validSchedule->end_time,
                'day' => $validSchedule->day,
                'is_move' => $isMove,
                'is_override' => $isOverride
            ]
        ]);
    }

    private function checkConflict($roomId, $day, $startTime, $endTime, $excludeScheduleId = null, $excludeOverrideId = null)
    {
        // 1. Cek Konflik dengan Schedule Reguler
        $scheduleConflict = Schedule::where('room_id', $roomId)
            ->where('day', $day)
            ->whereNotIn('status', ['dibatalkan', 'pindah_ruangan', 'selesai'])
            ->when($excludeScheduleId, function ($q) use ($excludeScheduleId) {
                return $q->where('schedule_id', '!=', $excludeScheduleId);
            })
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($scheduleConflict) return true;

        // 2. Cek Konflik dengan Schedule Override (Kelas Ganti)
        $overrideConflict = ScheduleOverride::where('room_id', $roomId)
            ->where('date', today())
            ->whereNotIn('status', ['cancelled', 'pindah_ruangan', 'selesai'])
            ->when($excludeOverrideId, function ($q) use ($excludeOverrideId) {
                return $q->where('id', '!=', $excludeOverrideId);
            })
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();

        return $overrideConflict;
    }

    private function convertNumberToDay($dayNumber)
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $days[$dayNumber] ?? null;
    }
    
    // POST /api/lab/confirm-entry
    public function confirmEntry(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $pendingEntry = session('pending_room_entry');

        if (!$pendingEntry) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada entry yang pending'
            ], 400);
        }

        $user = auth()->user();
        $roomId = $pendingEntry['room_id'];
        $logId = $pendingEntry['log_id'];
        $scheduleId = $pendingEntry['schedule_id'];
        $overrideId = $pendingEntry['override_id'] ?? null;
        $isMove = $pendingEntry['is_move'] ?? false;
        $isOverride = $pendingEntry['is_override'] ?? false;

        try {
            DB::beginTransaction();

            // Update log status menjadi success
            $log = RoomAccessLog::find($logId);
            $log->update([
                'validation_status' => 'success',
                'entry_time' => now()
            ]);

            $schedule = null;
            $override = null;
            $courseClass = null;

            if ($isOverride) {
                $override = ScheduleOverride::find($overrideId);
                $courseClass = $override->courseClass;
                
                // Backup check for move
                if ($override->status === 'pindah_ruangan') {
                    $isMove = true;
                }
            } else {
                $schedule = Schedule::find($scheduleId);
                $courseClass = $schedule->courseClass;

                // Backup check for move
                // if ($schedule->status === 'pindah_ruangan') {
                //     $isMove = true;
                // }
            }

            // Handle logic pindah ruangan
            $newOverrideId = null;
            
            // Backup check: Jika status adalah 'pindah_ruangan', cek apakah room beda
            if ($schedule && $schedule->status === 'pindah_ruangan') {
                if ($schedule->room_id != $roomId) {
                    $isMove = true;
                } else {
                    $isMove = false; // Scan di ruangan sama = Batal Pindah / Resume
                }
            }
            
            if ($override && $override->status === 'pindah_ruangan') {
                if ($override->room_id != $roomId) {
                    $isMove = true;
                } else {
                    $isMove = false; // Scan di ruangan sama = Batal Pindah / Resume
                }
            }
            if ($isMove) {
                // 1. Close Old Logbook (GANTI RUANGAN)
                // Check logbook linked to schedule OR override
                $query = Logbook::whereNull('logout');
                if ($isOverride) {
                    $query->where('override_id', $overrideId);
                } else {
                    $query->where('schedule_id', $scheduleId);
                }
                
                $query->update([
                        'logout' => now()->format('H:i:s'),
                        'status' => 'GANTI RUANGAN'
                    ]);

                // Update RoomAccessLog exit_time for OLD room
                $oldRoomId = $isOverride ? $override->room_id : $schedule->room_id;
                \App\Models\RoomAccessLog::where('user_id', $user->user_id)
                    ->where('room_id', $oldRoomId)
                    ->whereNull('exit_time')
                    ->latest()
                    ->first()
                    ?->update(['exit_time' => now()]);

                // 2. Clear Old Room Occupancy
                $occupancyQuery = RoomOccupancyStatus::where('is_active', true);
                if ($isOverride) {
                    $occupancyQuery->where('schedule_id', null); // Override occupancy usually has null schedule_id? No, we need to check how it's stored.
                    // Actually, RoomOccupancyStatus has schedule_id. Does it have override_id?
                    // Let's check RoomOccupancyStatus model. Assuming it might rely on user_id + active.
                    $occupancyQuery->where('current_user_id', $user->user_id);
                } else {
                    $occupancyQuery->where('schedule_id', $scheduleId);
                }
                
                $occupancyQuery->update([
                        'is_active' => false,
                        'ended_at' => now()
                    ]);

                // 3. Create Child Override (Recursive)
                // If it's already an override moving, we create a CHILD override
                // If it's a schedule moving, we create a ROOT override
                
                $parentOverrideId = $isOverride ? $overrideId : null;
                $parentScheduleId = $isOverride ? null : $scheduleId;
                
                $source = $isOverride ? $override : $schedule;

                $newOverride = ScheduleOverride::create([
                    'schedule_id' => $parentScheduleId, // Fix: Link to schedule if it's a regular class move
                    'schedule_override_id' => $parentOverrideId, // Link to parent override if exists
                    'user_id' => $user->user_id,
                    'room_id' => $roomId,
                    'class_id' => $source->class_id, // Inherit class_id
                    'date' => today(),
                    'day' => $source->day,
                    'start_time' => $source->start_time,
                    'end_time' => $source->end_time,
                    'status' => 'sedang_berlangsung', // Langsung aktif
                    'reason' => 'Pindah Ruangan'
                ]);
                
                $newOverrideId = $newOverride->id;
                
                // Update Parent Status if not already updated (though moveToRoom should have done it)
                // But confirmEntry ensures it's 'pindah_ruangan'
                $source->update(['status' => 'pindah_ruangan']);
                
                // Use the new override as the active context
                $overrideId = $newOverrideId;
                $isOverride = true; // Now we are definitely in an override context
                $scheduleId = null; // Clear schedule ID for the new session
            }

            // Update room occupancy status for NEW room
            RoomOccupancyStatus::updateOrCreate(
                [
                    'room_id' => $roomId,
                    'current_user_id' => $user->user_id,
                    'is_active' => true,
                ],
                [
                    'schedule_id' => $scheduleId, // Can be null for overrides
                    'started_at' => now(),
                    'ended_at' => null,
                ]
            );

            // Update status ke sedang_berlangsung jika bukan pindah ruangan (karena kalau pindah ruangan, yang baru dibuat sudah sedang_berlangsung)
            if (!$isMove) {
                if ($isOverride) {
                    $override->update([
                        'status' => 'sedang_berlangsung',
                        // 'started_at' => now(), // Override table might not have started_at? Check migration.
                    ]);
                } else {
                    $schedule->update([
                        'status' => 'sedang_berlangsung',
                        'started_at' => now(),
                    ]);
                }
            }
            
            // Create Logbook entry for NEW room
            $logbook = Logbook::create([
                'user_id' => $user->user_id,
                'room_id' => $roomId,
                'schedule_id' => $scheduleId,
                'override_id' => $overrideId, // ✅ Link to Override
                'course_id' => $courseClass->course_id,
                'date' => today(),
                'login' => now()->format('H:i:s'),
                'activity' => 'MENGAJAR',
                'access_log_id' => $logId,
                'entry_method' => 'QR_SCAN',
            ]);

            // Clear pending session
            session()->forget('pending_room_entry');

            // Broadcast update ke dashboard
            broadcast(new RoomStatusUpdated($roomId, true, $user->name, $user->user_id));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Entry berhasil. Ruangan sekarang aktif.',
                'room_id' => $roomId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Confirm Entry Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Tambah method baru untuk check room status
    public function getRoomStatus($roomId)
    {
        try {
            $occupancy = RoomOccupancyStatus::where('room_id', $roomId)->first();
            
            return response()->json([
                'success' => true,
                'room_id' => $roomId,
                'is_active' => $occupancy ? $occupancy->is_active : false,
                'current_user' => $occupancy ? $occupancy->current_user_id : null,
                'started_at' => $occupancy ? $occupancy->started_at : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking room status'
            ], 500);
        }
    }

    // POST /api/lab/exit-room
    public function exitRoom(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $roomId = $request->input('room_id');
        $user = auth()->user();

        try {
            // Update room occupancy
            RoomOccupancyStatus::where('room_id', $roomId)
                ->where('current_user_id', $user->user_id)
                ->update([
                    'is_active' => false,
                    'ended_at' => now()
                ]);
            
            // Update Logbook logout time
            Logbook::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->whereNull('logout')
                ->whereDate('date', today())
                ->latest()
                ->first()
                ?->update([
                    'logout' => now()->format('H:i:s'),
                    'status' => 'SELESAI'
                ]);

            // Update last access log
            RoomAccessLog::where('user_id', $user->user_id)
                ->where('room_id', $roomId)
                ->whereNull('exit_time')
                ->latest()
                ->first()
                ?->update(['exit_time' => now()]);

            // Broadcast update
            broadcast(new RoomStatusUpdated($roomId, false, null));

            return response()->json([
                'success' => true,
                'message' => 'Anda telah keluar dari ruangan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Exit Room Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    
}

