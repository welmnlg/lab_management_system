<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0E2C48 0%, #99391B 100%); padding: 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">ITLG Lab Management System</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #0E2C48; margin-top: 0; font-size: 20px;">Halo, {{ $user_name }}!</h2>
                            <p style="color: #666; line-height: 1.6; margin: 20px 0;">
                                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
                            </p>
                            
                            <p style="color: #666; line-height: 1.6; margin: 20px 0;">
                                Gunakan kode OTP berikut untuk mereset password Anda:
                            </p>
                            
                            <!-- OTP Code Box -->
                            <div style="background-color: #f8f9fa; border-left: 4px solid #0E2C48; padding: 20px; margin: 30px 0; text-align: center;">
                                <p style="color: #666; margin: 0 0 10px 0; font-size: 14px;">Kode OTP Anda:</p>
                                <h1 style="color: #0E2C48; margin: 0; font-size: 36px; letter-spacing: 8px; font-weight: bold;">{{ $otp }}</h1>
                            </div>
                            
                            <p style="color: #666; line-height: 1.6; margin: 20px 0;">
                                ⏰ Kode ini berlaku hingga <strong>{{ $expires_at }} WIB</strong> (3 menit).
                            </p>
                            
                            <p style="color: #d9534f; line-height: 1.6; margin: 20px 0; padding: 15px; background-color: #fef5f5; border-radius: 5px;">
                                <strong>⚠️ Peringatan Keamanan:</strong><br>
                                Jangan bagikan kode OTP ini kepada siapapun, termasuk staff ITLG. Kami tidak akan pernah meminta kode OTP Anda.
                            </p>
                            
                            <p style="color: #666; line-height: 1.6; margin: 20px 0;">
                                Jika Anda tidak merasa melakukan permintaan reset password, abaikan email ini dan pastikan akun Anda aman.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
                            <p style="color: #999; margin: 0; font-size: 12px;">
                                © 2025 ITLG Lab Management System. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>