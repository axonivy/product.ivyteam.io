FROM composer AS builder

COPY composer.* /app/
RUN composer install

FROM php:8.2-apache
COPY --from=builder /app/vendor /var/www/html/vendor

RUN a2enmod rewrite
ADD docker/dev/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD src /var/www/html/src
