<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    // Dummy Data
    private $dummyMoods = [
        [
            'id' => '1',
            'emoji' => '😊',
            'label' => 'Senang',
            'notes' => 'Hari ini produktif banget dan bisa kumpul bareng teman.',
            'sleep_hours' => 8,
            'activities' => ['Kerja', 'Santai'],
            'stress_level' => 3,
            'created_at' => '2026-06-27T08:00:00Z',
        ],
        [
            'id' => '2',
            'emoji' => '😐',
            'label' => 'Biasa',
            'notes' => 'Cuma rebahan seharian.',
            'sleep_hours' => 6,
            'activities' => ['Tidur', 'Nonton'],
            'stress_level' => 5,
            'created_at' => '2026-06-26T08:00:00Z',
        ],
        [
            'id' => '3',
            'emoji' => '😔',
            'label' => 'Sedih',
            'notes' => 'Lagi banyak pikiran dan kurang tidur.',
            'sleep_hours' => 4,
            'activities' => ['Kerja', 'Overthinking'],
            'stress_level' => 8,
            'created_at' => '2026-06-25T08:00:00Z',
        ],
    ];

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->dummyMoods,
        ]);
    }

    public function store(Request $request)
    {
        $newMood = [
            'id' => uniqid(),
            'emoji' => $request->input('emoji', '😀'),
            'label' => $request->input('label', 'Sangat Baik'),
            'notes' => $request->input('notes', ''),
            'sleep_hours' => $request->input('sleep_hours', 7),
            'activities' => $request->input('activities', []),
            'stress_level' => $request->input('stress_level', 5),
            'created_at' => now()->toIso8601String(),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Mood saved successfully',
            'data' => $newMood,
        ], 201);
    }

    public function statistics()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'distribution' => [
                    ['name' => 'Sangat Baik', 'emoji' => '😀', 'percentage' => 30],
                    ['name' => 'Baik', 'emoji' => '😊', 'percentage' => 40],
                    ['name' => 'Biasa', 'emoji' => '😐', 'percentage' => 15],
                    ['name' => 'Buruk', 'emoji' => '😔', 'percentage' => 10],
                    ['name' => 'Sangat Buruk', 'emoji' => '😭', 'percentage' => 5],
                ],
                'stress_trend' => [
                    ['day' => 'Sen', 'level' => 4],
                    ['day' => 'Sel', 'level' => 3],
                    ['day' => 'Rab', 'level' => 6],
                    ['day' => 'Kam', 'level' => 8],
                    ['day' => 'Jum', 'level' => 5],
                    ['day' => 'Sab', 'level' => 3],
                    ['day' => 'Min', 'level' => 2],
                ],
                'average_sleep' => 6.5,
                'dominant_mood' => '😊',
            ],
        ]);
    }
}
