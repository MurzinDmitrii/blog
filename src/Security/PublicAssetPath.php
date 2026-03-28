<?php

declare(strict_types=1);

namespace App\Security;

final class PublicAssetPath
{
    public static function sanitize(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $path = trim($path);
        if ($path === '' || str_contains($path, "\0")) {
            return null;
        }

        if (str_contains($path, '..') || str_contains($path, '\\')) {
            return null;
        }

        if (preg_match('#^[a-zA-Z][a-zA-Z0-9+.-]*:#', $path) === 1) {
            return null;
        }

        if (str_starts_with($path, '//')) {
            return null;
        }

        $path = ltrim($path, '/');
        if ($path === '') {
            return null;
        }

        if (preg_match('#^(assets|uploads)/[a-zA-Z0-9/_\-.]+$#', $path) !== 1) {
            return null;
        }

        return $path;
    }
    
    public static function modifier(mixed $path): string
    {
        $s = self::sanitize(is_string($path) ? $path : null);

        return $s ?? '';
    }
}
