<?php

declare(strict_types=1);

namespace App\Security;

final class RequestPath
{
    private const MAX_LEN = 2048;

    public static function fromServer(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        return self::fromRequestUri(is_string($uri) ? $uri : '/');
    }

    public static function fromRequestUri(string $requestUri): string
    {
        $path = parse_url($requestUri, PHP_URL_PATH);

        if (! is_string($path) || $path === '') {
            return '/';
        }

        if (strlen($path) > self::MAX_LEN) {
            return '/';
        }

        if (str_contains($path, "\0")) {
            return '/';
        }

        if (str_contains($path, '..')) {
            return '/';
        }

        $path = rtrim($path, '/');
        if ($path === '') {
            return '/';
        }

        if (! str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return $path;
    }
}
