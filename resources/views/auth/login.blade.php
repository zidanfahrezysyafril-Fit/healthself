<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk – HealthSelf</title>
    <meta name="description" content="Login ke platform HealthSelf untuk mengakses konsultasi kesehatan digital dan artikel kesehatan.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') !== 'your-recaptcha-site-key')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: #f4f6f9; }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            background: #f4f6f9;
        }

        /* LEFT PANEL */
        .login-left {
            flex: 1.1;
            background: linear-gradient(135deg, #570303 0%, #800000 40%, #b50000 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
            border-top-right-radius: 40px;
            border-bottom-right-radius: 40px;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
            z-index: 2;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -60px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .brand-logo {
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 12px;
            position: relative; z-index: 1;
        }
        .brand-tagline {
            color: rgba(255,255,255,0.75);
            font-size: 1.05rem;
            text-align: center;
            max-width: 340px;
            line-height: 1.7;
            position: relative; z-index: 1;
        }
        .feature-list {
            margin-top: 48px;
            list-style: none;
            padding: 0;
            position: relative; z-index: 1;
            width: 100%;
            max-width: 340px;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.85);
            font-size: 0.95rem;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .feature-list li:last-child { border-bottom: none; }
        .feature-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* RIGHT PANEL */
        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 24px;
            position: relative;
        }

        /* FLOATING FORM CARD */
        .form-card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 32px 32px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            animation: floatingForm 6s ease-in-out infinite;
        }

        @keyframes floatingForm {
            0%, 100% { transform: translateY(0); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
            50% { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.12); }
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 4px;
        }
        .login-subtitle {
            color: #6b7280;
            font-size: 0.8rem;
            margin-bottom: 20px;
        }

        /* FORM */
        .form-group { margin-bottom: 12px; }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.75rem;
            color: #374151;
            margin-bottom: 4px;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            font-family: inherit;
            transition: all 0.2s;
            outline: none;
            background: #fafafa;
        }
        .form-input:focus {
            border-color: #800000;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(128,0,0,0.08);
        }
        .form-input.error { border-color: #ef4444; }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: #111; }
        .password-toggle svg { width: 16px; height: 16px; }

        .error-msg {
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 4px;
        }

        /* DIVIDER */
        .divider {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
            color: #9ca3af;
            font-size: 0.75rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* GOOGLE BUTTON */
        .btn-google {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-family: inherit;
            margin-bottom: 12px;
        }
        .btn-google:hover {
            border-color: #d1d5db;
            background: #f9fafb;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        }
        .google-icon { width: 18px; height: 18px; }

        /* SUBMIT BUTTON */
        .btn-submit {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, #800000, #570303);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            margin-top: 4px;
            position: relative;
            overflow: hidden;
        }
        .btn-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(128,0,0,0.25);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ALERT */
        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #166534;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            color: #991b1b;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        /* REMEMBER & FORGOT */
        .form-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-footer label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #6b7280;
            cursor: pointer;
        }
        .form-footer a {
            font-size: 0.875rem;
            color: #800000;
            font-weight: 600;
            text-decoration: none;
        }
        .form-footer a:hover { text-decoration: underline; }

        /* LOCK BOX */
        .lock-box {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 16px;
            display: none;
        }
        .lock-box.visible { display: block; }
        .lock-title {
            font-weight: 700;
            color: #991b1b;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        .lock-countdown {
            font-size: 1.6rem;
            font-weight: 800;
            color: #800000;
            font-variant-numeric: tabular-nums;
        }
        .lock-desc {
            color: #b91c1c;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        @media (max-width: 960px) {
            .login-wrapper { flex-direction: column; background: #fff; }
            .login-left { display: none; }
            .login-right { flex: 1; padding: 40px 24px; align-items: stretch; }
            .form-card { box-shadow: none; animation: none; padding: 0; }
            .mobile-logo-wrapper { display: flex !important; }
        }
    </style>
</head>
<body>
<div class="login-wrapper">

    {{-- LEFT PANEL --}}
    <div class="login-left">
        <div class="brand-logo" style="display:flex; align-items:center; gap:14px;">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:52px; height:52px; border-radius:12px; object-fit:cover; mix-blend-mode: multiply;">
            <div style="display:flex; flex-direction:column; justify-content:center; text-align:left;">
                <span style="font-size: 26px; font-weight: 900; line-height: 1; letter-spacing: -0.5px;">HealthSelf</span>
                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px; opacity: 0.85; line-height: 1;">Mind & Body Care</span>
            </div>
        </div>
        <p class="brand-tagline">
            Platform kesehatan digital terpercaya untuk mahasiswa. Konsultasi, edukasi, dan dukungan kesehatan mental dalam satu tempat.
        </p>
        <ul class="feature-list">
            <li>
                <div class="feature-icon">🧠</div>
                <span>Artikel kesehatan terverifikasi oleh konselor profesional</span>
            </li>
            <li>
                <div class="feature-icon">💬</div>
                <span>Chatbot AI kesehatan aktif 24 jam sehari</span>
            </li>
            <li>
                <div class="feature-icon">🔒</div>
                <span>Data dan privasi percakapan Anda terjamin aman</span>
            </li>
            <li>
                <div class="feature-icon">👨‍⚕️</div>
                <span>Dipantau oleh konselor & dokter berpengalaman</span>
            </li>
        </ul>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="login-right">
        <div class="form-card">
            <div class="mobile-logo-wrapper" style="display:none; justify-content:center; margin-bottom:24px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="width:70px; height:70px; border-radius:16px; object-fit:cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            </div>
            <h1 class="login-title">Selamat Datang 👋</h1>
            <p class="login-subtitle">Masuk ke akun Anda untuk mulai</p>

            {{-- SUCCESS / ERROR ALERT --}}
            @if(session('success'))
                <div class="alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">⚠ {{ session('error') }}</div>
            @endif

            {{-- GOOGLE LOGIN --}}
            <a href="{{ route('auth.google') }}" class="btn-google" id="btn-google">
                <svg class="google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="divider">atau masuk dengan email</div>

            {{-- LOGIN FORM --}}
            <form method="POST" action="{{ route('login.post') }}" id="login-form">
                @csrf

                @if($errors->has('email') || $errors->has('captcha'))
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div>⚠ {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-container">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            style="padding-right: 48px;"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-footer">
                    <label>
                        <input type="checkbox" name="remember" style="accent-color:#800000;">
                        Ingat saya
                    </label>
                </div>

                {{-- RECAPTCHA --}}
                @if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') !== 'your-recaptcha-site-key')
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        @error('captcha')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- LOCK BOX COUNTDOWN --}}
                <div class="lock-box {{ session('locked_seconds') ? 'visible' : '' }}" id="lock-box">
                    <div class="lock-title">🔒 Akun Terkunci Sementara</div>
                    <div class="lock-countdown" id="countdown-timer">--:--</div>
                    <div class="lock-desc">Terlalu banyak percobaan gagal. Tunggu hingga waktu berakhir untuk mencoba lagi.</div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    Masuk ke Akun
                </button>

                <div style="text-align:center; margin-top:20px; font-size:0.85rem; color:#6b7280;">
                    Belum punya akun? <a href="{{ route('register') }}" style="color:#800000; font-weight:700; text-decoration:none;">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // COUNTDOWN TIMER (Realtime)
    let countdownInterval = null;
    let secondsLeft = {{ session('locked_seconds', 0) }};

    function formatTime(secs) {
        const m = String(Math.floor(secs / 60)).padStart(2, '0');
        const s = String(secs % 60).padStart(2, '0');
        return `${m}:${s}`;
    }

    function startCountdown(secs) {
        const lockBox    = document.getElementById('lock-box');
        const timer      = document.getElementById('countdown-timer');
        const submitBtn  = document.getElementById('submit-btn');
        const googleBtn  = document.getElementById('btn-google');
        const form       = document.getElementById('login-form');

        if (secs <= 0) return;

        lockBox.classList.add('visible');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Akun Terkunci';
        if (googleBtn) googleBtn.style.pointerEvents = 'none';
        form.querySelectorAll('input').forEach(i => i.disabled = true);

        let remaining = secs;
        timer.textContent = formatTime(remaining);

        countdownInterval = setInterval(() => {
            remaining--;
            timer.textContent = formatTime(remaining);
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                lockBox.classList.remove('visible');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Masuk ke Akun';
                if (googleBtn) googleBtn.style.pointerEvents = '';
                form.querySelectorAll('input').forEach(i => i.disabled = false);
            }
        }, 1000);
    }

    if (secondsLeft > 0) {
        startCountdown(secondsLeft);
    }

    // Poll lock status setiap 10 detik jika belum ada countdown
    const emailInput = document.getElementById('email');
    if (emailInput && secondsLeft === 0) {
        setInterval(async () => {
            const email = emailInput.value;
            if (!email) return;
            try {
                const res = await fetch(`{{ route('lock.status') }}?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.locked && data.seconds > 0 && !countdownInterval) {
                    startCountdown(data.seconds);
                }
            } catch(e) {}
        }, 10000);
    }

    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        if (type === 'text') {
            btn.innerHTML = `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>`;
        } else {
            btn.innerHTML = `<svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
        }
    }
</script>
</body>
</html>
