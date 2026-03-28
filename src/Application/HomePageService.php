<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\HomeCategoryBlock;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

final class HomePageService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ArticleRepositoryInterface $articles,
    ) {}

    /**
     * @return list<HomeCategoryBlock>
     */
    public function buildHomeBlocks(): array
    {
        $list = $this->categories->findAllThatHaveArticles();
        $blocks = [];
        foreach ($list as $cat) {
            $summaries = $this->articles->findLatestForCategory($cat->id, 3);
            $blocks[] = new HomeCategoryBlock($cat, $summaries);
        }

        return $blocks;
    }
}
