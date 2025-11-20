#!/usr/bin/env sh
set -e

echo "[entrypoint] === PC Maintenance Container Starting ==="
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
    cat > /var/www/html/.env <<EOF
APP_NAME=${APP_NAME:-Laravel}
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stack
LOG_LEVEL=info

DB_CONNECTION=sqlite
DB_DATABASE=${DB_DATABASE:-/var/data/database.sqlite}

CACHE_DRIVER=${CACHE_DRIVER:-file}
SESSION_DRIVER=${SESSION_DRIVER:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
EOF
  fi
fi

# Ensure SQLite database exists
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  DB_PATH="${DB_DATABASE:-/var/data/database.sqlite}"
  DB_DIR=$(dirname "$DB_PATH")
  echo "[entrypoint] Ensuring SQLite database directory exists: $DB_DIR"
  mkdir -p "$DB_DIR" || { echo "[entrypoint] ERROR: Failed to create $DB_DIR"; exit 1; }
  
  if [ ! -f "$DB_PATH" ]; then
    echo "[entrypoint] Creating SQLite database file: $DB_PATH"
    touch "$DB_PATH" || { echo "[entrypoint] ERROR: Failed to create $DB_PATH"; exit 1; }
  fi
  
  # Set proper ownership and permissions for database and directory
  if [ "$(id -u)" = "0" ]; then
    chown www-data:www-data "$DB_DIR" "$DB_PATH" || echo "[entrypoint] WARNING: Could not chown database"
  fi
  chmod 775 "$DB_DIR" || echo "[entrypoint] WARNING: Could not chmod database directory"
  chmod 664 "$DB_PATH" || echo "[entrypoint] WARNING: Could not chmod database file"
  
  echo "[entrypoint] Database file ready: $(ls -lh $DB_PATH)"
  echo "[entrypoint] Database directory: $(ls -ldh $DB_DIR)"
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
php artisan config:cache || { echo "[entrypoint] ERROR: config:cache failed"; exit 1; }
# Skip route caching to allow Livewire dynamic asset route in production.
# php artisan route:cache || echo "[entrypoint] WARNING: route:cache failed, continuing..."
php artisan view:cache || { echo "[entrypoint] ERROR: view:cache failed"; exit 1; }

if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "[entrypoint] Running migrations"
  php artisan migrate --force
fi

if [ "${RUN_SEED}" = "true" ]; then
  echo "[entrypoint] Seeding database"
  php artisan db:seed --force || true
fi

echo "[entrypoint] Starting PHP-FPM and Nginx"
# Substitute PORT in nginx config
sed -i "s/PORT_PLACEHOLDER/${PORT:-8080}/g" /etc/nginx/nginx.conf || { echo "[entrypoint] ERROR: nginx config substitution failed"; exit 1; }

echo "[entrypoint] === Environment Check ==="
echo "  APP_ENV: ${APP_ENV:-not_set}"
echo "  APP_DEBUG: ${APP_DEBUG:-not_set}"
echo "  DB_CONNECTION: ${DB_CONNECTION:-sqlite}"
echo "  DB_DATABASE: ${DB_DATABASE:-/var/data/database.sqlite}"
echo "  PORT: ${PORT:-8080}"
echo "[entrypoint] =========================="

php-fpm -D
nginx -g 'daemon off;'
