<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Security\RequestPath;
use PHPUnit\Framework\TestCase;

final class RequestPathTest extends TestCase
{
    public function testNormalizesCategoryPath(): void
    {
        self::assertSame(
            '/category/1',
            RequestPath::fromRequestUri('/category/1?sort=views'),
        );
    }

    public function testRejectsParentSegments(): void
    {
        self::assertSame(
            '/',
            RequestPath::fromRequestUri('/foo/../bar'),
        );
    }

    public function testRootPath(): void
    {
        self::assertSame('/', RequestPath::fromRequestUri('/'));
    }
}
