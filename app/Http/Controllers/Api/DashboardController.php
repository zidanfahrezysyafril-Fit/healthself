<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        try {
            $userId = auth()->id() ?? 1; // Fallback for dev without auth

            $data = $this->dashboardService->getDashboardData($userId);

            return ApiResponse::success($data, 'Berhasil memuat data dashboard.');
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data dashboard: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return ApiResponse::error('Gagal mengambil data dashboard.', 500);
        }
    }
}
