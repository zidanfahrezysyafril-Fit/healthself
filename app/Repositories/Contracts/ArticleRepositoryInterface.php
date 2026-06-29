<?php

namespace App\Repositories\Contracts;

interface ArticleRepositoryInterface
{
    public function getPaginated(int $perPage = 10, ?string $search = null, ?string $category = null);
    public function getBySlug(string $slug);
    public function getPopular(int $limit = 5);
    public function getRecommended(int $limit = 5);
    public function getRelatedByCategory(int $categoryId, int $excludeArticleId, int $limit = 3);
    public function findById(int $id);
}
