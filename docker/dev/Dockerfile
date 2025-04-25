# ./docker/dev/Dockerfile
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Tag Labeling
LABEL maintener="Ruben Alpredo Tampubolon"
LABEL author="CV Indosistem"

# Install system dependencies
RUN apt-get update
RUN apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libicu-dev \
    vim \
    nano \
    cron \
    libmcrypt-dev nodejs npm

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Install Node (for hot reload, optional but useful)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Permissions for Laravel
RUN chown -R www-data:www-data /var/www


# Copy existing application directory contents
COPY . /var/www

RUN cp .env.example .env


# After other PHP extensions
RUN pecl install swoole \
    && docker-php-ext-enable swoole


# Install Laravel dependencies
RUN composer install

RUN npm install && npm run build

# Hot reload using Laravel Octane or Laravel's built-in server can be added later


EXPOSE 9000
