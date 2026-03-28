<?php

declare(strict_types=1);

use App\Config;

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';

$config = Config::fromEnv();

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config->dbHost,
    $config->dbPort,
    $config->dbName,
    $config->dbCharset,
);

$pdo = new PDO($dsn, $config->dbUser, $config->dbPassword, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
]);

$pdo->exec(
    <<<'SQL'
    CREATE TABLE IF NOT EXISTS schema_migrations (
        version VARCHAR(255) NOT NULL,
        applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (version)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    SQL,
);

$migrationsDir = $root . '/migrations';
$files = glob($migrationsDir . '/*.sql') ?: [];
sort($files);

foreach ($files as $file) {
    $version = basename($file);
    $stmt = $pdo->prepare('SELECT 1 FROM schema_migrations WHERE version = ?');
    $stmt->execute([$version]);
    if ($stmt->fetchColumn()) {
        echo "Skip {$version}\n";
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        fwrite(STDERR, "Cannot read {$file}\n");
        exit(1);
    }

    $pdo->exec($sql);
    $ins = $pdo->prepare('INSERT INTO schema_migrations (version) VALUES (?)');
    $ins->execute([$version]);
    echo "Applied {$version}\n";
}

echo "Done.\n";
