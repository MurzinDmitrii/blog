<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\ArticleSummary;
use App\Exception\NotFoundException;
use App\Http\CategoryListingQuery;
use App\Http\PageNumber;
use App\Http\PageSize;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use Smarty;

final class CategoryController
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ArticleRepositoryInterface $articles,
        private readonly Smarty $smarty,
        private readonly string $baseUrl,
    ) {}

    public function show(int $id, CategoryListingQuery $query): void
    {
        $category = $this->categories->findById($id);
        if ($category === null) {
            throw new NotFoundException('Категория не найдена');
        }

        $perPage = $query->perPage->value;

        $result = $this->articles->findByCategoryPaginated(
            $id,
            $query->sort,
            $query->page,
            $perPage,
        );

        $totalPages = max(1, (int) ceil($result->total / $perPage));
        $displayPage = $query->page->value;
        if ($result->total > 0 && $displayPage > $totalPages) {
            $displayPage = $totalPages;
            $result = $this->articles->findByCategoryPaginated(
                $id,
                $query->sort,
                PageNumber::fromPositive($displayPage),
                $perPage,
            );
        }

        $articles = array_map(
            static fn(ArticleSummary $a): array => $a->toTemplateArray(),
            $result->items,
        );

        $this->smarty->assign('page_title', $category->name);
        $this->smarty->assign('category', $category->toTemplateArray());
        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('sort', $query->sort->value);
        $this->smarty->assign('page', $displayPage);
        $this->smarty->assign('total_pages', $totalPages);
        $this->smarty->assign('total', $result->total);
        $this->smarty->assign('per_page', $perPage);
        $this->smarty->assign('per_page_options', PageSize::ALLOWED);
        $this->smarty->assign('base_url', $this->baseUrl);
        $this->smarty->display('pages/category.tpl');
    }
}
