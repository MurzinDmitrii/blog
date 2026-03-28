<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\ArticleDetail;
use App\Domain\ArticleSummary;
use App\Domain\PaginatedArticleSummaries;
use App\Domain\SortOrder;
use App\Http\PageNumber;
use PDO;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private readonly PDO $pdo) {}

    public function findDetailById(int $id): ?ArticleDetail
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT id, title, description, body, image_path, view_count, published_at
                FROM articles
                WHERE id = ?
                LIMIT 1
                SQL,
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? ArticleDetail::fromRow($row) : null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE articles SET view_count = view_count + 1 WHERE id = ?',
        );
        $stmt->execute([$id]);
    }

    public function findLatestForCategory(int $categoryId, int $limit): array
    {
        $limitRows = max(0, $limit);
        $sql = <<<SQL
            SELECT a.id, a.title, a.description, a.image_path, a.view_count, a.published_at
            FROM articles a
            INNER JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id = ?
            ORDER BY a.published_at DESC
            LIMIT {$limitRows}
            SQL;
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        /** @var list<ArticleSummary> */
        return array_values(array_map(
            static fn(array $row): ArticleSummary => ArticleSummary::fromRow($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC),
        ));
    }

    public function findByCategoryPaginated(
        int $categoryId,
        SortOrder $sort,
        PageNumber $page,
        int $perPage,
    ): PaginatedArticleSummaries {
        $order = match ($sort) {
            SortOrder::Views => 'a.view_count DESC, a.published_at DESC',
            SortOrder::Date => 'a.published_at DESC',
        };

        $offset = ($page->value - 1) * $perPage;
        $limitRows = max(1, $perPage);
        $offsetRows = max(0, $offset);

        $countStmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT COUNT(DISTINCT a.id)
                FROM articles a
                INNER JOIN article_categories ac ON ac.article_id = a.id
                WHERE ac.category_id = ?
                SQL,
        );
        $countStmt->execute([$categoryId]);
        $total = (int) $countStmt->fetchColumn();

        // LIMIT/OFFSET as literals: PDO MySQL native prepares mishandle bound LIMIT in some setups.
        $sql = <<<SQL
            SELECT a.id, a.title, a.description, a.image_path, a.view_count, a.published_at
            FROM articles a
            INNER JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id = ?
            ORDER BY {$order}
            LIMIT {$limitRows} OFFSET {$offsetRows}
            SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        /** @var list<ArticleSummary> $items */
        $items = array_values(array_map(
            static fn(array $row): ArticleSummary => ArticleSummary::fromRow($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC),
        ));

        return new PaginatedArticleSummaries($items, $total);
    }

    public function findCategoriesForArticle(int $articleId): array
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT c.id, c.name
                FROM categories c
                INNER JOIN article_categories ac ON ac.category_id = c.id
                WHERE ac.article_id = ?
                ORDER BY c.name ASC
                SQL,
        );
        $stmt->execute([$articleId]);

        /** @var list<array{id:int,name:string}> */
        return array_values($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findSimilar(int $articleId, int $limit): array
    {
        $limitRows = max(0, $limit);
        $stmt = $this->pdo->prepare(<<<SQL
            SELECT a.id, a.title, a.description, a.image_path, a.view_count, a.published_at,
                (
                    SELECT COUNT(*)
                    FROM article_categories ac1
                    INNER JOIN article_categories ac2
                        ON ac1.category_id = ac2.category_id
                        AND ac2.article_id = ?
                    WHERE ac1.article_id = a.id
                ) AS shared_cats
            FROM articles a
            WHERE a.id != ?
              AND EXISTS (
                  SELECT 1
                  FROM article_categories ac1
                  INNER JOIN article_categories ac2
                      ON ac1.category_id = ac2.category_id
                      AND ac2.article_id = ?
                  WHERE ac1.article_id = a.id
              )
            ORDER BY shared_cats DESC, a.published_at DESC
            LIMIT {$limitRows}
            SQL);
        $stmt->bindValue(1, $articleId, PDO::PARAM_INT);
        $stmt->bindValue(2, $articleId, PDO::PARAM_INT);
        $stmt->bindValue(3, $articleId, PDO::PARAM_INT);
        $stmt->execute();

        /** @var list<ArticleSummary> */
        return array_values(array_map(
            static fn(array $row): ArticleSummary => ArticleSummary::fromRow($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC),
        ));
    }
}
