#!/bin/sh
# No usamos 'set -e' al inicio para tener más control sobre los errores
echo "--- Iniciando script de entrada ---"
echo "USER: $(id)"
echo "PATH: $PATH"
echo "PWD: $(pwd)"
echo "PHP version: $(php -v | head -n 1 || echo 'php no encontrado')"
ls -la /var/www/html/public/index.php || echo "index.php NO ENCONTRADO en /var/www/html/public/"

# Ahora sí, si falla algo crítico, paramos si es necesario (o lo manejamos manual)
# set -e 

# Copiar secreto si está montado
if [ -f /app/secrets/laravel-env ]; then
    echo "Copiando configuración desde /app/secrets/laravel-env"
    cp /app/secrets/laravel-env /var/www/html/.env
elif [ -f /secrets/laravel-env ]; then
    echo "Copiando configuración desde /secrets/laravel-env"
    cp /secrets/laravel-env /var/www/html/.env
else
    echo "ADVERTENCIA: No se encontró archivo de secretos. Usando .env actual si existe."
fi

# Asegurar permisos correctos
echo "Ajustando permisos de storage y cache..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Paso de optimización (opcional pero recomendado)
echo "Optimizando caches de Laravel..."
php artisan config:cache || echo "No se pudo cachear config"
php artisan route:cache || echo "No se pudo cachear rutas"
php artisan view:cache || echo "No se pudo cachear vistas"

# Migraciones
echo "Intentando ejecutar migraciones..."
php artisan migrate --force || echo "ADVERTENCIA: Las migraciones fallaron. Verifica la conexión a la base de datos."

# Link de storage
echo "Creando symlink de storage..."
php artisan storage:link || echo "El link de storage ya existe o falló"

echo "Iniciando PHP-FPM..."
php-fpm -D

echo "Iniciando Nginx en puerto 8080..."
# No usamos 'set -e' aquí para que el contenedor intente mantenerse vivo si nginx inicia
nginx -g "daemon off;"
