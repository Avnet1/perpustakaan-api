# Gunakan PHP CLI karena Laravel Octane menggunakan Swoole (bukan FPM)
FROM php:8.3-cli

# Set working directory
WORKDIR /var/www

# Tag Labeling
LABEL maintainer="Ruben Alpredo Tampubolon"
LABEL author="CV Indosistem"

# Install system dependencies
RUN apt-get update && apt-get install -y \
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
    nano \
    ca-certificates \
    gnupg \
    lsb-release \
    software-properties-common \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Swoole via PECL
RUN pecl install swoole && docker-php-ext-enable swoole

# Copy application source code
COPY . /var/www

# Set file permissions
RUN chown -R www-data:www-data /var/www

# Copy and prepare environment
COPY .env.production .env

# Run composer with optimized autoloader
RUN composer install --no-dev --optimize-autoloader

# Laravel setup
RUN php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan optimize

# Expose Octane port
EXPOSE 8000

# Start Laravel Octane in production mode
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0", "--port=8000"]
