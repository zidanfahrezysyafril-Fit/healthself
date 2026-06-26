<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';
    protected $fillable = ['identifier', 'attempts', 'locked_until'];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function secondsRemaining(): int
    {
        if ($this->isLocked()) {
            return (int) now()->diffInSeconds($this->locked_until);
        }
        return 0;
    }
}
