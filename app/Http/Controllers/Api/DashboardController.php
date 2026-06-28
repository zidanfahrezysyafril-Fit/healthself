<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function articles()
    {
        try {
            $articles = Artikel::with('kategori')
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
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $articles
            ]);
        } catch (\Exception $e) {
            // Fallback dummy data if table doesn't exist yet in local setup
            return response()->json([
                'status' => 'success',
                'data' => [
                    [
                        'id' => '1',
                        'title' => 'Pentingnya Kesadaran Diri (Mindfulness)',
                        'category' => 'Psikologi',
                        'image_url' => 'https://picsum.photos/seed/10/400/200'
                    ],
                    [
                        'id' => '2',
                        'title' => 'Cara Mengatasi Kecemasan Berlebih',
                        'category' => 'Kesehatan',
                        'image_url' => 'https://picsum.photos/seed/11/400/200'
                    ]
                ]
            ]);
        }
    }

    public function quotes()
    {
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

        return response()->json([
            'status' => 'success',
            'data' => $quotes[array_rand($quotes)]
        ]);
    }

    public function moodsToday(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'emoji' => '🙂',
                'label' => 'Tenang',
                'is_tracked' => true
            ]
        ]);
    }
}
