FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# The runtime stage installs the extension; Composer's builder image intentionally does not.
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-req=ext-mongodb

FROM php:8.3-fpm-bookworm
RUN apt-get update \
    && apt-get install --yes --no-install-recommends $PHPIZE_DEPS ca-certificates curl libicu-dev libonig-dev libssl-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring intl zip opcache \
    && curl --fail --location --retry 5 --retry-all-errors --output /tmp/mongodb.tgz https://pecl.php.net/get/mongodb-2.5.5.tgz \
    && pecl install --configureoptions 'with-mongodb-ssl="openssl"' /tmp/mongodb.tgz \
    && docker-php-ext-enable mongodb
RUN curl --fail --location --retry 5 --retry-all-errors --output /tmp/redis.tgz https://pecl.php.net/get/redis-6.3.0.tgz \
    && pecl install /tmp/redis.tgz \
    && docker-php-ext-enable redis \
    && php -m | grep -qx mongodb \
    && php -m | grep -qx redis \
    && rm -f /tmp/mongodb.tgz /tmp/redis.tgz \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www
COPY --from=vendor /app/vendor ./vendor
COPY . .
RUN mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && php artisan package:discover --ansi --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache
USER www-data
EXPOSE 9000
CMD ["php-fpm", "-F"]
