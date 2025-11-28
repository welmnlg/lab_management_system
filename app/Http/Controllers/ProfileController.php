<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Schedule;
// use App\Models\SemesterPeriod;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function edit()
    {
        return view('profile');
    }

    /**
     * Update profil user (nama, email, nim)
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
                'nim' => 'sometimes|string|max:9|unique:users,nim,' . $user->user_id . ',user_id'
            ]);

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diupdate',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update password user dengan validasi lengkap
     */
    public function updatePassword(Request $request)
    {
        try {
            Log::info('Password update attempt started', [
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            $user = auth()->user();

            // Validasi dengan aturan lengkap
            $validator = Validator::make($request->all(), [
                'current_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('Kata sandi lama yang Anda masukkan salah.');
                        }
                    },
                ],
                'new_password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                    'different:current_password',
                    'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$@%]).+$/'
                ],
            ], [
                'current_password.required' => 'Kata sandi lama harus diisi',
                'new_password.required' => 'Kata sandi baru harus diisi',
                'new_password.min' => 'Kata sandi baru minimal 6 karakter',
                'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
                'new_password.different' => 'Kata sandi baru tidak boleh sama dengan kata sandi lama',
                'new_password.regex' => 'Kata sandi baru harus mengandung huruf, angka, dan karakter khusus (!$@%)',
            ]);

            if ($validator->fails()) {
                Log::warning('Password update validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update password dengan hash
            $user->password = Hash::make($request->new_password);
            $user->save();

            Log::info('Password updated successfully', [
                'user_id' => $user->user_id,
                'user_name' => $user->name,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil diubah'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error updating password', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah kata sandi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profil lengkap dengan jadwal
     */
    public function getProfile()
    {
        try {
            $user = Auth::user();
            // $activePeriod = SemesterPeriod::getActivePeriod();

            $data = [
                'user' => [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'nim' => $user->nim,
                    'email' => $user->email,
                    'program_studi' => $user->program_studi
                ],
                'schedules' => [],
                'period' => null
            ];

            // if ($activePeriod) {
                // Get user schedules grouped by day
                $schedules = Schedule::with(['course', 'class', 'room'])
                    ->where('period_id', $activePeriod->period_id)
                    ->where('user_id', $user->user_id)
                    ->where('status', 'active')
                    ->orderBy('day')
                    ->orderBy('start_time')
                    ->get()
                    ->groupBy('day');

                // Format schedules
                $formattedSchedules = [];
                foreach ($schedules as $day => $daySchedules) {
                    $formattedSchedules[$day] = $daySchedules->map(function($schedule) {
                        return [
                            'schedule_id' => $schedule->schedule_id,
                            'course_name' => $schedule->course->course_name ?? 'Unknown Course',
                            'course_code' => $schedule->course->course_code ?? '',
                            'class_name' => $schedule->class->class_name ?? '',
                            'room_name' => $schedule->room->room_name ?? 'Unknown Room',
                            'location' => $schedule->room->location ?? 'Unknown Location',
                            'time_slot' => $schedule->time_slot,
                            'start_time' => $schedule->start_time,
                            'end_time' => $schedule->end_time,
                            'day' => $schedule->day
                        ];
                    })->values();
                }

                $data['schedules'] = $formattedSchedules;
                $data['period'] = [
                    'semester' => $activePeriod->semester_type,
                    'academic_year' => $activePeriod->academic_year,
                    'formatted_period' => $activePeriod->formatted_period
                ];
            // }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data profil'
            ], 500);
        }
    }

    /**
     * Update profil (alias untuk update)
     */
    public function updateProfile(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Hapus akun user
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|current_password'
            ]);

            $user = $request->user();
            
            Log::info('User account deleted', [
                'user_id' => $user->user_id,
                'user_name' => $user->name,
                'timestamp' => now()
            ]);
            
            auth()->logout();
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting account: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
}