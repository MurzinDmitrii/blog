<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\SortOrder;
use App\Http\CategoryListingQuery;
use App\Http\PageSize;
use PHPUnit\Framework\TestCase;

final class CategoryListingQueryTest extends TestCase
{
    public function testDefaults(): void
    {
        $q = CategoryListingQuery::fromQueryParams([]);
        self::assertSame(SortOrder::Date, $q->sort);
        self::assertSame(1, $q->page->value);
        self::assertSame(PageSize::DEFAULT, $q->perPage->value);
    }

    public function testSortViews(): void
    {
        $q = CategoryListingQuery::fromQueryParams(['sort' => 'views']);
        self::assertSame(SortOrder::Views, $q->sort);
    }

    public function testPageClamped(): void
    {
        $q = CategoryListingQuery::fromQueryParams(['page' => '999999'], 100);
        self::assertSame(100, $q->page->value);
    }

    public function testPerPageAllowed(): void
    {
        $q = CategoryListingQuery::fromQueryParams(['per_page' => '10']);
        self::assertSame(10, $q->perPage->value);
    }

    public function testPerPageInvalidFallsBackToDefault(): void
    {
        $q = CategoryListingQuery::fromQueryParams(['per_page' => '7']);
        self::assertSame(PageSize::DEFAULT, $q->perPage->value);
    }
}
