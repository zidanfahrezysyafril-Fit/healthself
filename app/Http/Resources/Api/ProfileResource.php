<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->avatarUrl(),
                'role' => $this->role,
            ],
            'statistics' => [
                'total_moods' => $this->moods()->count(),
                'total_chats' => $this->riwayatChat()->count(),
                'total_articles_saved' => $this->bookmarkedArticles()->count(), // Menggunakan saved articles sbg pengganti read
                'joined_days' => $this->created_at ? $this->created_at->diffInDays(now()) : 0,
            ]
        ];
    }
}
