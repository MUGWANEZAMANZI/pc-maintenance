# ==============================================
# Stage 1: Composer dependencies
# ==============================================
FROM php:8.3-fpm-alpine AS php-base

# System dependencies
RUN apk add --no-cache \
    git curl zip unzip nginx supervisor sqlite sqlite-dev libpq-dev bash nodejs npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

# Install Composer and PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-progress

# Publish Livewire assets & clear caches so assets exist BEFORE building JS
RUN ./vendor/bin/php artisan livewire:publish --assets || true \
    && php artisan livewire:clear || true \
    && php artisan optimize:clear || true

# ==============================================
# Stage 2: Node build (Vite/Tailwind assets)
# ==============================================
FROM node:20-alpine AS node-build
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund

# Copy Laravel + Livewire assets
COPY resources ./resources
COPY public ./public

# Copy vendor files
COPY --from=php-base /var/www/html/vendor ./vendor

COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Build assets
RUN npm run build

# ==============================================
# Stage 3: Final app image
# ==============================================
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache nginx bash curl zip unzip sqlite

WORKDIR /var/www/html

# Copy Laravel app
COPY . .

# Copy bootstrapped vendor + assets
COPY --from=php-base /var/www/html/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

# Make storage writable (Livewire writes cache here)
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Required for Livewire v3 asset resolver
ENV LIVEWIRE_ASSET_URL=${APP_URL}

COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENV PORT=8080
EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://localhost:$PORT/ || exit 1

ENTRYPOINT ["/entrypoint.sh"]
