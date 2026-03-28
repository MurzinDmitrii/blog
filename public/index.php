<?php

declare(strict_types=1);

use App\Application\ArticleReadService;
use App\Application\HomePageService;
use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Database;
use App\Http\RequestExceptionHandler;
use App\Presentation\ArticleBodyFormatter;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Router;
use App\Security\SecurityHeaders;
use App\SmartyFactory;
use Throwable;

$root = dirname(__DIR__);

$config = require $root . '/src/bootstrap.php';

SecurityHeaders::send();

$pdo = Database::pdo($config);
$smarty = SmartyFactory::create($root);
$smarty->assign('current_year', date('Y'));

$categoryRepository = new CategoryRepository($pdo);
$articleRepository = new ArticleRepository($pdo);

$homePageService = new HomePageService($categoryRepository, $articleRepository);
$articleReadService = new ArticleReadService($articleRepository);
$bodyFormatter = new ArticleBodyFormatter();

$homeController = new HomeController($homePageService, $smarty, $config->baseUrl);
$categoryController = new CategoryController(
    $categoryRepository,
    $articleRepository,
    $smarty,
    $config->baseUrl,
);
$articleController = new ArticleController(
    $articleReadService,
    $bodyFormatter,
    $smarty,
    $config->baseUrl,
);

$router = new Router($config, $homeController, $categoryController, $articleController);

$debug = filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN);
$exceptionHandler = new RequestExceptionHandler($smarty, $config->baseUrl, $debug);

try {
    $router->dispatch();
} catch (Throwable $e) {
    $exceptionHandler->handle($e);
}
