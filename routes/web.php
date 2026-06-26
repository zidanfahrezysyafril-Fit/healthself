<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ArtikelAdminController;
use App\Http\Controllers\Admin\FeedbackAdminController;
use App\Http\Controllers\Konselor\DashboardController as KonselorDashboard;
use App\Http\Controllers\Konselor\ArtikelKonselorController;
use App\Http\Controllers\Konselor\ChatMonitorController;
use App\Http\Controllers\Konselor\FeedbackKonselorController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\Mahasiswa\FeedbackMahasiswaController;
use App\Models\Artikel;

// =====================
// PUBLIC ROUTES
// =====================
Route::get('/', function () {
    $artikels  = Artikel::with(['kategori', 'pembuat'])
                        ->where('status', 'published')
                        ->latest('tanggal_publish')
                        ->take(6)
                        ->get();
    return view('home', compact('artikels'));
})->name('home');

Route::get('/artikel/{artikel:slug}', function (Artikel $artikel) {
    abort_if($artikel->status !== 'published', 404);
    return view('artikel.show', compact('artikel'));
})->name('artikel.show');

// =====================
// AUTH ROUTES
// =====================
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout',  [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/lock-status', [AuthController::class, 'lockStatus'])->name('lock.status');

// =====================
// OTP ROUTES
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/otp-verify', [AuthController::class, 'showOtpForm'])->name('otp.verify');
    Route::post('/otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.post');
    Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

// =====================
// CHATBOT (Login required)
// =====================
Route::post('/chat', [ChatController::class, 'chat'])->middleware(['auth', 'verified_otp'])->name('chat');

// =====================
// ADMIN ROUTES
// =====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified_otp', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', AdminUser::class);

    // Artikel
    Route::get('/artikel',              [ArtikelAdminController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/create',       [ArtikelAdminController::class, 'create'])->name('artikel.create');
    Route::post('/artikel',             [ArtikelAdminController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{artikel}',    [ArtikelAdminController::class, 'show'])->name('artikel.show');
    Route::get('/artikel/{artikel}/edit',   [ArtikelAdminController::class, 'edit'])->name('artikel.edit');
    Route::put('/artikel/{artikel}',    [ArtikelAdminController::class, 'update'])->name('artikel.update');
    Route::delete('/artikel/{artikel}', [ArtikelAdminController::class, 'destroy'])->name('artikel.destroy');

    // Feedback
    Route::get('/feedback', [FeedbackAdminController::class, 'index'])->name('feedback.index');
});

// =====================
// KONSELOR ROUTES
// =====================
Route::prefix('konselor')->name('konselor.')->middleware(['auth', 'verified_otp', 'role:konselor'])->group(function () {
    Route::get('/dashboard', [KonselorDashboard::class, 'index'])->name('dashboard');

    // Artikel
    Route::get('/artikel',                          [ArtikelKonselorController::class, 'index'])->name('artikel.index');
    Route::get('/artikel/create',                   [ArtikelKonselorController::class, 'create'])->name('artikel.create');
    Route::post('/artikel',                         [ArtikelKonselorController::class, 'store'])->name('artikel.store');
    Route::get('/artikel/{artikel}/validasi',       [ArtikelKonselorController::class, 'showValidasi'])->name('artikel.validasi');
    Route::post('/artikel/{artikel}/approve',       [ArtikelKonselorController::class, 'approve'])->name('artikel.approve');
    Route::post('/artikel/{artikel}/reject',        [ArtikelKonselorController::class, 'reject'])->name('artikel.reject');
    Route::delete('/artikel/{artikel}',             [ArtikelKonselorController::class, 'destroy'])->name('artikel.destroy');

    // Chat Monitor
    Route::get('/chat-monitor',                     [ChatMonitorController::class, 'index'])->name('chat.index');
    Route::get('/chat-monitor/flagged',             [ChatMonitorController::class, 'flaggedChats'])->name('chat.flagged');
    Route::get('/chat-monitor/{user}',              [ChatMonitorController::class, 'detail'])->name('chat.detail');
    Route::post('/chat-monitor/{chat}/flag',        [ChatMonitorController::class, 'flag'])->name('chat.flag');
    Route::post('/chat-monitor/{chat}/unflag',      [ChatMonitorController::class, 'unflag'])->name('chat.unflag');
    Route::post('/chat-monitor/{chat}/comment',     [ChatMonitorController::class, 'sendComment'])->name('chat.comment');

    // Feedback
    Route::get('/feedback', [FeedbackKonselorController::class, 'index'])->name('feedback.index');
});

// =====================
// MAHASISWA ROUTES
// =====================
Route::prefix('profil')->name('mahasiswa.')->middleware(['auth', 'verified_otp', 'role:mahasiswa'])->group(function () {
    Route::get('/',          [ProfileController::class, 'index'])->name('profile');
    Route::get('/feedback',  [FeedbackMahasiswaController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackMahasiswaController::class, 'store'])->name('feedback.store');
});