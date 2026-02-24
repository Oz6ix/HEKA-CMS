#!/bin/sh
set -e

cd /var/www/html

echo "==> HEKA-CMS: Starting setup..."

# Create .env file from environment variables
if [ ! -f .env ]; then
    echo "==> Creating .env file..."
    cat > .env <<EOF
APP_NAME=${APP_NAME:-HEKA}
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
APP_KEY=

DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_DATABASE=/var/www/html/database/database.sqlite

SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=120
CACHE_STORE=${CACHE_STORE:-file}
LOG_CHANNEL=${LOG_CHANNEL:-stderr}
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
EOF
fi

# Generate app key
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "==> Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Ensure SQLite database exists
touch database/database.sqlite 2>/dev/null || true

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "==> Migration warning (may be OK)"

# Seed database
echo "==> Seeding database..."
php artisan db:seed --force --no-interaction 2>&1 || echo "==> Seeding skipped"

# Cache for performance
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true
php artisan storage:link 2>&1 || true

# Fix permissions
chown -R www-data:www-data storage database bootstrap/cache public/uploads 2>/dev/null || true

echo "==> Setup complete. Starting PHP-FPM and Nginx on port 8080..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground (keeps container alive)
nginx -g "daemon off;"
