#!/bin/sh
set -e
cd /var/www/site
PORT="${PORT:-80}"
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
php artisan migrate --force
php artisan config:cache
chown -R www-data:www-data storage bootstrap/cache
su -s /bin/sh www-data -c "php artisan schedule:work >/dev/null 2>&1 &"
exec apache2-foreground
