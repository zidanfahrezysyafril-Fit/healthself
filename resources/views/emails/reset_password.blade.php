<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Atur Ulang Kata Sandi - HealthSelf</title>
</head>
<body style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 24px; color: #374151;">
    <div style="max-width: 540px; margin: 0 auto; background: #ffffff; padding: 36px 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; width: 48px; height: 48px; line-height: 48px; border-radius: 12px; background: #fef2f2; color: #800000; font-size: 24px; font-weight: bold;">
                🔒
            </div>
            <h2 style="color: #800000; margin: 12px 0 6px; font-size: 22px; font-weight: 800;">Atur Ulang Kata Sandi</h2>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Permintaan pengaturan ulang kata sandi akun HealthSelf</p>
        </div>

        <p style="font-size: 14px; line-height: 1.6; color: #374151;">
            Halo,
        </p>
        <p style="font-size: 14px; line-height: 1.6; color: #4b5563;">
            Kami menerima permintaan untuk mengatur ulang kata sandi akun HealthSelf Anda. Masukkan kode verifikasi sekali pakai (OTP) berikut ke dalam formulir:
        </p>
        
        <div style="background: linear-gradient(135deg, #fff5f5, #fef2f2); padding: 20px; text-align: center; margin: 24px 0; border-radius: 12px; border: 1.5px dashed #fca5a5;">
            <div style="font-size: 12px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Kode Verifikasi OTP Anda</div>
            <div style="font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #800000; font-family: monospace;">{{ $otp }}</div>
            <div style="font-size: 12px; color: #6b7280; margin-top: 6px;">Berlaku selama <strong>5 menit</strong></div>
        </div>
        
        <div style="background: #f9fafb; border-left: 4px solid #800000; padding: 12px 14px; border-radius: 6px; margin: 20px 0; font-size: 12.5px; color: #4b5563; line-height: 1.5;">
            <strong>Keamanan Akun:</strong> Jangan pernah membagikan kode OTP ini kepada siapa pun. Jika Anda tidak melakukan permintaan ini, segera abaikan pesan ini. Kata sandi Anda akan tetap aman.
        </div>

        <hr style="border: none; border-top: 1px solid #f3f4f6; margin: 28px 0 20px;">

        <p style="font-size: 12px; color: #9ca3af; text-align: center; margin: 0;">
            Email ini dikirim otomatis oleh sistem <strong>HealthSelf</strong>.<br>
            © {{ date('Y') }} HealthSelf. All rights reserved.
        </p>
    </div>
</body>
</html>
