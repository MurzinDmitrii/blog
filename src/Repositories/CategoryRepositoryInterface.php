<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return list<Category>
     */
    public function findAllThatHaveArticles(): array;

    public function findById(int $id): ?Category;
}
