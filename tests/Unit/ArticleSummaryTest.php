<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\ArticleSummary;
use PHPUnit\Framework\TestCase;

final class ArticleSummaryTest extends TestCase
{
    public function testFromRowAndDisplayDate(): void
    {
        $a = ArticleSummary::fromRow([
            'id' => '1',
            'title' => 'T',
            'description' => null,
            'image_path' => 'assets/x.svg',
            'view_count' => '5',
            'published_at' => '2026-03-15 12:00:00',
        ]);

        self::assertSame(1, $a->id);
        self::assertStringContainsString('2026', $a->getDisplayDate());
    }
}
