<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'google_id', 'avatar',
        'role', 'nim_nip', 'prodi', 'phone', 'email_verified_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // --- Helpers ---
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isKonselor(): bool { return $this->role === 'konselor'; }
    public function isMahasiswa(): bool{ return $this->role === 'mahasiswa'; }

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=800000&color=fff&size=128';
    }

    // --- Relasi ---
    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'id_user');
    }

    public function artikelDivalidasi()
    {
        return $this->hasMany(Artikel::class, 'id_konselor');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_user');
    }

    public function riwayatChat()
    {
        return $this->hasMany(RiwayatChat::class, 'id_user');
    }

    public function konselorComments()
    {
        return $this->hasMany(KonselorComment::class, 'id_user');
    }

    public function konselorCommentsDibuat()
    {
        return $this->hasMany(KonselorComment::class, 'id_konselor');
    }
}
