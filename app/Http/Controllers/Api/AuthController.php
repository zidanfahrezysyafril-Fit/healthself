<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\ProfileResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login(
                $request->only(['email', 'password']),
                $request->ip(),
                $request->userAgent() ?? 'Unknown',
                $request->throttleKey(),
                $request->boolean('remember_me')
            );

            return ApiResponse::success([
                'access_token' => $data['access_token'],
                'user' => new ProfileResource($data['user']),
            ], 'Login berhasil.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return ApiResponse::error($e->getMessage(), 401);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return ApiResponse::error('Terjadi kesalahan saat login.', 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $this->authService->register($request->validated());

            return ApiResponse::success([
                'access_token' => $data['access_token'],
                'user' => new ProfileResource($data['user']),
            ], 'Registrasi berhasil.', 201);
        } catch (\Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return ApiResponse::error('Terjadi kesalahan saat registrasi.', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());
            return ApiResponse::success(null, 'Berhasil logout dari perangkat ini.');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return ApiResponse::error('Terjadi kesalahan saat logout.', 500);
        }
    }

    public function logoutAll(Request $request)
    {
        try {
            $this->authService->logoutAll($request->user());
            return ApiResponse::success(null, 'Berhasil logout dari semua perangkat.');
        } catch (\Exception $e) {
            Log::error('LogoutAll error: ' . $e->getMessage());
            return ApiResponse::error('Terjadi kesalahan saat logout semua perangkat.', 500);
        }
    }
}
