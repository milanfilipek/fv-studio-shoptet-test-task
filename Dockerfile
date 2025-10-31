FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev cron && \
    docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN sed -ri -e 's!/var/www/html!/var/www/html/web!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf

RUN a2enmod rewrite \
 && echo '<Directory /var/www/html/web>\nAllowOverride All\nRequire all granted\n</Directory>' > /etc/apache2/conf-available/yii2.conf \
 && a2enconf yii2

RUN chown -R www-data:www-data /var/www/html

RUN composer install --no-interaction

# set up cron to run yii command every 15 minutes
RUN mkdir -p /etc/cron.d \
 && mkdir -p /var/www/html/runtime/logs \
 && echo "*/15 * * * * cd /var/www/html && /usr/local/bin/php yii sync-shoptet-products/cron >> /var/www/html/runtime/logs/cron.log 2>&1" > /etc/cron.d/shoptet-cron \
 && chmod 0644 /etc/cron.d/shoptet-cron \
 && crontab /etc/cron.d/shoptet-cron \
 && touch /var/www/html/runtime/logs/cron.log

RUN mkdir -p /var/www/html/runtime/logs && touch /var/www/html/runtime/logs/cron.log

EXPOSE 80

CMD service cron start && apache2-foreground
