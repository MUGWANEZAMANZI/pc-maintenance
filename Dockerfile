# ==============================================
# Stage 1: Composer dependencies
# ==============================================
FROM php:8.3-fpm-alpine AS php-base

# System dependencies
RUN apk add --no-cache \
    git curl zip unzip nginx supervisor sqlite sqlite-dev libpq-dev bash nodejs npm

# PHP extensions (common for Laravel)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql

WORKDIR /var/www/html

COPY composer.json composer.lock* ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --prefer-dist --no-interaction --no-scripts --no-progress \
    && rm -rf /root/.composer

# ==============================================
# Stage 2: Node build (Vite/Tailwind assets)
# ==============================================
FROM node:20-alpine AS node-build
WORKDIR /app

# Copy package files and install dependencies
COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund

# Copy all necessary files for Vite build
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Copy vendor for any dependencies
COPY --from=php-base /var/www/html/vendor ./vendor

# Build assets (fail if build fails)
RUN npm run build

# ==============================================
# Stage 3: Final image
# ==============================================
FROM php:8.3-fpm-alpine AS app

RUN apk add --no-cache nginx bash curl zip unzip sqlite

WORKDIR /var/www/html

# Copy application source
COPY . .
# Copy vendor from build stage
COPY --from=php-base /var/www/html/vendor ./vendor
# Copy built assets
COPY --from=node-build /app/public/build ./public/build

# Entry scripts / config
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Allow overriding port via Render PORT env
ENV PORT=8080
EXPOSE 8080

# Keep root for simplicity on Render; php-fpm runs as www-data internally.
# (If you require non-root, ensure permissions handled pre-USER switch.)

# Healthcheck (simple)
HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://localhost:$PORT/ || exit 1

ENTRYPOINT ["/entrypoint.sh"]
