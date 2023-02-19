FROM php:8.0-apache
RUN  a2enmod rewrite
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/

RUN apt-get update \ 
    && apt-get install -y \
    tzdata \
    libpq-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    && pecl install xdebug \
    && docker-php-ext-install pgsql pdo_pgsql intl \
    && rm -rf /var/lib/apt/lists/* 


ENV TZ=Asia/Tokyo