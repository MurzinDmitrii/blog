<?php

declare(strict_types=1);

namespace App;

final class Config
{
    public function __construct(
        public readonly string $dbHost,
        public readonly int $dbPort,
        public readonly string $dbName,
        public readonly string $dbUser,
        public readonly string $dbPassword,
        public readonly string $dbCharset,
        public readonly string $baseUrl,
    ) {}

    public static function fromEnv(): self
    {
        $port = (int) (getenv('DB_PORT') ?: '3306');

        $baseUrl = rtrim((string) (getenv('BASE_URL') ?: ''), '/');
        if ($baseUrl !== '' && ! preg_match('#^/[a-zA-Z0-9/_\-]*$#', $baseUrl)) {
            $baseUrl = '';
        }

        return new self(
            dbHost: getenv('DB_HOST') ?: '127.0.0.1',
            dbPort: $port > 0 ? $port : 3306,
            dbName: getenv('DB_NAME') ?: 'blog',
            dbUser: getenv('DB_USER') ?: 'blog',
            dbPassword: getenv('DB_PASSWORD') ?: '',
            dbCharset: getenv('DB_CHARSET') ?: 'utf8mb4',
            baseUrl: $baseUrl,
        );
    }
}
