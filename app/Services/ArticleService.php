<?php

namespace App\Services;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticles(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $search = $filters['search'] ?? null;
        $category = $filters['category'] ?? null;

        return $this->articleRepository->getPaginated($perPage, $search, $category);
    }

    public function getArticleDetail(string $slug)
    {
        $article = $this->articleRepository->getBySlug($slug);
        $related = $this->articleRepository->getRelatedByCategory($article->id_kategori, $article->id, 3);
        
        return [
            'article' => $article,
            'related' => $related
        ];
    }

    public function getPopularArticles()
    {
        return $this->articleRepository->getPopular(5);
    }

    public function getRecommendedArticles()
    {
        return $this->articleRepository->getRecommended(5);
    }

    public function toggleBookmark(int $userId, int $articleId)
    {
        $user = User::findOrFail($userId);
        $article = $this->articleRepository->findById($articleId);

        // Jika sudah dibookmark, maka detach (hapus). Jika belum, attach (tambah).
        $isBookmarked = $user->bookmarkedArticles()->where('artikel_id', $articleId)->exists();
        
        if ($isBookmarked) {
            $user->bookmarkedArticles()->detach($articleId);
            return false; // Bookmarked removed
        } else {
            $user->bookmarkedArticles()->attach($articleId);
            return true; // Bookmarked added
        }
    }
}
