#!/bin/sh
set -e

cd /var/www/html

echo "==> Starting HEKA-CMS setup..."

# Generate .env file from environment variables if it doesn't exist
if [ ! -f .env ]; then
    echo "==> Creating .env from environment variables..."
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

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "==> Generating application key..."
    php artisan key:generate --force --no-interaction
fi

# Ensure SQLite database exists
if [ ! -f database/database.sqlite ]; then
    echo "==> Creating SQLite database..."
    touch database/database.sqlite
fi

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "Migration warning (may be OK on first run)"

# Seed the database
echo "==> Seeding database..."
php artisan db:seed --force --no-interaction 2>&1 || echo "Seeding skipped or already done"

# Cache config and routes for performance
echo "==> Caching configuration..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

# Create storage link
php artisan storage:link 2>&1 || true

# Fix permissions
chown -R www-data:www-data storage database bootstrap/cache public/uploads 2>/dev/null || true

echo "==> HEKA-CMS is ready! Starting services on port 8080..."

exec "$@"
