# Блог (PHP, Smarty, MySQL)

Без фреймворков: PDO, миграции SQL, сиды из `database/seeds/seed.json`.

## Запуск

```bash
make
```

Сайт: [http://localhost:8080](http://localhost:8080)

- MySQL с хоста: `localhost:3307`, БД `blog`, пользователь `blog`, пароль `blog`
- Контейнеры: `blog-web`, `blog-db` (проект Compose: `blog`)

Вручную: `docker compose up -d --build`, затем `docker compose exec web php bin/migrate.php` и `php bin/seed.php`. Команды: `make help`.

## Composer

Том проекта перекрывает `vendor` из образа. В Docker **`make` / `make setup`** выполняют `composer install --no-dev` (достаточно для приложения). Полный dev-стек в контейнере: `make install-dev` (много RAM); проверки на хосте: `make check` (нужны PHP и Composer).

Если `vendor` повреждён: удалите каталог `vendor`, снова `make setup`.

## Стили

```bash
npm install && npm run build:css
```

Выход: `public/assets/css/main.css`.

## Проверки кода (dev-зависимости)

```bash
composer install
composer check
```

В Docker после `make install-dev`: `make check-docker`.

## Переменные окружения

`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_CHARSET` — для MySQL. Опционально `BASE_URL` (префикс пути, например `/blog`). `APP_DEBUG=1` — вывод ошибок PHP в ответ.

## Структура каталогов

- `public/` — входная точка, статика
- `src/` — домен, Application, HTTP, репозитории, контроллеры
- `templates/` — Smarty
- `migrations/` — SQL, учёт в `schema_migrations`
- `database/seeds/seed.json` — демо-данные для `bin/seed.php`

Подробнее: [ARCHITECTURE.md](ARCHITECTURE.md).
