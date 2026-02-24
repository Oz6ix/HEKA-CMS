# ---- Stage 1: Build frontend assets ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources/ resources/
COPY vite.config.js ./
COPY public/ public/
RUN npm run build

# ---- Stage 2: Install PHP dependencies ----
FROM composer:2 AS composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

# ---- Stage 3: Production image ----
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_sqlite opcache bcmath

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Configure PHP-FPM: pass env vars to PHP, listen on TCP
RUN sed -i 's|;clear_env = no|clear_env = no|g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's|listen = 127.0.0.1:9000|listen = 127.0.0.1:9000|g' /usr/local/etc/php-fpm.d/www.conf

# Configure Nginx
RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
# Remove default nginx server block if it conflicts
RUN rm -f /etc/nginx/http.d/default.conf.bak

# Set working directory
WORKDIR /var/www/html

# Copy app from composer stage
COPY --from=composer /app .

# Copy built frontend assets
COPY --from=frontend /app/public/build public/build

# Create SQLite database and storage directories
RUN mkdir -p database storage/app/public storage/framework/cache/data \
    storage/framework/sessions storage/framework/views storage/logs \
    bootstrap/cache public/uploads \
    && touch database/database.sqlite \
    && chown -R www-data:www-data . \
    && chmod -R 775 storage bootstrap/cache database public/uploads

# Startup script
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
