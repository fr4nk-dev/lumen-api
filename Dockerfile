# FROM composer:2.0 as build
# WORKDIR /app
# COPY . /app
# RUN composer install


# FROM php:7.4-apache
# RUN docker-php-ext-install pdo pdo_mysql

# EXPOSE 8080
# COPY --from=build /app /var/www/
# COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
# COPY .env /var/www/.env
# RUN chmod 777 -R /var/www/storage/
# RUN echo "Listen 8080" >> /etc/apache2/ports.conf

# RUN a2enmod rewrite

FROM php:7.3

WORKDIR /var/www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
  php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
  php composer-setup.php && \
  php -r "unlink('composer-setup.php');" && \
  mv composer.phar /bin/composer

RUN  apt-get update \
  && apt-get install -y wget unzip git \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN wget https://get.symfony.com/cli/installer -O - | bash \
  && mv /root/.symfony/bin/symfony /bin/symfony

COPY . /var/www

RUN composer dump-env prod

CMD [ "symfony", "server:start"]