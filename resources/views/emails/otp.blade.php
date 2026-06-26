<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi OTP HealthSelf</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #800000; text-align: center;">Verifikasi Email Anda</h2>
        <p>Halo,</p>
        <p>Terima kasih telah mendaftar di HealthSelf. Silakan gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda.</p>
        
        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; margin: 20px 0; border-radius: 8px; border: 1px dashed #ccc;">
            <h1 style="margin: 0; font-size: 36px; letter-spacing: 5px; color: #111;">{{ $otp }}</h1>
        </div>
        
        <p>Kode ini berlaku selama <strong>3 menit</strong>. Jika Anda tidak merasa mendaftar di HealthSelf, abaikan email ini.</p>
        <p>Salam hangat,<br>Tim HealthSelf</p>
    </div>
</body>
</html>
