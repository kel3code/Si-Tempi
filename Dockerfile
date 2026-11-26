
FROM php:8.3-apache

# Install kebutuhan PHP dan ekstensi MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Gunakan folder proyek Si-Tempi
WORKDIR /var/www/site

# Salin seluruh file proyek Si-Tempi ke container
COPY . .

# Install dependency berdasarkan composer.lock proyek
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-dev \
    --optimize-autoloader

# Atur Apache agar menggunakan folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/site/public

RUN sed -ri "s#/var/www/html#${APACHE_DOCUMENT_ROOT}#g" \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && printf '<Directory %s>\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
    "${APACHE_DOCUMENT_ROOT}" \
    > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Gunakan startup script yang sudah ada
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh

CMD ["/start.sh"]
