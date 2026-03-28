<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\ArticleDetail;
use App\Domain\ArticleSummary;
use App\Domain\PaginatedArticleSummaries;
use App\Domain\SortOrder;
use App\Http\PageNumber;

interface ArticleRepositoryInterface
{
    public function findDetailById(int $id): ?ArticleDetail;

    public function incrementViews(int $id): void;

    /**
     * @return list<ArticleSummary>
     */
    public function findLatestForCategory(int $categoryId, int $limit): array;

    public function findByCategoryPaginated(
        int $categoryId,
        SortOrder $sort,
        PageNumber $page,
        int $perPage,
    ): PaginatedArticleSummaries;

    /**
     * @return list<array{id:int,name:string}>
     */
    public function findCategoriesForArticle(int $articleId): array;

    /**
     * @return list<ArticleSummary>
     */
    public function findSimilar(int $articleId, int $limit): array;
}
