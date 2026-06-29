<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use App\Http\Resources\Api\ArticleResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'category', 'per_page']);
            $articles = $this->articleService->getArticles($filters);
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil daftar artikel.',
                'data' => ArticleResource::collection($articles),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching articles: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil artikel.', 500);
        }
    }

    public function show($slug)
    {
        try {
            $data = $this->articleService->getArticleDetail($slug);
            
            return ApiResponse::success([
                'article' => new ArticleResource($data['article']),
                'related_articles' => ArticleResource::collection($data['related'])
            ], 'Berhasil mengambil detail artikel.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        } catch (\Exception $e) {
            Log::error('Error fetching article detail: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil detail artikel.', 500);
        }
    }

    public function popular()
    {
        try {
            $articles = $this->articleService->getPopularArticles();
            return ApiResponse::success(ArticleResource::collection($articles), 'Berhasil mengambil artikel populer.');
        } catch (\Exception $e) {
            Log::error('Error fetching popular articles: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil artikel populer.', 500);
        }
    }

    public function recommended()
    {
        try {
            $articles = $this->articleService->getRecommendedArticles();
            return ApiResponse::success(ArticleResource::collection($articles), 'Berhasil mengambil artikel rekomendasi.');
        } catch (\Exception $e) {
            Log::error('Error fetching recommended articles: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil artikel rekomendasi.', 500);
        }
    }

    public function getByCategory($slug)
    {
        try {
            // Kita bisa menggunakan method index dengan filter category
            $filters = ['category' => $slug, 'per_page' => 10];
            $articles = $this->articleService->getArticles($filters);
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil artikel berdasarkan kategori.',
                'data' => ArticleResource::collection($articles),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'total' => $articles->total(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching articles by category: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengambil artikel berdasarkan kategori.', 500);
        }
    }

    public function toggleBookmark($id)
    {
        try {
            $userId = auth()->id() ?? 1; // Fallback for dev
            
            $isBookmarked = $this->articleService->toggleBookmark($userId, $id);
            
            $message = $isBookmarked ? 'Artikel berhasil disimpan ke bookmark.' : 'Artikel dihapus dari bookmark.';
            
            return ApiResponse::success(['is_bookmarked' => $isBookmarked], $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        } catch (\Exception $e) {
            Log::error('Error toggling bookmark: ' . $e->getMessage());
            return ApiResponse::error('Gagal mengubah status bookmark.', 500);
        }
    }
}
