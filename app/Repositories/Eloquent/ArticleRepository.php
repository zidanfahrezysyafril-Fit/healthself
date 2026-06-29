<?php

namespace App\Repositories\Eloquent;

use App\Models\Artikel;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ArticleRepository implements ArticleRepositoryInterface
{
    protected function baseQuery()
    {
        // Selalu load kategori, pembuat, dan data bookmark (jika ada yg login)
        return Artikel::with(['kategori', 'pembuat', 'bookmarkedByUsers' => function($query) {
            $query->where('user_id', auth()->id() ?? 1);
        }])->where('status', 'published');
    }

    public function getPaginated(int $perPage = 10, ?string $search = null, ?string $category = null)
    {
        $query = $this->baseQuery();

        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%');
        }

        if ($category && $category !== 'Semua') {
            $query->whereHas('kategori', function ($q) use ($category) {
                $q->where('nama_kategori', $category)
                  ->orWhere('slug', $category);
            });
        }

        return $query->latest('tanggal_publish')->paginate($perPage);
    }

    public function getBySlug(string $slug)
    {
        return $this->baseQuery()->where('slug', $slug)->firstOrFail();
    }

    public function getPopular(int $limit = 5)
    {
        return Cache::remember('articles.popular', now()->addHour(), function () use ($limit) {
            return $this->baseQuery()->inRandomOrder()->take($limit)->get();
        });
    }

    public function getRecommended(int $limit = 5)
    {
        return Cache::remember('articles.recommended', now()->addHour(), function () use ($limit) {
            return $this->baseQuery()->inRandomOrder()->take($limit)->get();
        });
    }

    public function getRelatedByCategory(int $categoryId, int $excludeArticleId, int $limit = 3)
    {
        return $this->baseQuery()
            ->where('id_kategori', $categoryId)
            ->where('id', '!=', $excludeArticleId)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    public function findById(int $id)
    {
        return Artikel::findOrFail($id);
    }
}
