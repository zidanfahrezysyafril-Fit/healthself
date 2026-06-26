<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artikel;
use App\Models\Feedback;
use App\Models\RiwayatChat;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_mahasiswa'  => User::where('role', 'mahasiswa')->count(),
            'total_konselor'   => User::where('role', 'konselor')->count(),
            'total_artikel'    => Artikel::count(),
            'artikel_published'=> Artikel::where('status', 'published')->count(),
            'artikel_pending'  => Artikel::where('status', 'pending')->count(),
            'total_feedback'   => Feedback::count(),
            'total_chat'       => RiwayatChat::count(),
            'avg_rating'       => round(Feedback::avg('rating'), 1),
        ];

        $recentUsers    = User::latest()->take(5)->get();
        $recentFeedback = Feedback::with('user')->latest()->take(5)->get();
        $pendingArtikel = Artikel::with(['pembuat', 'kategori'])->where('status', 'pending')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentFeedback', 'pendingArtikel'));
    }
}
