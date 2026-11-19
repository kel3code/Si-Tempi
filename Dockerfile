FROM php:8.3-apache
RUN apt-get update && apt-get install -y --no-install-recommends git unzip libzip-dev libpq-dev \
 && docker-php-ext-install pdo_mysql pdo_pgsql zip && a2enmod rewrite && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/site
RUN composer create-project laravel/laravel:^12.0 . --no-interaction --prefer-dist
COPY app ./app
COPY database ./database
COPY resources ./resources
COPY routes ./routes
ENV APACHE_DOCUMENT_ROOT=/var/www/site/public
RUN sed -ri "s#/var/www/html#${APACHE_DOCUMENT_ROOT}#g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]
