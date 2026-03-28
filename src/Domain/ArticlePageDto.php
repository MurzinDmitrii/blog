<?php

declare(strict_types=1);

namespace App\Domain;

final readonly class ArticlePageDto
{
    /**
     * @param list<Category>       $categories
     * @param list<ArticleSummary> $similar
     */
    public function __construct(
        public ArticleDetail $article,
        /** @var list<Category> */
        public array $categories,
        /** @var list<ArticleSummary> */
        public array $similar,
    ) {}
}
