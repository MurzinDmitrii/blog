#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Config;
use App\Database;

$root = dirname(__DIR__);
require_once $root . '/vendor/autoload.php';

$config = Config::fromEnv();
$pdo = Database::pdo($config);

$seedPath = $root . '/database/seeds/seed.json';
if (! is_readable($seedPath)) {
    fwrite(STDERR, "Файл сидов не найден: {$seedPath}\n");
    exit(1);
}

$json = file_get_contents($seedPath);
if ($json === false) {
    fwrite(STDERR, "Не удалось прочитать {$seedPath}\n");
    exit(1);
}

/** @var array{categories: list<array{name:string,description:string}>, articles: list<array<string,mixed>>} $data */
$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE article_categories');
$pdo->exec('TRUNCATE TABLE articles');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$catStmt = $pdo->prepare(
    'INSERT INTO categories (name, description) VALUES (?, ?)',
);
foreach ($data['categories'] as $c) {
    $catStmt->execute([$c['name'], $c['description']]);
}

$catIds = $pdo->query('SELECT id FROM categories ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
$catIds = array_map('intval', $catIds);

$artStmt = $pdo->prepare(
    <<<'SQL'
        INSERT INTO articles (title, description, body, image_path, view_count, published_at)
        VALUES (?, ?, ?, ?, ?, ?)
        SQL,
);

$linkStmt = $pdo->prepare(
    'INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)',
);

foreach ($data['articles'] as $row) {
    $artStmt->execute([
        $row['title'],
        $row['description'],
        $row['body'],
        $row['image_path'],
        (int) $row['view_count'],
        $row['published_at'],
    ]);
    $articleId = (int) $pdo->lastInsertId();
    foreach ($row['category_indexes'] as $idx) {
        $idx = (int) $idx;
        $cid = $catIds[$idx] ?? null;
        if ($cid !== null) {
            $linkStmt->execute([$articleId, $cid]);
        }
    }
}

$nCat = count($data['categories']);
$nArt = count($data['articles']);
echo "Сиды применены из database/seeds/seed.json: {$nArt} статей, {$nCat} категорий.\n";
