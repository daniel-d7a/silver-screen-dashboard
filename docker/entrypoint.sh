#!/bin/sh
set -e

cd /var/www/html

export NGINX_PORT="${PORT:-80}"

envsubst '${NGINX_PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/http.d/default.conf

# Generate a fresh app key if not provided
if [ -z "${APP_KEY}" ]; then
    php artisan key:generate --force --no-interaction
fi

# Ensure storage/framework dirs exist for file session/cache
mkdir -p storage/framework/{sessions,views,cache}
chown -R www-data:www-data storage bootstrap/cache

exec "$@"