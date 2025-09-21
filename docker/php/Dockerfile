FROM php:8.3-fpm-alpine

# Установка необходимых пакетов и расширений
RUN apk add --no-cache \
        bash \
        git \
        unzip \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        libpq-dev \
        build-base \
        autoconf \
        make \
        curl \
        supervisor \
    && docker-php-ext-install pdo_pgsql intl mbstring zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Создание директорий для supervisor
RUN mkdir -p /var/log/supervisor /var/run \
    && chown -R www-data:www-data /var/log/supervisor /var/run

WORKDIR /var/www

COPY ./supervisord.conf /etc/supervisord.conf
COPY ./supervisor.d /etc/supervisor.d/
