<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP – HealthSelf</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); width: 100%; max-width: 400px; text-align: center; }
        .title { font-size: 1.5rem; font-weight: 800; color: #111; margin-bottom: 8px; }
        .subtitle { color: #6b7280; font-size: 0.9rem; margin-bottom: 24px; line-height: 1.5; }
        .otp-inputs { display: flex; justify-content: space-between; margin-bottom: 24px; gap: 8px; }
        .otp-input { width: 100%; height: 50px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 1.5px solid #e5e7eb; border-radius: 10px; outline: none; transition: all 0.2s; }
        .otp-input:focus { border-color: #800000; box-shadow: 0 0 0 3px rgba(128,0,0,0.1); }
        .btn-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, #800000, #570303); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 1rem; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(128,0,0,0.2); }
        .resend { margin-top: 20px; font-size: 0.85rem; color: #6b7280; }
        .resend a { color: #800000; font-weight: 700; text-decoration: none; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: left; }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
        .alert-error { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
    </style>
</head>
<body>

<div class="card">
    <h1 class="title">Verifikasi Email ✉️</h1>
    <p class="subtitle">Kami telah mengirimkan 6 digit kode OTP ke email <strong>{{ auth()->user()->email }}</strong>. Kode berlaku selama 3 menit.</p>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">⚠ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $err)
                <div>⚠ {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify.post') }}" id="otpForm">
        @csrf
        <div class="otp-inputs">
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required autofocus>
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
            <input type="text" maxlength="1" class="otp-input" name="otp[]" required>
        </div>
        <input type="hidden" name="otp_code" id="otp_hidden">
        
        <button type="submit" class="btn-submit">Verifikasi</button>
    </form>

    <div class="resend">
        Belum menerima kode? 
        <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#800000;font-weight:700;cursor:pointer;padding:0;font-family:inherit;font-size:inherit;">Kirim Ulang</button>
        </form>
    </div>
    
    <div style="margin-top: 20px; font-size:0.85rem;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#6b7280;text-decoration:underline;cursor:pointer;">Kembali ke Login</button>
        </form>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otp_hidden');
    const form = document.getElementById('otpForm');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    form.addEventListener('submit', () => {
        let otp = '';
        inputs.forEach(input => otp += input.value);
        hiddenInput.value = otp;
    });
</script>

</body>
</html>
