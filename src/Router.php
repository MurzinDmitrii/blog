<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Exception\NotFoundException;
use App\Http\CategoryListingQuery;
use App\Security\RequestPath;

final class Router
{
    public function __construct(
        private readonly Config $config,
        private readonly HomeController $home,
        private readonly CategoryController $category,
        private readonly ArticleController $article,
    ) {}

    public function dispatch(): void
    {
        $path = RequestPath::fromServer();

        $base = $this->config->baseUrl;
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
            $path = $path === '' ? '/' : $path;
        }

        if ($path === '/' || $path === '') {
            $this->home->index();

            return;
        }

        if (preg_match('#^/category/(\d+)$#', $path, $m)) {
            $query = CategoryListingQuery::fromQueryParams($_GET);
            $this->category->show((int) $m[1], $query);

            return;
        }

        if (preg_match('#^/article/(\d+)$#', $path, $m)) {
            $this->article->show((int) $m[1]);

            return;
        }

        throw new NotFoundException('Страница не найдена');
    }
}
