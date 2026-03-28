<?php

declare(strict_types=1);

namespace App\Http;

final readonly class PageNumber
{
    private function __construct(public int $value) {}

    public static function fromPositive(int $raw, int $max = 50_000): self
    {
        return new self(min(max(1, $raw), $max));
    }
}
