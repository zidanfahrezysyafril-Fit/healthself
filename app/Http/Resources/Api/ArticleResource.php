<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth()->id() ?? 1;
        $isBookmarked = false;

        // Jika relasi bookmarkedByUsers sudah di-load, kita bisa cek dari situ tanpa query baru
        if ($this->relationLoaded('bookmarkedByUsers')) {
            $isBookmarked = $this->bookmarkedByUsers->contains('id', $userId);
        } else {
            // Fallback (sebaiknya gunakan with() di repository)
            $isBookmarked = \DB::table('article_user_bookmarks')
                ->where('user_id', $userId)
                ->where('artikel_id', $this->id)
                ->exists();
        }

        return [
            'id' => (string) $this->id,
            'title' => $this->judul,
            'category' => $this->kategori->nama_kategori ?? 'Kesehatan',
            'image_url' => $this->thumbnailUrl(),
            'author' => $this->pembuat->name ?? 'Admin',
            'date' => $this->tanggal_publish ? $this->tanggal_publish->format('d M Y') : $this->created_at->format('d M Y'),
            'content' => $this->isi_konten,
            'slug' => $this->slug,
            'is_bookmarked' => $isBookmarked,
        ];
    }
}
