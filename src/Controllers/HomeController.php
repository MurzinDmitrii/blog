<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Application\HomePageService;
use App\Domain\HomeCategoryBlock;
use Smarty;

final class HomeController
{
    public function __construct(
        private readonly HomePageService $homePage,
        private readonly Smarty $smarty,
        private readonly string $baseUrl,
    ) {}

    public function index(): void
    {
        $blocks = array_map(
            static fn(HomeCategoryBlock $block): array => $block->toTemplateArray(),
            $this->homePage->buildHomeBlocks(),
        );

        $this->smarty->assign('page_title', 'Главная');
        $this->smarty->assign('blocks', $blocks);
        $this->smarty->assign('base_url', $this->baseUrl);
        $this->smarty->display('pages/home.tpl');
    }
}
