<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\LoginAttempt;

class AuthController extends Controller
{
    const MAX_ATTEMPTS = 5;
    const LOCK_MINUTES = 15;

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|ends_with:@gmail.com',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk (login).',
            'email.ends_with'   => 'Harap gunakan akun Google (@gmail.com) untuk mendaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa', // Default register adalah mahasiswa
        ]);

        $this->sendOtp($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('otp.verify')->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk kode OTP.');
    }

    private function sendOtp(User $user)
    {
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $user->id, $otp, now()->addMinutes(3));
        Mail::to($user->email)->send(new OtpMail($otp));
    }

    public function showOtpForm()
    {
        if (Auth::user()->email_verified_at) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric|digits:6']);
        $user = Auth::user();

        $cachedOtp = Cache::get('otp_' . $user->id);

        if (!$cachedOtp) {
            return back()->with('error', 'Kode OTP telah kedaluwarsa. Silakan minta ulang.');
        }

        if ($cachedOtp != $request->otp_code) {
            return back()->with('error', 'Kode OTP salah.');
        }

        // Valid
        Cache::forget('otp_' . $user->id);
        $user->update(['email_verified_at' => now()]);

        return $this->redirectByRole($user)->with('success', 'Email berhasil diverifikasi! Selamat datang di HealthSelf.');
    }

    public function resendOtp()
    {
        $user = Auth::user();
        if ($user->email_verified_at) {
            return $this->redirectByRole($user);
        }

        $this->sendOtp($user);
        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('email');
        $ip         = $request->ip();

        // Cek brute force – email
        $attempt = LoginAttempt::firstOrCreate(
            ['identifier' => $identifier],
            ['attempts' => 0, 'locked_until' => null]
        );

        // Cek juga by IP
        $ipAttempt = LoginAttempt::firstOrCreate(
            ['identifier' => $ip],
            ['attempts' => 0, 'locked_until' => null]
        );

        if ($attempt->isLocked() || $ipAttempt->isLocked()) {
            $secs = max($attempt->secondsRemaining(), $ipAttempt->secondsRemaining());
            return back()->withErrors([
                'email' => 'Akun terkunci. Coba lagi dalam ' . ceil($secs / 60) . ' menit.',
            ])->with('locked_seconds', $secs)->withInput($request->only('email'));
        }

        // Validasi reCAPTCHA (skip jika key belum diset)
        if (env('RECAPTCHA_SECRET_KEY') && env('RECAPTCHA_SECRET_KEY') !== 'your-recaptcha-secret-key') {
            $captcha = $request->input('g-recaptcha-response');
            if (!$captcha) {
                return back()->withErrors(['captcha' => 'Verifikasi CAPTCHA diperlukan.'])->withInput($request->only('email'));
            }
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => env('RECAPTCHA_SECRET_KEY'),
                'response' => $captcha,
                'remoteip' => $ip,
            ]);
            if (!$response->json('success')) {
                return back()->withErrors(['captcha' => 'CAPTCHA tidak valid. Coba lagi.'])->withInput($request->only('email'));
            }
        }

        // Coba login
        if (Auth::attempt(['email' => $identifier, 'password' => $request->password], $request->boolean('remember'))) {
            // Reset attempts
            $attempt->update(['attempts' => 0, 'locked_until' => null]);
            $ipAttempt->update(['attempts' => 0, 'locked_until' => null]);

            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        // Gagal – tambah attempt
        $newCount = $attempt->attempts + 1;
        $lockedUntil = $newCount >= self::MAX_ATTEMPTS ? now()->addMinutes(self::LOCK_MINUTES) : null;
        $attempt->update(['attempts' => $newCount, 'locked_until' => $lockedUntil]);

        $ipNew = $ipAttempt->attempts + 1;
        $ipLocked = $ipNew >= self::MAX_ATTEMPTS ? now()->addMinutes(self::LOCK_MINUTES) : null;
        $ipAttempt->update(['attempts' => $ipNew, 'locked_until' => $ipLocked]);

        $remaining = self::MAX_ATTEMPTS - $newCount;

        if ($lockedUntil) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan. Akun terkunci selama ' . self::LOCK_MINUTES . ' menit.',
            ])->with('locked_seconds', self::LOCK_MINUTES * 60)->withInput($request->only('email'));
        }

        return back()->withErrors([
            'email' => 'Username dan password salah. Sisa percobaan: ' . max(0, $remaining),
        ])->withInput($request->only('email'));
    }

    public function redirectToGoogle()
    {
        // Cek jika Google tidak dikonfigurasi
        if (env('GOOGLE_CLIENT_ID') === 'your-google-client-id') {
            return redirect()->route('login')->with('error', 'Login Google belum dikonfigurasi. Hubungi administrator.');
        }
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();
        } catch (\Exception $e) {
            $msg = $e->getMessage() ?: get_class($e);
            \Log::error('Google Login Error: ' . $msg);
            return redirect()->route('login')->with('error', 'Login Google gagal: ' . $msg);
        }

        // Cek brute force by IP
        $ip = $request->ip();
        $ipAttempt = LoginAttempt::where('identifier', $ip)->first();
        if ($ipAttempt && $ipAttempt->isLocked()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'IP Anda terkunci. Coba lagi nanti.');
        }

        // Cari atau buat user
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // Update google_id & avatar jika belum ada
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'role'              => 'mahasiswa',
                'password'          => null,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    public function lockStatus(Request $request)
    {
        $identifier = $request->query('email', $request->ip());
        $attempt = LoginAttempt::where('identifier', $identifier)->first();

        if (!$attempt || !$attempt->isLocked()) {
            return response()->json(['locked' => false, 'seconds' => 0]);
        }

        return response()->json([
            'locked'  => true,
            'seconds' => $attempt->secondsRemaining(),
        ]);
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'konselor' => redirect()->route('konselor.dashboard'),
            default    => redirect()->route('home'),
        };
    }
}
