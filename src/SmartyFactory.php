<?php

declare(strict_types=1);

namespace App;

use App\Security\PublicAssetPath;
use Smarty;

final class SmartyFactory
{
    public static function create(string $projectRoot): Smarty
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($projectRoot . '/templates');
        $smarty->setCompileDir($projectRoot . '/templates_c');
        $smarty->setCacheDir($projectRoot . '/templates_c/cache');
        $smarty->caching = \Smarty::CACHING_OFF;
        $smarty->escape_html = true;
        $smarty->debugging = false;
        $smarty->registerPlugin('modifier', 'safe_asset', [PublicAssetPath::class, 'modifier']);

        return $smarty;
    }
}
