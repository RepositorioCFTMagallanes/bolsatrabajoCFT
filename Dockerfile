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
    ca-certificates \
    openssl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libmagic-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
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
COPY . .

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
# Eliminar cualquier cache previa de configuración
# (CRÍTICO para Cloud Run)
# =====================================================
RUN rm -f bootstrap/cache/config.php \
    bootstrap/cache/routes-v7.php \
    bootstrap/cache/packages.php \
    bootstrap/cache/services.php \
    || true

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
# Configuración PHP segura para uploads
# (AJUSTE CRÍTICO PARA BLOB Y CLOUD RUN)
# =====================================================
RUN echo "upload_max_filesize=16M" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=16M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time=120" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "upload_tmp_dir=/tmp" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "sys_temp_dir=/tmp" >> /usr/local/etc/php/conf.d/uploads.ini

# Asegurar carpeta temporal
RUN mkdir -p /tmp && chmod 1777 /tmp

# =====================================================
# Apache escucha en 8080 (requerido por Cloud Run)
# =====================================================
RUN sed -i 's/80/8080/g' \
    /etc/apache2/ports.conf \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 8080

# =====================================================
# Script de arranque para limpiar cache en runtime
# =====================================================
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
