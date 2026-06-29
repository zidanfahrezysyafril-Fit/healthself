<?php

namespace App\Repositories\Contracts;

interface ProfileRepositoryInterface
{
    public function updateProfile(int $userId, array $data);
    public function updateAvatar(int $userId, ?string $avatarId);
    public function updatePassword(int $userId, string $hashedPassword);
}
