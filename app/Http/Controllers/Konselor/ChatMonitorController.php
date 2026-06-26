<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\RiwayatChat;
use App\Models\KonselorComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ChatMonitorController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua user yang punya riwayat chat
        $query = User::where('role', 'mahasiswa')
                     ->whereHas('riwayatChat');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%");
            });
        }

        $users = $query->withCount('riwayatChat')
                       ->with(['riwayatChat' => function ($q) {
                           $q->latest('waktu_chat')->limit(1);
                       }])
                       ->latest()
                       ->paginate(15);

        $flaggedCount = RiwayatChat::where('is_flagged', true)->count();

        return view('konselor.chat-monitor.index', compact('users', 'flaggedCount'));
    }

    public function detail(Request $request, User $user)
    {
        $chats = RiwayatChat::where('id_user', $user->id)
                             ->with('komentar.konselor')
                             ->orderBy('waktu_chat')
                             ->get();

        return view('konselor.chat-monitor.detail', compact('user', 'chats'));
    }

    public function flag(Request $request, RiwayatChat $chat)
    {
        $request->validate(['flag_reason' => 'required|string|max:255']);
        $chat->update([
            'is_flagged'  => true,
            'flag_reason' => $request->flag_reason,
        ]);
        return back()->with('success', 'Pesan berhasil ditandai sebagai berbahaya.');
    }

    public function unflag(RiwayatChat $chat)
    {
        $chat->update(['is_flagged' => false, 'flag_reason' => null]);
        return back()->with('success', 'Flag berhasil dihapus.');
    }

    public function sendComment(Request $request, RiwayatChat $chat)
    {
        $request->validate([
            'komentar' => 'required|string|min:10|max:1000',
        ]);

        $user = $chat->user;

        $comment = KonselorComment::create([
            'id_riwayat' => $chat->id,
            'id_konselor'=> auth()->id(),
            'id_user'    => $user->id,
            'komentar'   => $request->komentar,
            'email_sent' => false,
        ]);

        // Kirim email (jika SMTP dikonfigurasi)
        try {
            Mail::send('emails.konselor-comment', [
                'user'     => $user,
                'konselor' => auth()->user(),
                'komentar' => $request->komentar,
                'chat'     => $chat,
            ], function ($m) use ($user) {
                $m->to($user->email, $user->name)
                  ->subject('[HealthSelf] Pesan dari Konselor untuk Anda');
            });
            $comment->update(['email_sent' => true]);
        } catch (\Exception $e) {
            // Email gagal, tidak apa-apa (logged)
        }

        return back()->with('success', 'Komentar berhasil dikirim ke pengguna.');
    }

    public function flaggedChats()
    {
        $chats = RiwayatChat::with('user')
                             ->where('is_flagged', true)
                             ->latest('waktu_chat')
                             ->paginate(20);
        return view('konselor.chat-monitor.flagged', compact('chats'));
    }
}
