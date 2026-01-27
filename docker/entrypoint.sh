#!/bin/sh
echo "--- Iniciando Contenedor ---"
export PORT=${PORT:-8080}

# Configuración Nginx
sed -i "s/LISTEN_PORT/$PORT/g" /etc/nginx/http.d/default.conf

# Secretos
if [ -f /app/secrets/laravel-env ]; then
    cp /app/secrets/laravel-env /var/www/html/.env
elif [ -f /secrets/laravel-env ]; then
    cp /secrets/laravel-env /var/www/html/.env
fi

# Permisos rápidos
chown -R www-data:www-data storage bootstrap/cache

# PHP-FPM en segundo plano
php-fpm -D

# Tareas de Laravel en segundo plano (para no bloquear el puerto)
(
    php artisan config:cache || true
    php artisan storage:link || true
    php artisan migrate --force || echo "Migraciones fallaron (verificar BD)"
) &

echo "Servidor listo en puerto $PORT"
exec nginx -g "daemon off;"
