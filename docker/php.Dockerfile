FROM php:8.3-cli

# --- System deps
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libicu-dev \
 && docker-php-ext-install pdo pdo_pgsql intl zip \
 && rm -rf /var/lib/apt/lists/*

# --- Opcache (for perf)
RUN docker-php-ext-install opcache
COPY docker/php.ini /usr/local/etc/php/php.ini

# --- Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app
