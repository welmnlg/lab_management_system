<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\RoomAccessLog;
use App\Models\RoomOccupancyStatus;
use App\Models\Logbook;  
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

            // ✅ Try Laravel Encryption first
            try {
                $decryptedData = Crypt::decryptString($token);
                $qrData = json_decode($decryptedData, true);
            } catch (\Exception $e) {
                // ✅ Fallback: Try base64 decode (untuk QR yang lama)
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
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']; //testing minggu
        // $dayName = $dayNames[$currentDay]; //testing minggu
        $currentTime = $now->format('H:i:s');

        // Convert day name to number if needed
        $dayMap = [
            'Minggu' => 0,
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6
            
        ];

        // Convert day number ke nama hari (FIX MINGGU BUG)
        $dayName = $this->convertNumberToDay($currentDay);

        $schedule = Schedule::where('user_id', $user->user_id)
            ->where('room_id', $roomId)
            ->where('day', $dayName)  // ← FIX: use day name
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        // ✅ NEW: CHECK STATUS BEFORE ACCEPTING SCAN
        if (!in_array($schedule->status, ['dikonfirmasi', 'pindah_ruangan'])) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal belum dikonfirmasi atau sudah dibatalkan',
                'current_status' => $schedule->status
            ], 400);
        }

        // ✅ Check time window (existing logic tetap)
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($schedule->start_time);
        $endTime = \Carbon\Carbon::parse($schedule->end_time);
        $fifteenMinBefore = $startTime->copy()->subMinutes(15);

        if (!$now->isBetween($fifteenMinBefore, $endTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu scan tidak valid'
            ], 400);
        }

        // 4. Validasi jadwal mengajar aslab pada ruangan ini
        // $schedule = Schedule::where('user_id', $user->user_id)
        //     ->where('room_id', $roomId)
        //     ->where('day', $currentDay) // Hari harus sama
        //     ->whereTime('start_time', '<=', $currentTime)
        //     ->whereTime('end_time', '>=', $currentTime)
        //     ->first();
        // 4. Validasi jadwal mengajar aslab pada ruangan ini - WITH STATUS CHECK
        $schedule = Schedule::where('user_id', $user->user_id)
            ->where('room_id', $roomId)
            ->where('day', $dayName)
            ->orderBy('start_time', 'asc')
            ->get();

        // ✅ FILTER MANUAL DENGAN BUFFER 15 MENIT + STATUS CHECK
        $validSchedule = null;
        foreach ($schedule as $sched) {
            // ✅ CHECK 1: Status harus dikonfirmasi atau pindah_ruangan
            if (!in_array($sched->status, ['dikonfirmasi', 'pindah_ruangan'])) {
                continue; // Skip jadwal yang belum confirm atau sudah dibatalkan
            }
            
            $bufferStart = \Carbon\Carbon::parse($sched->start_time)->subMinutes(15)->format('H:i:s');
            $endTime = $sched->end_time;
            
            // ✅ CHECK 2: Cek: bufferStart <= currentTime <= endTime
            if ($currentTime >= $bufferStart && $currentTime <= $endTime) {
                $validSchedule = $sched;
                break;
            }
        }

        // ✅ NEW: If no schedule found, check why
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
            'schedule_id' => $validSchedule->schedule_id
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
                'day' => $validSchedule->day
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

        try {
            // Update log status menjadi success
            $log = RoomAccessLog::find($logId);
            $log->update([
                'validation_status' => 'success',
                'entry_time' => now()
            ]);

            // Update room occupancy status
            RoomOccupancyStatus::updateOrCreate(
                [
                    'room_id' => $roomId,
                    'schedule_id' => $pendingEntry['schedule_id'], // PASTIKAN INI!
                    'current_user_id' => $user->user_id,
                    'is_active' => true,
                ],
                [
                    'started_at' => now(),
                    'ended_at' => null,
                ]
            );

            // Create Logbook entry
            // ✅ UPDATE Schedule status ke sedang_berlangsung
            $schedule = Schedule::find($pendingEntry['schedule_id']);
            $schedule->update([
                'status' => 'sedang_berlangsung',
                'started_at' => now(),
                'room_id' => $roomId
            ]);
            
            // ✅ INVALIDATE CACHE untuk profil user (agar load data terbaru)
            // Cache::tags(['user_schedules'])->flush();
            // Cache::forget("user_schedules_{$user->user_id}");


            // Create Logbook entry
            $logbook = Logbook::create([
                'user_id' => $user->user_id,
                'room_id' => $roomId,
                'schedule_id' => $pendingEntry['schedule_id'],
                'course_id' => $schedule->courseClass->course_id,
                'date' => today(),
                'login' => now()->format('H:i:s'),
                'activity' => 'MENGAJAR',
                'access_log_id' => $logId,
                'entry_method' => 'QR_SCAN',
            ]);

            // Clear pending session
            session()->forget('pending_room_entry');

            // ✅ FLUSH DASHBOARD CACHE (untuk room usage schedule)
            // Cache::tags(['dashboard_usage'])->flush();

            // Broadcast update ke dashboard
            broadcast(new RoomStatusUpdated($roomId, true, $user->name, $user->user_id));

            return response()->json([
                'success' => true,
                'message' => 'Entry berhasil. Ruangan sekarang aktif.',
                'room_id' => $roomId
            ]);
        } catch (\Exception $e) {
            \Log::error('Confirm Entry Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
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

