<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Application\ArticleReadService;
use App\Domain\ArticleSummary;
use App\Domain\Category;
use App\Presentation\ArticleBodyFormatter;
use Smarty;

final class ArticleController
{
    public function __construct(
        private readonly ArticleReadService $articleRead,
        private readonly ArticleBodyFormatter $bodyFormatter,
        private readonly Smarty $smarty,
        private readonly string $baseUrl,
    ) {}

    public function show(int $id): void
    {
        $page = $this->articleRead->getArticlePageForRead($id);
        $bodyFormatted = $this->bodyFormatter->format($page->article->body);

        $this->smarty->assign('page_title', $page->article->title);
        $this->smarty->assign('article', $page->article->toTemplateArray($bodyFormatted));
        $this->smarty->assign(
            'categories',
            array_map(
                static fn(Category $c): array => $c->toTemplateArray(),
                $page->categories,
            ),
        );
        $this->smarty->assign(
            'similar',
            array_map(
                static fn(ArticleSummary $s): array => $s->toTemplateArray(),
                $page->similar,
            ),
        );
        $this->smarty->assign('base_url', $this->baseUrl);
        $this->smarty->display('pages/article.tpl');
    }
}
