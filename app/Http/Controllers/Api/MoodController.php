<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreMoodRequest;
use App\Http\Requests\Api\UpdateMoodRequest;
use App\Http\Resources\Api\MoodResource;
use App\Services\MoodService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Mood;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MoodController extends Controller
{
    use AuthorizesRequests;

    protected $moodService;

    public function __construct(MoodService $moodService)
    {
        $this->moodService = $moodService;
    }

    public function index()
    {
        try {
            $userId = auth()->id();
            $moods = $this->moodService->getUserMoods($userId);
            
            return ApiResponse::success(MoodResource::collection($moods), 'Berhasil mengambil data mood.');
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data mood: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil data mood.', 500);
        }
    }

    public function store(StoreMoodRequest $request)
    {
        try {
            $userId = auth()->id();
            
            $mood = $this->moodService->createMood($userId, $request->validated());

            return ApiResponse::success(new MoodResource($mood), 'Mood berhasil disimpan.', 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan mood: ' . $e->getMessage());
            return ApiResponse::error('Gagal menyimpan mood.', 500);
        }
    }

    public function update(UpdateMoodRequest $request, $id)
    {
        try {
            $userId = auth()->id();
            
            // Note: Authorization can also be handled by Policy if injected as Model
            // Here Service ensures it's updated by the right user or throws NotFound
            $mood = $this->moodService->updateMood($id, $userId, $request->validated());

            return ApiResponse::success(new MoodResource($mood), 'Mood berhasil diperbarui.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error('Mood tidak ditemukan atau bukan milik Anda.', 404);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui mood: ' . $e->getMessage());
            return ApiResponse::error('Gagal memperbarui mood.', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $userId = auth()->id();
            
            $this->moodService->deleteMood($id, $userId);

            return ApiResponse::success(null, 'Mood berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error('Mood tidak ditemukan atau bukan milik Anda.', 404);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus mood: ' . $e->getMessage());
            return ApiResponse::error('Gagal menghapus mood.', 500);
        }
    }

    public function statistics()
    {
        try {
            $userId = auth()->id();
            $statistics = $this->moodService->getMoodStatistics($userId);
            
            return ApiResponse::success($statistics, 'Berhasil mengambil statistik mood.');
        } catch (\Exception $e) {
            Log::error('Gagal menghitung statistik mood: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil statistik mood.', 500);
        }
    }
}
