#!/usr/bin/env sh
set -e

echo "[entrypoint] Ensuring storage and cache permissions"
if [ "$(id -u)" = "0" ]; then
  chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
  chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true
else
  echo "[entrypoint] Non-root user; skipping chown"
fi

if [ ! -f /var/www/html/.env ]; then
  echo "[entrypoint] Generating .env from environment variables"
  if [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
  else
    cat > /var/www/html/.env <<'EOF'
APP_NAME=Laravel
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF
  fi
fi

if grep -q "APP_KEY=" /var/www/html/.env; then
  if [ -z "${APP_KEY}" ]; then
    if ! grep -q "APP_KEY=base64" /var/www/html/.env; then
      echo "[entrypoint] Generating APP_KEY"
      php artisan key:generate --force
    fi
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
