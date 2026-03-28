<?php

declare(strict_types=1);

namespace App\Http;

use App\Domain\SortOrder;

final readonly class CategoryListingQuery
{
    public function __construct(
        public SortOrder $sort,
        public PageNumber $page,
        public PageSize $perPage,
    ) {}

    /** @param array<string, mixed> $query */
    public static function fromQueryParams(array $query, int $maxPage = 50_000): self
    {
        $sortRaw = $query['sort'] ?? 'date';
        $sort = is_string($sortRaw) && $sortRaw === SortOrder::Views->value
            ? SortOrder::Views
            : SortOrder::Date;

        $pageRaw = $query['page'] ?? 1;
        if (is_array($pageRaw) || is_bool($pageRaw)) {
            $pageInt = 1;
        } elseif (is_int($pageRaw)) {
            $pageInt = $pageRaw;
        } elseif (is_string($pageRaw) && is_numeric(trim($pageRaw))) {
            $pageInt = (int) $pageRaw;
        } elseif (is_float($pageRaw) && is_finite($pageRaw)) {
            $pageInt = (int) $pageRaw;
        } else {
            $pageInt = 1;
        }
        $perPage = PageSize::fromQueryParam($query['per_page'] ?? null);

        return new self($sort, PageNumber::fromPositive($pageInt, $maxPage), $perPage);
    }
}
