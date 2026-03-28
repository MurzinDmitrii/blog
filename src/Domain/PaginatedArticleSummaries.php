<?php

declare(strict_types=1);

namespace App\Domain;

final readonly class PaginatedArticleSummaries
{
    /**
     * @param list<ArticleSummary> $items
     */
    public function __construct(
        public array $items,
        public int $total,
    ) {}
}
