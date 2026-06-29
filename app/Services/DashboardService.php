<?php

namespace App\Services;

use App\Models\User;
use App\Models\Artikel;
use App\Models\RiwayatChat;
use App\Models\Mood;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    protected $moodService;

    public function __construct(MoodService $moodService)
    {
        $this->moodService = $moodService;
    }

    public function getDashboardData(int $userId)
    {
        $user = User::find($userId);

        // Daily Quote (Cached for 1 day)
        $dailyQuote = Cache::remember('dashboard.daily_quote', now()->addDay(), function () {
            $quotes = [
                [
                    'quote' => 'Kesehatan mental bukanlah tujuan akhir, melainkan sebuah proses. Ini tentang bagaimana Anda mengemudi, bukan kemana Anda pergi.',
                    'author' => 'Noam Shpancer'
                ],
                [
                    'quote' => 'Anda tidak harus mengendalikan pikiran Anda. Anda hanya harus berhenti membiarkan mereka mengendalikan Anda.',
                    'author' => 'Dan Millman'
                ],
                [
                    'quote' => 'Perawatan diri bukanlah keegoisan, melainkan cara menyelaraskan diri untuk dapat memberikan yang terbaik.',
                    'author' => 'Parker Palmer'
                ]
            ];
            return $quotes[array_rand($quotes)];
        });

        // Today's Mood
        $todayMood = Mood::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        // Statistics (menggunakan MoodService)
        $statistics = $this->moodService->getMoodStatistics($userId);

        // Latest Articles
        $latestArticles = Artikel::with('kategori')
            ->where('status', 'published')
            ->latest('tanggal_publish')
            ->take(5)
            ->get()
            ->map(function ($artikel) {
                return [
                    'id' => (string) $artikel->id,
                    'title' => $artikel->judul,
                    'category' => $artikel->kategori->nama_kategori ?? 'Kesehatan',
                    'image_url' => $artikel->thumbnailUrl(),
                    'published_at' => $artikel->tanggal_publish,
                ];
            });

        // Recommended Articles (bisa berdasarkan minat user, sementara random)
        $recommendedArticles = Artikel::with('kategori')
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(3)
            ->get()
            ->map(function ($artikel) {
                return [
                    'id' => (string) $artikel->id,
                    'title' => $artikel->judul,
                    'category' => $artikel->kategori->nama_kategori ?? 'Kesehatan',
                    'image_url' => $artikel->thumbnailUrl(),
                    'published_at' => $artikel->tanggal_publish,
                ];
            });

        // Recent Chat
        $recentChat = RiwayatChat::where('id_user', $userId)
            ->latest('waktu_chat')
            ->take(5)
            ->get()
            ->map(function ($chat) {
                return [
                    'id' => $chat->id,
                    'user_message' => $chat->pesan_user,
                    'ai_reply' => $chat->respon_bot,
                    'time' => $chat->waktu_chat,
                ];
            });

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar_url, // Assuming User has avatar accessor/column
            ],
            'daily_quote' => $dailyQuote,
            'today_mood' => $todayMood ? [
                'id' => $todayMood->id,
                'mood' => $todayMood->mood,
                'note' => $todayMood->note,
                'is_tracked' => true
            ] : [
                'is_tracked' => false
            ],
            'statistics' => [
                'average_sleep' => $statistics['average_sleep'] ?? 0,
                'dominant_mood' => $statistics['dominant_mood'] ?? null,
            ],
            'latest_articles' => $latestArticles,
            'recommended_articles' => $recommendedArticles,
            'recent_chat' => $recentChat,
        ];
    }
}
