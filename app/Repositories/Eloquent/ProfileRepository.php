<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function updateProfile(int $userId, array $data)
    {
        $user = User::findOrFail($userId);
        $user->update($data);
        return $user;
    }

    public function updateAvatar(int $userId, ?string $avatarId)
    {
        $user = User::findOrFail($userId);
        $user->update(['avatar' => $avatarId]);
        return $user;
    }

    public function updatePassword(int $userId, string $hashedPassword)
    {
        $user = User::findOrFail($userId);
        $user->update(['password' => $hashedPassword]);
        return $user;
    }
}
