FROM php:8.4-fpm-alpine AS base

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache sudo
RUN docker-php-ext-install bcmath pdo_mysql

FROM base AS dev

RUN apk add --no-cache $PHPIZE_DEPS linux-headers
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
COPY ./docker/fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
