<?php

declare(strict_types=1);

namespace App\Presentation;

final class ArticleBodyFormatter
{
    public function format(string $body): string
    {
        return nl2br(htmlspecialchars($body, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }
}
