FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    bash \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    git \
    default-mysql-client \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/laravel
