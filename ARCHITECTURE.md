# Архитектура

## Стек

PHP 8.1+ (в Docker образе PHP 8.4 из‑за `composer.lock` dev-зависимостей), MySQL 8, Smarty 4, SCSS → CSS.

## Слои

| Слой | Содержимое |
|------|------------|
| HTTP | `Router`, `CategoryListingQuery`, `RequestExceptionHandler`, заголовки безопасности |
| Application | `ArticleReadService`, `HomePageService` |
| Domain | DTO: `Category`, `ArticleDetail`, `ArticleSummary`, enum `SortOrder` |
| Infrastructure | `ArticleRepository`, `CategoryRepository` (+ интерфейсы) |
| Presentation | `ArticleBodyFormatter` |

Сборка зависимостей в `public/index.php`, отдельного DI-контейнера нет.

## Каталоги

```
public/          index.php, .htaccess, assets/, uploads/
src/
  Application/
  Controllers/
  Domain/
  Exception/
  Http/
  Presentation/
  Repositories/
  Security/
migrations/
database/seeds/seed.json
bin/migrate.php
bin/seed.php
templates/
scss/
```

## Данные

- Связь статей и категорий: `article_categories` (M:N).
- Похожие статьи: общие категории, сортировка по числу совпадений и дате.
- Просмотры: при открытии статьи — `UPDATE … SET view_count = view_count + 1`.

## Маршруты

`/`, `/category/{id}`, `/category/{id}?sort=date|views&page=n&per_page=5|10|15|25`, `/article/{id}`.

## Безопасность

PDO с подготовленными запросами, `ATTR_EMULATE_PREPARES => false`. Путь запроса: `RequestPath`. `BASE_URL` — только допустимый префикс пути. Картинки: `PublicAssetPath`, модификатор Smarty `safe_asset`. Текст статьи: экранирование + `nl2br` до вывода. Заголовки: CSP (в т.ч. шрифты Google), `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`. Ошибки в ответе отключены, если не задан `APP_DEBUG=1`.

## Качество

`composer analyse` (PHPStan 8), `composer test`, `composer lint` / `lint:fix`. В Docker по умолчанию ставится только production Composer-зависимостей.
