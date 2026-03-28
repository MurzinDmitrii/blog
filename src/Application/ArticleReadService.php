<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\ArticlePageDto;
use App\Domain\Category;
use App\Exception\NotFoundException;
use App\Repositories\ArticleRepositoryInterface;

final class ArticleReadService
{
    public function __construct(private readonly ArticleRepositoryInterface $articles) {}

    /** @throws NotFoundException */
    public function getArticlePageForRead(int $id): ArticlePageDto
    {
        $detail = $this->articles->findDetailById($id);
        if ($detail === null) {
            throw new NotFoundException('Статья не найдена');
        }

        $this->articles->incrementViews($id);
        $detail = $detail->withIncrementedViews();

        $categoryRows = $this->articles->findCategoriesForArticle($id);
        $categories = array_map(
            static fn(array $row): Category => Category::fromRow($row),
            $categoryRows,
        );

        $similar = $this->articles->findSimilar($id, 3);

        return new ArticlePageDto($detail, $categories, $similar);
    }
}
