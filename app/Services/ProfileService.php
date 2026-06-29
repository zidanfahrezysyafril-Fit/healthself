<?php

namespace App\Services;

use App\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function updateProfile(int $userId, array $data)
    {
        return $this->profileRepository->updateProfile($userId, $data);
    }

    public function updateAvatar(int $userId, string $avatarId)
    {
        // Avatar ID adalah string bebas yang disediakan oleh frontend (e.g. 'avatar1', 'avatar2')
        return $this->profileRepository->updateAvatar($userId, $avatarId);
    }

    public function deleteAvatar(int $userId)
    {
        return $this->profileRepository->updateAvatar($userId, null);
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword, string $currentHashedPassword)
    {
        if (!Hash::check($oldPassword, $currentHashedPassword)) {
            throw new \Exception('Password lama tidak sesuai.');
        }

        return $this->profileRepository->updatePassword($userId, Hash::make($newPassword));
    }
}
