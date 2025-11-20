#!/usr/bin/env sh
set -e

echo "[entrypoint] Ensuring storage and cache permissions"
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

if [ ! -f /var/www/html/.env ]; then
  echo "[entrypoint] Generating .env from environment variables"
  cp /var/www/html/.env.example /var/www/html/.env
fi

if grep -q "APP_KEY=" /var/www/html/.env && grep -q "APP_KEY=" /var/www/html/.env.example; then
  if [ -z "${APP_KEY}" ]; then
    echo "[entrypoint] Generating APP_KEY"
    php artisan key:generate --force
  fi
fi

echo "[entrypoint] Running artisan optimize tasks"
php artisan config:cache
php artisan route:cache || true
php artisan view:cache

if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "[entrypoint] Running migrations"
  php artisan migrate --force
fi

if [ "${RUN_SEED}" = "true" ]; then
  echo "[entrypoint] Seeding database"
  php artisan db:seed --force || true
fi

echo "[entrypoint] Starting PHP-FPM and Nginx"
php-fpm -D
nginx -g 'daemon off;'
