<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function createUser(array $data): User;
    public function recordLoginHistory(int $userId, string $ipAddress, string $userAgent): void;
}
