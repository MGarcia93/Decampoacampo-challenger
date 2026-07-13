FROM php:8.5-fpm-alpine

WORKDIR /var/www/backend

RUN docker-php-ext-install \
    pdo \
    pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock* ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .

CMD ["php-fpm"]