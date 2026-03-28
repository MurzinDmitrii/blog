<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Category;
use PDO;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly PDO $pdo) {}

    public function findAllThatHaveArticles(): array
    {
        $sql = <<<'SQL'
            SELECT DISTINCT c.id, c.name, c.description
            FROM categories c
            INNER JOIN article_categories ac ON ac.category_id = c.id
            ORDER BY c.name ASC
            SQL;

        $result = $this->pdo->query($sql);
        if ($result === false) {
            return [];
        }

        /** @var list<Category> */
        return array_values(array_map(
            static fn(array $row): Category => Category::fromRow($row),
            $result->fetchAll(PDO::FETCH_ASSOC),
        ));
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description FROM categories WHERE id = ? LIMIT 1',
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? Category::fromRow($row) : null;
    }
}
