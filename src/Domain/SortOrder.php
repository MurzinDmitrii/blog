<?php

declare(strict_types=1);

namespace App\Domain;

enum SortOrder: string
{
    case Date = 'date';
    case Views = 'views';
}
