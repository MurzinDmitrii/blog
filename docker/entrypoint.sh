#!/bin/sh
set -e
cd /var/www/html
mkdir -p vendor
if [ ! -f vendor/autoload.php ]; then
  composer install --no-dev --no-interaction --prefer-dist
fi
mkdir -p templates_c/cache public/uploads/articles public/assets/css
chown -R www-data:www-data templates_c public/uploads 2>/dev/null || true
exec apache2-foreground
