FROM composer:2 AS composer-build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts


FROM node:22-alpine AS node-build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install --ignore-scripts

COPY . .
RUN npm run build


FROM php:8.4-fpm-alpine AS production

RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql gd zip pcntl opcache

WORKDIR /var/www/html

COPY . .
COPY --from=composer-build /app/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
