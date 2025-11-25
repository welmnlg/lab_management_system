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
        $dayName = 'Selasa';

        // Validasi jadwal mengajar aslab pada ruangan ini
        $schedule = Schedule::where('user_id', $user->user_id)
            ->where('room_id', $roomId)
            ->where('day', $dayName)
            ->orderBy('start_time', 'asc')
            ->get();

        // Filter manual dengan buffer 15 menit
        $validSchedule = null;
        $isMove = false;

        foreach ($schedule as $sched) {
            // Status harus dikonfirmasi atau pindah_ruangan
            if (!in_array($sched->status, ['dikonfirmasi', 'pindah_ruangan'])) {
                continue; // Lewati jadwal yang belum dikonfirmasi atau sudah dibatalkan
            }
            
            $bufferStart = \Carbon\Carbon::parse($sched->start_time)->subMinutes(15)->format('H:i:s');
            $endTime = $sched->end_time;
            
            // Cek apakah jadwal hari ini sesuai
            if ($currentTime >= $bufferStart && $currentTime <= $endTime) {
                $validSchedule = $sched;
                break;
            }
        }

        // Jika tidak ada jadwal standar, cek status "Pindah Ruangan"
        if (!$validSchedule) {
            $movingSchedule = Schedule::where('user_id', $user->user_id)
                ->where('status', 'pindah_ruangan')
                ->where('day', $dayName)
                ->first();

            if ($movingSchedule) {
                // Validasi waktu untuk jadwal pindah ruangan
                $bufferStart = \Carbon\Carbon::parse($movingSchedule->start_time)->subMinutes(15)->format('H:i:s');
                if ($currentTime >= $bufferStart && $currentTime <= $movingSchedule->end_time) {
                    
                    // Cek konflik di ruangan baru
                    $conflictingSchedule = Schedule::where('room_id', $roomId)
                        ->where('day', $dayName)
                        ->where('status', '!=', 'dibatalkan')
                        ->where('schedule_id', '!=', $movingSchedule->schedule_id)
                        ->where(function($q) use ($movingSchedule) {
                            $q->whereBetween('start_time', [$movingSchedule->start_time, $movingSchedule->end_time])
                              ->orWhereBetween('end_time', [$movingSchedule->start_time, $movingSchedule->end_time])
                              ->orWhere(function($sub) use ($movingSchedule) {
                                  $sub->where('start_time', '<=', $movingSchedule->start_time)
                                      ->where('end_time', '>=', $movingSchedule->end_time);
                              });
                        })
                        ->first();

                    if ($conflictingSchedule) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Ruangan tidak tersedia. Ada jadwal lain di jam ini.',
                            'error_type' => 'room_conflict'
                        ], 409);
                    }

                    $validSchedule = $movingSchedule;
                    $isMove = true;
                }
            }
        }

        // Jika tidak ada jadwal valid, periksa penyebabnya
        if (!$validSchedule) {
            // Check apakah ada schedule tapi belum dikonfirmasi
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
                'message' => 'Tidak ada jadwal aktif untuk ruangan ini saat ini.',
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
            'schedule_id' => $validSchedule->schedule_id,
            'is_move' => $isMove
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
                'is_move' => $isMove
            ]
        ]);
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
        $isMove = $pendingEntry['is_move'] ?? false;

        try {
            DB::beginTransaction();

            // Update log status menjadi success
            $log = RoomAccessLog::find($logId);
            $log->update([
                'validation_status' => 'success',
                'entry_time' => now()
            ]);

            $schedule = Schedule::find($scheduleId);

            // Backup check: Jika status adalah 'pindah_ruangan', force isMove = true
            if ($schedule->status === 'pindah_ruangan') {
                $isMove = true;
            }

            // Handle logic pindah ruangan
            $overrideId = null;
            if ($isMove) {
                // 1. Close Old Logbook (GANTI RUANGAN)
                Logbook::where('schedule_id', $scheduleId)
                    ->whereNull('logout')
                    ->update([
                        'logout' => now()->format('H:i:s'),
                        'status' => 'GANTI RUANGAN'
                    ]);

                // 2. Clear Old Room Occupancy
                RoomOccupancyStatus::where('schedule_id', $scheduleId)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'ended_at' => now()
                    ]);

                // 3. Create Schedule Override
                $override = ScheduleOverride::create([
                    'schedule_id' => $scheduleId,
                    'user_id' => $user->user_id,
                    'room_id' => $roomId,
                    'date' => today(),
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    // 'class_id' removed as per user change
                ]);
                $overrideId = $override->id;
            }

            // Update room occupancy status for NEW room
            RoomOccupancyStatus::updateOrCreate(
                [
                    'room_id' => $roomId,
                    'schedule_id' => $scheduleId,
                    'current_user_id' => $user->user_id,
                    'is_active' => true,
                ],
                [
                    'started_at' => now(),
                    'ended_at' => null,
                ]
            );

            // Update status schedule ke sedang_berlangsung jika bukan pindah ruangan
            if (!$isMove) {
                $schedule->update([
                    'status' => 'sedang_berlangsung',
                    'started_at' => now(),
                ]);
            }
            
            // Create Logbook entry for NEW room
            $logbook = Logbook::create([
                'user_id' => $user->user_id,
                'room_id' => $roomId,
                'schedule_id' => $scheduleId,
                'override_id' => $overrideId, // ✅ Link to Override
                'course_id' => $schedule->courseClass->course_id,
                'date' => today(),
                'login' => now()->format('H:i:s'),
                'activity' => 'MENGAJAR',
                'access_log_id' => $logId,
                'entry_method' => 'QR_SCAN',
                // 'status' => 'AKTIF' // ❌ REMOVED: Status column only accepts 'GANTI RUANGAN' or 'SELESAI' or NULL
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

