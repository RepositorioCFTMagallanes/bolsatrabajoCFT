# =====================================================
# Imagen base: PHP 8.2 + Apache
# =====================================================
FROM php:8.2-apache

# =====================================================
# Paquetes del sistema + extensiones PHP necesarias
# =====================================================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libmagic-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        bcmath \
        exif \
        fileinfo \
        pcntl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# =====================================================
# Composer (desde imagen oficial)
# =====================================================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =====================================================
# Directorio de trabajo
# =====================================================
WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . /var/www/html

# =====================================================
# Instalar dependencias Laravel
# =====================================================
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-progress \
    --no-interaction \
    --optimize-autoloader

# =====================================================
# Limpiar cualquier cache de configuración
# (CRÍTICO para Cloud Run y variables de entorno)
# =====================================================
RUN rm -f bootstrap/cache/config.php \
    bootstrap/cache/routes*.php \
    bootstrap/cache/packages.php \
    bootstrap/cache/services.php \
    && php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear

# =====================================================
# Apache → DocumentRoot = /public
# =====================================================
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# =====================================================
# Permitir .htaccess (Laravel routing)
# =====================================================
RUN printf '<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n' \
    > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# =====================================================
# Permisos correctos para Laravel
# =====================================================
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# =====================================================
# Configuración PHP para uploads
# =====================================================
RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=10M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/uploads.ini

# =====================================================
# Apache escucha en 8080 (requerido por Cloud Run)
# =====================================================
RUN sed -i 's/80/8080/g' \
    /etc/apache2/ports.conf \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

# =====================================================
# Arranque del servidor
# =====================================================
CMD ["apache2-foreground"]
