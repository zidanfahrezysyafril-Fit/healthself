<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\LoginAttempt;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'mahasiswa', // Default for mobile apps
        ]);
    }

    public function recordLoginHistory(int $userId, string $ipAddress, string $userAgent): void
    {
        // Fitur riwayat login dinonaktifkan karena tabel login_attempts 
        // khusus untuk brute-force, bukan untuk history.
        // LoginAttempt::create([
        //     'user_id' => $userId,
        //     'ip_address' => $ipAddress,
        //     'user_agent' => $userAgent,
        //     'status' => 'success',
        //     'login_time' => now(),
        // ]);
    }
}
