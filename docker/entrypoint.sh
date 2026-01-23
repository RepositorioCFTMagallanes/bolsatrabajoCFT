#!/bin/sh
# No 'set -e' for the whole script to allow background tasks and errors
# set -e

echo "--- Starting Entrypoint Script ---"
echo "Port: ${PORT:=8080}"

# Copy secrets if available
if [ -f /app/secrets/laravel-env ]; then
    echo "Using secrets from /app/secrets/laravel-env"
    cp /app/secrets/laravel-env /var/www/html/.env
elif [ -f /secrets/laravel-env ]; then
    echo "Using secrets from /secrets/laravel-env"
    cp /secrets/laravel-env /var/www/html/.env
else
    echo "Notice: No secret file found at /app/secrets/laravel-env or /secrets/laravel-env"
fi

# Configure Nginx port
echo "Configuring Nginx to listen on port: $PORT"
sed -i "s/LISTEN_PORT/$PORT/g" /etc/nginx/http.d/default.conf

# Set permissions (in background to start faster)
echo "Setting permissions for storage and cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel optimizations and migrations in BACKGROUND
echo "Starting Laravel tasks in background..."
(
    php artisan config:cache || echo "Config cache failed"
    php artisan route:cache || echo "Route cache failed"
    php artisan view:cache || echo "View cache failed"
    php artisan storage:link || echo "Storage link failed"
    php artisan migrate --force || echo "Migrations failed"
) &

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
# Create directory for pid file if needed
mkdir -p /run/nginx
nginx -t
exec nginx -g "daemon off;"
