<?php

namespace App\Services;

use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(array $credentials, string $ipAddress, string $userAgent, string $throttleKey, bool $remember = false)
    {
        $user = $this->authRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Record failed attempt in Rate Limiter
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
            ]);
        }

        // Clear rate limit on successful login
        RateLimiter::clear($throttleKey);

        // Record Login History
        $this->authRepository->recordLoginHistory($user->id, $ipAddress, $userAgent);

        // Generate Token
        // Jika remember_me true, kita bisa menambahkan waktu expires.
        // Sanctum secara default stateless, tetapi kita bisa set waktu expires di config atau biarkan tanpa batas (tergantung config session/sanctum).
        // Kita akan menggunakan kemampuan dasar Sanctum (plainTextToken).
        
        $token = $user->createToken('auth_token', ['*'], $remember ? now()->addDays(30) : null)->plainTextToken;

        return [
            'access_token' => $token,
            'user' => clone $user, // Clone to avoid direct mutation issues
        ];
    }

    public function register(array $data)
    {
        $user = $this->authRepository->createUser($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'user' => $user,
        ];
    }

    public function logout(User $user)
    {
        // Revoke current token
        $user->currentAccessToken()->delete();
        return true;
    }

    public function logoutAll(User $user)
    {
        // Revoke all tokens
        $user->tokens()->delete();
        return true;
    }
}
