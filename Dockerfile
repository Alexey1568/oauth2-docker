FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y postgresql-client

WORKDIR /var/www/html
COPY ./auth_test_proj/ /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./auth_test_proj/.env /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install
