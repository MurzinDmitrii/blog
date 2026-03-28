<?php

declare(strict_types=1);

namespace App\Security;

final class SecurityHeaders
{
    public static function send(): void
    {
        if (headers_sent()) {
            return;
        }

        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: interest-cohort=()');

        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "img-src 'self' data:",
            "style-src 'self' https://fonts.googleapis.com",
            "font-src https://fonts.gstatic.com",
            "object-src 'none'",
            "frame-ancestors 'self'",
        ]);
        header('Content-Security-Policy: ' . $csp);
    }
}
