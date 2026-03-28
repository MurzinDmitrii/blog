<?php

declare(strict_types=1);

use App\Config;
use App\Database;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$debug = filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN);
if (! $debug) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

$config = Config::fromEnv();
Database::pdo($config);

return $config;
