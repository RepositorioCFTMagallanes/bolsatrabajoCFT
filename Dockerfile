# =====================================================
# Imagen base: PHP 8.2 + Apache
# =====================================================
FROM php:8.2-apache

# =====================================================
# Paquetes del sistema + extensiones PHP necesarias
# (MySQL + PostgreSQL + Laravel)
# =====================================================
RUN apt-get update && apt-get install -y \
    git unzip zip \
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

# =====================================================
# Copiar código del proyecto
# =====================================================
COPY . /var/www/html

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
# Instalar dependencias PHP del proyecto
# =====================================================
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-progress \
    --no-interaction \
    --optimize-autoloader
    
# =====================================================
# Permisos correctos para Laravel
# =====================================================
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# =====================================================
# Apache escucha en 8080 (requerido por Render)
# =====================================================
RUN sed -i 's/80/8080/g' \
    /etc/apache2/ports.conf \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

# =====================================================
# Arranque del servidor
# =====================================================
CMD ["apache2-foreground"]
