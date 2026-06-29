<?php

namespace App\Services;

use App\Repositories\Contracts\MoodRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MoodService
{
    protected $moodRepository;

    public function __construct(MoodRepositoryInterface $moodRepository)
    {
        $this->moodRepository = $moodRepository;
    }

    public function getUserMoods(int $userId)
    {
        return $this->moodRepository->getByUserId($userId);
    }

    public function createMood(int $userId, array $data)
    {
        $data['user_id'] = $userId;
        return $this->moodRepository->create($data);
    }

    public function updateMood(int $id, int $userId, array $data)
    {
        // Pastikan hanya user bersangkutan yang bisa mengedit
        $this->moodRepository->findByIdAndUserId($id, $userId);
        return $this->moodRepository->update($id, $data);
    }

    public function deleteMood(int $id, int $userId)
    {
        // Pastikan hanya user bersangkutan yang bisa menghapus
        $this->moodRepository->findByIdAndUserId($id, $userId);
        return $this->moodRepository->delete($id);
    }

    public function getMoodStatistics(int $userId)
    {
        $moods = $this->moodRepository->getByUserId($userId);

        if ($moods->isEmpty()) {
            return [
                'weekly_moods' => [],
                'monthly_moods' => [],
                'yearly_moods' => [],
                'distribution' => [],
                'average_sleep' => 0,
                'dominant_mood' => null,
                'stress_trend' => [],
            ];
        }

        // Kalkulasi Statistik Dasar
        $averageSleep = $moods->avg('sleep_hours') ?? 0;
        
        // Mood Distribution
        $distribution = $moods->groupBy('mood')->map(function ($group, $mood) use ($moods) {
            return [
                'name' => $mood,
                'percentage' => round(($group->count() / $moods->count()) * 100, 2)
            ];
        })->values();

        // Dominant Mood
        $dominantMood = $distribution->sortByDesc('percentage')->first()['name'] ?? null;

        // Stress Trend (7 Hari Terakhir)
        $last7Days = $moods->filter(function ($mood) {
            return $mood->created_at >= Carbon::now()->subDays(7);
        })->sortBy('created_at');

        $stressTrend = $last7Days->map(function ($mood) {
            return [
                'day' => $mood->created_at->locale('id')->isoFormat('ddd'),
                'level' => $mood->stress_level
            ];
        })->values();

        // Weekly Moods
        $weeklyMoods = $last7Days->map(function ($mood) {
            return [
                'day' => $mood->created_at->locale('id')->isoFormat('ddd'),
                'mood' => $mood->mood
            ];
        })->values();

        // Monthly Moods
        $last30Days = $moods->filter(function ($mood) {
            return $mood->created_at >= Carbon::now()->subDays(30);
        })->sortBy('created_at');

        $monthlyMoods = $last30Days->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('W');
        })->map(function ($weekGroup, $weekNum) {
            // Rata-rata stress mingguan dalam sebulan
            return [
                'week' => 'Minggu ' . $weekNum,
                'average_stress' => round($weekGroup->avg('stress_level'), 1)
            ];
        })->values();

        return [
            'weekly_moods' => $weeklyMoods,
            'monthly_moods' => $monthlyMoods,
            'distribution' => $distribution,
            'average_sleep' => round($averageSleep, 1),
            'dominant_mood' => $dominantMood,
            'stress_trend' => $stressTrend,
        ];
    }
}
