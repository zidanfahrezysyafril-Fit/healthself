<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\UpdateAvatarRequest;
use App\Http\Resources\Api\ProfileResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            // Optional: fallback id 1 for testing if no auth (though user() is required)
            if (!$user) {
                $user = \App\Models\User::find(1);
            }

            return ApiResponse::success(new ProfileResource($user), 'Berhasil memuat profil.');
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data profil: ' . $e->getMessage());
            return ApiResponse::error('Gagal memuat profil.', 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user() ?? \App\Models\User::find(1);
            $this->profileService->updateProfile($user->id, $request->validated());

            // Reload user for response
            $user->refresh();
            return ApiResponse::success(new ProfileResource($user), 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui profil: ' . $e->getMessage());
            return ApiResponse::error('Gagal memperbarui profil.', 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = $request->user() ?? \App\Models\User::find(1);
            $this->profileService->changePassword(
                $user->id, 
                $request->old_password, 
                $request->new_password, 
                $user->password
            );

            return ApiResponse::success(null, 'Password berhasil diubah.');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Password lama tidak sesuai.') {
                return ApiResponse::error($e->getMessage(), 400);
            }
            Log::error('Gagal mengubah password: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengubah password.', 500);
        }
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        try {
            $user = $request->user() ?? \App\Models\User::find(1);
            $this->profileService->updateAvatar($user->id, $request->avatar_id);

            $user->refresh();
            return ApiResponse::success(new ProfileResource($user), 'Avatar berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui avatar: ' . $e->getMessage());
            return ApiResponse::error('Gagal memperbarui avatar.', 500);
        }
    }

    public function deleteAvatar(Request $request)
    {
        try {
            $user = $request->user() ?? \App\Models\User::find(1);
            $this->profileService->deleteAvatar($user->id);

            $user->refresh();
            return ApiResponse::success(new ProfileResource($user), 'Avatar berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus avatar: ' . $e->getMessage());
            return ApiResponse::error('Gagal menghapus avatar.', 500);
        }
    }
}
