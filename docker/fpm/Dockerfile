FROM php:8.4-fpm-alpine AS base

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache sudo
RUN docker-php-ext-install bcmath pdo_mysql

FROM base AS dev

ENV NEWUSER='dev'

RUN apk add --no-cache $PHPIZE_DEPS linux-headers sudo
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN adduser -D -g "${NEWUSER}" $NEWUSER
RUN echo "$NEWUSER ALL=(ALL) NOPASSWD: ALL" > /etc/sudoers.d/$NEWUSER && chmod 0440 /etc/sudoers.d/$NEWUSER
COPY ./docker/fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
