FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    libzip-dev \
    unzip \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --no-dev --no-interaction --prefer-dist

COPY . .

RUN mkdir -p templates_c public/uploads/articles public/assets/css \
    && chown -R www-data:www-data templates_c public/uploads

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
