<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\RiwayatChat;
use App\Models\Feedback;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'artikel_pending'    => Artikel::where('status', 'pending')->count(),
            'artikel_dibuat'     => Artikel::where('id_user', auth()->id())->count(),
            'artikel_divalidasi' => Artikel::where('id_konselor', auth()->id())->count(),
            'total_chat'         => RiwayatChat::count(),
            'chat_flagged'       => RiwayatChat::where('is_flagged', true)->count(),
            'total_feedback'     => Feedback::count(),
            'avg_rating'         => round(Feedback::avg('rating'), 1),
        ];

        $pendingArtikel  = Artikel::with(['pembuat', 'kategori'])->where('status', 'pending')->latest()->take(5)->get();
        $recentChats     = RiwayatChat::with('user')->latest('waktu_chat')->take(10)->get();
        $recentFeedback  = Feedback::with('user')->latest()->take(5)->get();

        return view('konselor.dashboard', compact('stats', 'pendingArtikel', 'recentChats', 'recentFeedback'));
    }
}
