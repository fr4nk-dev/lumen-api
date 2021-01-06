FROM composer:2.0 as build
WORKDIR /app
COPY . /app
RUN composer install


FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 8080
COPY --from=build /app /var/www/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chmod 777 -R /var/www/storage/
RUN echo "Listen 8080" >> /etc/apache2/ports.conf
RUN chown -R www-data:www-data /var/www/ \
    && a2enmod rewrite
