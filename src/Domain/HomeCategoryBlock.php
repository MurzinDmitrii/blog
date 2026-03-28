<?php

declare(strict_types=1);

namespace App\Domain;

final readonly class HomeCategoryBlock
{
    /**
     * @param list<ArticleSummary> $articles
     */
    public function __construct(
        public Category $category,
        /** @var list<ArticleSummary> */
        public array $articles,
    ) {}

    /**
     * @return array{category: array<string, mixed>, articles: list<array<string, mixed>>}
     */
    public function toTemplateArray(): array
    {
        $articles = [];
        foreach ($this->articles as $a) {
            $articles[] = $a->toTemplateArray();
        }

        return [
            'category' => $this->category->toTemplateArray(),
            'articles' => $articles,
        ];
    }
}
