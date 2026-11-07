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
use App\Mail\ResetPasswordOtpMail;
use App\Models\User;
use App\Models\LoginAttempt;

class AuthController extends Controller
{
    const MAX_ATTEMPTS = 5;
    const LOCK_SECONDS = 40;

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
        $otp = (string) random_int(100000, 999999);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'register_' . $user->id],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );
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

        $record = \DB::table('password_reset_tokens')->where('email', 'register_' . $user->id)->first();

        if (!$record) {
            return back()->with('error', 'Kode OTP telah kedaluwarsa atau belum diminta. Silakan klik "Kirim Ulang".');
        }

        // Berlaku selama 5 menit
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(5)->isPast()) {
            \DB::table('password_reset_tokens')->where('email', 'register_' . $user->id)->delete();
            return back()->with('error', 'Kode OTP telah kedaluwarsa (lebih dari 5 menit). Silakan klik "Kirim Ulang".');
        }

        if (!Hash::check((string) $request->otp_code, $record->token)) {
            return back()->with('error', 'Kode OTP yang dimasukkan salah.');
        }

        // Valid - hapus token sekali pakai
        \DB::table('password_reset_tokens')->where('email', 'register_' . $user->id)->delete();
        $user->update(['email_verified_at' => now()]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan.');
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
                'email' => 'Akun terkunci sementara. Coba lagi dalam ' . $secs . ' detik.',
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
        $lockedUntil = $newCount >= self::MAX_ATTEMPTS ? now()->addSeconds(self::LOCK_SECONDS) : null;
        $attempt->update(['attempts' => $newCount, 'locked_until' => $lockedUntil]);

        $ipNew = $ipAttempt->attempts + 1;
        $ipLocked = $ipNew >= self::MAX_ATTEMPTS ? now()->addSeconds(self::LOCK_SECONDS) : null;
        $ipAttempt->update(['attempts' => $ipNew, 'locked_until' => $ipLocked]);

        $remaining = self::MAX_ATTEMPTS - $newCount;

        if ($lockedUntil) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan gagal. Akun terkunci sementara selama ' . self::LOCK_SECONDS . ' detik.',
            ])->with('locked_seconds', self::LOCK_SECONDS)->withInput($request->only('email'));
        }

        return back()->withErrors([
            'email' => 'Username dan password salah. Sisa percobaan: ' . max(0, $remaining),
        ])->withInput($request->only('email'));
    }

    /**
     * Kirim OTP untuk Atur Ulang Kata Sandi
     * Memenuhi prinsip keamanan:
     * - Jangan izinkan upaya menebak ID login (pesan seragam)
     * - Rate limiting / cooldown brute force
     * - Token sekali pakai dengan masa berlaku (5 menit)
     * - Disimpan di tabel password_reset_tokens Supabase agar persisten di Serverless
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = trim(strtolower($request->email));

        // Cek cooldown 40 detik antar permintaan dari database
        $existing = \DB::table('password_reset_tokens')->where('email', $email)->first();
        if ($existing && $existing->created_at) {
            $secondsSince = abs(now()->diffInSeconds(\Carbon\Carbon::parse($existing->created_at)));
            if ($secondsSince < 40) {
                $wait = 40 - (int) $secondsSince;
                return response()->json([
                    'success' => false,
                    'message' => "Permintaan terlalu cepat. Tunggu {$wait} detik sebelum meminta kode baru.",
                ], 429);
            }
        }

        $user = User::where('email', $email)->first();

        // Jika user terdaftar, buat OTP acak 6 digit dan kirim email
        if ($user) {
            $otp = (string) random_int(100000, 999999);

            // Simpan hash OTP ke tabel password_reset_tokens di database
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token'      => Hash::make($otp),
                    'created_at' => now(),
                ]
            );

            try {
                Mail::to($user->email)->send(new ResetPasswordOtpMail($otp));
            } catch (\Throwable $e) {
                \Log::error('Gagal mengirim email reset OTP ke ' . $email . ': ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim email verifikasi: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Pesan seragam untuk mencegah identitas pengguna ditebak (User Enumeration Defense)
        return response()->json([
            'success' => true,
            'message' => 'Jika email Anda terdaftar, kode verifikasi 6 digit telah dikirimkan ke email Anda. Silakan cek inbox/spam.',
            'email'   => $email,
        ]);
    }

    /**
     * Verifikasi OTP dan Atur Ulang Kata Sandi Baru
     * Memenuhi prinsip keamanan:
     * - Kebijakan password konsisten (min 8 karakter, confirmed)
     * - Batalkan semua sesi yang ada setelah reset berhasil
     * - Token sekali pakai (langsung dihapus setelah verifikasi)
     * - Strong hashing (Hash::make / Bcrypt)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'otp'                   => 'required|numeric|digits:6',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'email.required'        => 'Email wajib diisi.',
            'otp.required'          => 'Kode verifikasi OTP wajib diisi.',
            'otp.digits'            => 'Kode verifikasi OTP harus terdiri dari 6 digit.',
            'password.required'     => 'Kata sandi baru wajib diisi.',
            'password.min'          => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $email = trim(strtolower($request->email));

        $record = \DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi telah kedaluwarsa atau belum diminta. Silakan minta kode baru.',
            ], 422);
        }

        // Cek masa berlaku token akses (5 menit)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(5)->isPast()) {
            \DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi telah kedaluwarsa (lebih dari 5 menit). Silakan minta kode baru.',
            ], 422);
        }

        // Verifikasi OTP
        if (!Hash::check((string) $request->otp, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi OTP yang dimasukkan salah.',
            ], 422);
        }

        // Hapus token sekali pakai langsung setelah verifikasi berhasil
        \DB::table('password_reset_tokens')->where('email', $email)->delete();

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        // Update password dengan hashing yang kuat
        $user->password = Hash::make($request->password);
        // Batalkan sesi yang ada: regenerate remember token
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->save();

        // Batalkan sesi di database jika tabel sessions digunakan
        try {
            \DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {}

        // Invalidate sesi aktif saat ini
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Reset hitungan percobaan gagal pada email dan IP ini
        LoginAttempt::where('identifier', $email)
            ->orWhere('identifier', $request->ip())
            ->update(['attempts' => 0, 'locked_until' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diatur ulang! Silakan masuk dengan kata sandi baru Anda.',
        ]);
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
