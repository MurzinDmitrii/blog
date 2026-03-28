<?php

declare(strict_types=1);

namespace App\Http;

use App\Exception\NotFoundException;
use Smarty;
use Throwable;

final class RequestExceptionHandler
{
    public function __construct(
        private readonly Smarty $smarty,
        private readonly string $baseUrl,
        private readonly bool $debug,
    ) {}

    public function handle(Throwable $e): void
    {
        if ($e instanceof NotFoundException) {
            http_response_code(404);
            $this->smarty->assign('page_title', 'Не найдено');
            $this->smarty->assign('base_url', $this->baseUrl);
            $this->smarty->display('pages/404.tpl');

            return;
        }

        error_log($e->getMessage() . "\n" . $e->getTraceAsString());

        http_response_code(500);
        if ($this->debug) {
            throw $e;
        }

        $this->smarty->assign('page_title', 'Ошибка');
        $this->smarty->assign('base_url', $this->baseUrl);
        $this->smarty->display('pages/500.tpl');
    }
}
