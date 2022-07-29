FROM php:8.1-apache

RUN apt-get update
RUN apt-get -y upgrade --fix-missing --no-install-recommends

RUN a2enmod rewrite headers vhost_alias

RUN apt-get install -y \
        wget \
        git \
        curl \
        libcurl4-gnutls-dev \
        zip \
        openssl \
        libzip-dev \
        libxml2-dev \
        autoconf \
        g++ \
        make \
        libpng-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && apt-get autoremove -y \
    && apt-get clean -y \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www/html