<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Step 1: Kirim OTP ke email
     * POST /forgot-password/send-otp
     */
    public function sendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;

            // Check apakah email ada di database users
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak ditemukan di sistem kami.',
                    'field_error' => true,
                ], 404);
            }

            // Generate atau update password reset record dengan OTP
            $passwordReset = PasswordReset::createOrUpdate($email);

            // Format waktu expired dalam timezone WIB (Asia/Jakarta)
            $expiresAtWIB = $passwordReset->otp_expires_at->setTimezone('Asia/Jakarta')->format('H:i');

            // Send OTP ke email
            try {
                Mail::send('emails.otp-notification', [
                    'email' => $email,
                    'otp' => $passwordReset->otp,
                    'user_name' => $user->name,
                    'expires_at' => $expiresAtWIB,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Kode OTP Reset Password - ITLG Lab Management System');
                });

                Log::info('OTP berhasil dikirim', [
                    'email' => $email,
                    'timestamp' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke email Anda.',
                    'data' => [
                        'email' => $email,
                        'otp_expires_in' => $passwordReset->getOTPRemainingSeconds(),
                    ]
                ]);

            } catch (\Exception $mailError) {
                Log::error('Gagal mengirim email OTP', [
                    'email' => $email,
                    'error' => $mailError->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP ke email. Silakan coba lagi.',
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error di sendOTP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Step 2: Verifikasi OTP
     * POST /forgot-password/verify-otp
     */
    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6',
            ]);

            $email = $request->email;
            $otp = $request->otp;

            // Find password reset record
            $passwordReset = PasswordReset::where('email', $email)->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi reset password tidak ditemukan. Silakan mulai dari awal.',
                ], 404);
            }

            // Check OTP validity
            if (!$passwordReset->isOTPValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP telah kadaluarsa. Silakan minta kode OTP baru.',
                    'is_expired' => true,
                ], 400);
            }

            // Verify OTP
            if (!$passwordReset->verifyOTP($otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode OTP tidak sesuai. Silakan cek kembali.',
                ], 400);
            }

            Log::info('OTP berhasil diverifikasi', [
                'email' => $email,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil diverifikasi. Silakan isi password baru.',
                'data' => [
                    'email' => $email,
                    'token' => $passwordReset->token,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error di verifyOTP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Step 3: Reset password dengan password baru
     * POST /forgot-password/reset
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                    'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!$@%]).+$/'
                ],
            ], [
                'password.required' => 'Kata sandi harus diisi',
                'password.min' => 'Kata sandi minimal 6 karakter',
                'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
                'password.regex' => 'Kata sandi harus mengandung huruf, angka, dan karakter khusus (!$@%)',
            ]);

            $email = $request->email;
            $token = $request->token;
            $newPassword = $request->password;

            // Find password reset record
            $passwordReset = PasswordReset::where('email', $email)->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi reset password tidak ditemukan.',
                ], 404);
            }

            // Verify token
            if ($passwordReset->token !== $token || !$passwordReset->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid. Silakan mulai dari awal.',
                ], 400);
            }

            // Find user
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            // Update password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Hapus password reset record
            $passwordReset->completeReset();

            Log::info('Password berhasil direset', [
                'email' => $email,
                'user_id' => $user->user_id,
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil direset. Silakan login dengan password baru.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error di resetPassword', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend OTP
     * POST /forgot-password/resend-otp
     */
    public function resendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->email;

            // Check apakah ada password reset record
            $passwordReset = PasswordReset::where('email', $email)->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi tidak ditemukan. Silakan mulai dari awal.',
                ], 404);
            }

            // Check apakah user ada
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.',
                ], 404);
            }

            // Generate OTP baru
            $passwordReset->update([
                'otp' => PasswordReset::generateOTP(),
                'otp_expires_at' => \Carbon\Carbon::now()->addMinutes(3), // 3 MENIT
                'is_verified' => false,
            ]);

            // Format waktu expired dalam timezone WIB (Asia/Jakarta)
            $expiresAtWIB = $passwordReset->otp_expires_at->setTimezone('Asia/Jakarta')->format('H:i');

            // Send OTP baru ke email
            try {
                Mail::send('emails.otp-notification', [
                    'email' => $email,
                    'otp' => $passwordReset->otp,
                    'user_name' => $user->name,
                    'expires_at' => $expiresAtWIB,
                ], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Kode OTP Reset Password (Ulang) - ITLG Lab Management System');
                });

                Log::info('OTP baru berhasil dikirim', [
                    'email' => $email,
                    'timestamp' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                    'data' => [
                        'email' => $email,
                        'otp_expires_in' => $passwordReset->getOTPRemainingSeconds(),
                    ]
                ]);

            } catch (\Exception $mailError) {
                Log::error('Gagal mengirim email OTP ulang', [
                    'email' => $email,
                    'error' => $mailError->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP ke email. Silakan coba lagi.',
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error di resendOTP', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}