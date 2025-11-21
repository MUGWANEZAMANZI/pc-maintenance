# ==============================================
# Stage 1: Base PHP + Composer
# ==============================================
FROM php:8.3-fpm-alpine AS php-base

# System dependencies
RUN apk add --no-cache \
    git curl zip unzip sqlite sqlite-dev libpq-dev \
    bash nginx supervisor nodejs npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql

WORKDIR /var/www/html

# Copy Laravel app first so artisan exists
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

# Publish Livewire assets to prevent 404
RUN php artisan livewire:publish --assets || true

# Clear caches for clean build
RUN php artisan optimize:clear || true

# ==============================================
# Stage 2: Build Vite assets with Node
# ==============================================
FROM node:20-alpine AS node-build
WORKDIR /app

# Copy necessary files
COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund

# Copy everything needed for Vite
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./
COPY --from=php-base /var/www/html/vendor ./vendor

# Build frontend
RUN npm run build

# ==============================================
# Stage 3: Final app image (Nginx + PHP-FPM)
# ==============================================
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache nginx bash curl zip unzip sqlite

WORKDIR /var/www/html

# Copy full app code
COPY . .

# Copy vendor and built assets
COPY --from=php-base /var/www/html/vendor ./vendor
COPY --from=node-build /app/public/build ./public/build

# Make storage writable
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Let Livewire resolve assets correctly
ENV LIVEWIRE_ASSET_URL=${APP_URL}

# ---- Nginx configuration ----
COPY nginx.conf /etc/nginx/nginx.conf

# ---- Entrypoint ----
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Render will inject PORT, default 8080
ENV PORT=8080
EXPOSE 8080

# Let Render verify container health
HEALTHCHECK --interval=30s --timeout=5s \
  CMD curl -f http://localhost:$PORT/ || exit 1

ENTRYPOINT ["/entrypoint.sh"]
