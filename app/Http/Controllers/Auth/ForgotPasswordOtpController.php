<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Notifications\SendOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class ForgotPasswordOtpController extends Controller
{
    // Kirim OTP ke email
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();

        $otpCode = rand(100000, 999999); // 6 digit

        // Hapus OTP lama yang belum kadaluarsa:
        Otp::where('user_id', $user->id)->delete();

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(15), // Expired 15 menit
        ]);

        $user->notify(new SendOtpNotification($otpCode));
        return response()->json(['message' => 'Kode OTP telah dikirim ke email.']);
    }

    // Verifikasi OTP
    public function checkOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp_code' => 'required|digits:6',
        ]);
        $user = User::where('email', $request->email)->first();

        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Kode OTP salah atau sudah kedaluwarsa.'], 422);
        }

        // Sukses, hapus OTP agar sekali pakai
        $otp->delete();
        // Bisa set flag sesi untuk lanjut reset password
        session(['otp_verified_user' => $user->id]);
        return response()->json(['message' => 'OTP valid. Silakan buat password baru.']);
    }

    // Reset password (panggil setelah OTP sukses diverifikasi)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:9|max:12|confirmed',
        ]);

        $userId = session('otp_verified_user');
        if (!$userId) {
            return response()->json(['message' => 'OTP belum diverifikasi.'], 403);
        }
        $user = User::findOrFail($userId);
        $user->password = bcrypt($request->password);
        $user->save();

        session()->forget('otp_verified_user');
        return response()->json(['message' => 'Password baru sudah disimpan, silakan login!']);
    }
}