# syntax=docker/dockerfile:1

# ---------- Stage 1: Composer deps ----------
FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --ignore-platform-reqs

# ---------- Stage 2: Node assets ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY --from=composer /app ./
COPY . .
RUN npm run build

# ---------- Stage 3: PHP-FPM + nginx ----------
FROM php:8.5-fpm-alpine AS app
WORKDIR /var/www

RUN apk add --no-cache \
        nginx \
        supervisor \
        postgresql-client \
        postgresql-dev \
        libzip-dev \
        icu-dev \
        gettext \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_pgsql zip

COPY --from=assets /app /var/www/html
COPY docker/nginx.conf /etc/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Permissions for php-fpm + nginx
RUN rm -f bootstrap/cache/services.php bootstrap/cache/packages.php \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]