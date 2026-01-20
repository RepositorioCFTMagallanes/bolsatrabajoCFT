#!/bin/sh
# Copy secret if mounted to /app/secrets/laravel-env or /secrets/laravel-env
if [ -f /app/secrets/laravel-env ]; then
    cp /app/secrets/laravel-env /var/www/html/.env
elif [ -f /secrets/laravel-env ]; then
    cp /secrets/laravel-env /var/www/html/.env
fi

# Optimizar cachés
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente
php artisan migrate --force

# Crear enlace simbólico para archivos
php artisan storage:link

# Iniciar procesos
php-fpm -D
nginx -g "daemon off;"
