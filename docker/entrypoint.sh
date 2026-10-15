#!/bin/sh
set -e

# Render injects the PORT environment variable (default to 8080 if not set)
RENDER_PORT="${PORT:-8080}"
echo "==> Configuring Nginx to listen on port ${RENDER_PORT}..."
sed -i "s/__PORT__/${RENDER_PORT}/g" /etc/nginx/http.d/default.conf

# Ensure storage subdirectories exist
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Fix permissions for web user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    echo "==> Linking storage directory..."
    php artisan storage:link || true
fi

# Run database migrations if RUN_MIGRATIONS is true
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "==> Executing database migrations (MySQL)..."
    php artisan migrate --force || echo "==> [WARN] Migration failed or database not ready yet."
fi

# Optimization cache for production (only if APP_KEY is provided)
if [ -n "$APP_KEY" ]; then
    echo "==> Caching Laravel configuration, routes and views..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "==> [WARN] APP_KEY is empty. Skipping artisan cache to avoid boot errors."
fi

echo "==> Starting Supervisord (PHP-FPM + Nginx)..."
exec "$@"
