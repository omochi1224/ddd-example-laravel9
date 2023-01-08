FROM php:8.2-fpm
COPY php.ini /usr/local/etc/php/

RUN apt-get update \
    && apt-get install -y zlib1g-dev libzip-dev mariadb-client git libicu-dev \
    && docker-php-ext-install zip pdo_mysql

#Xdebug
RUN pecl install xdebug-3.2.0 && docker-php-ext-enable xdebug

#Redis
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis
RUN docker-php-ext-install mysqli redis

#intl
RUN docker-php-ext-install intl

#Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www