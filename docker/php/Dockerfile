FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo pdo_mysql

RUN apt-get -y update \

# Zainstaluj Composer globalnie
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer