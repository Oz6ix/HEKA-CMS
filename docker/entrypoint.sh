#!/bin/sh
set -e

cd /var/www/html

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
fi

# Run migrations
php artisan migrate --force --no-interaction

# Seed the database if it's fresh (check if users table is empty)
php artisan db:seed --force --no-interaction 2>/dev/null || true

# Cache config and routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link 2>/dev/null || true

# Fix permissions
chown -R www-data:www-data storage database bootstrap/cache public/uploads 2>/dev/null || true

echo "==> HEKA-CMS is starting..."

exec "$@"
