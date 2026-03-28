<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Security\PublicAssetPath;
use PHPUnit\Framework\TestCase;

final class PublicAssetPathTest extends TestCase
{
    public function testAllowsAssetsPath(): void
    {
        self::assertSame(
            'assets/img/placeholder.svg',
            PublicAssetPath::sanitize('assets/img/placeholder.svg'),
        );
    }

    public function testAllowsUploadsPath(): void
    {
        self::assertSame(
            'uploads/articles/x.png',
            PublicAssetPath::sanitize('uploads/articles/x.png'),
        );
    }

    public function testRejectsDirectoryTraversal(): void
    {
        self::assertNull(PublicAssetPath::sanitize('assets/../etc/passwd'));
    }

    public function testRejectsScheme(): void
    {
        self::assertNull(PublicAssetPath::sanitize('javascript:alert(1)'));
    }

    public function testModifierReturnsEmptyString(): void
    {
        self::assertSame('', PublicAssetPath::modifier('bad'));
    }
}
