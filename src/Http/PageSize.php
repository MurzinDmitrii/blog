<?php

declare(strict_types=1);

namespace App\Http;

final readonly class PageSize
{
    /** @var list<int> */
    public const ALLOWED = [5, 10, 15, 25];

    public const DEFAULT = 5;

    private function __construct(public int $value) {}

    public static function fromQueryParam(mixed $raw): self
    {
        if ($raw === null || is_array($raw)) {
            return new self(self::DEFAULT);
        }

        if (is_string($raw)) {
            $raw = trim($raw);
        }

        if ($raw === '' || ! is_numeric($raw)) {
            return new self(self::DEFAULT);
        }

        $n = (int) $raw;

        return in_array($n, self::ALLOWED, true)
            ? new self($n)
            : new self(self::DEFAULT);
    }
}
