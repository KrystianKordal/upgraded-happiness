FROM php:8.4-fpm

RUN apt-get update && apt-get install -y libicu-dev git zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer