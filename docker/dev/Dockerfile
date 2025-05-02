# Use PHP CLI (not FPM) because Octane runs in CLI mode
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
    vim \
    nano \
    nodejs \
    npm \
    && apt-get clean

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (v18)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs


# Copy source code
COPY . /var/www

# Salin file .env
RUN cp .env.example .env

# Set permissions
RUN chown -R www-data:www-data /var/www


# Install Laravel dependencies
RUN composer install
RUN npm install && npm run build

# Install dependensi frontend menggunakan npm (sebagai contoh untuk hot reload)
RUN npm install && npm run build


# Expose port 8000 untuk aplikasi Laravel
EXPOSE 8000

# Default container command (overridden by docker-compose)
CMD ["php", "artisan", "serve" ,  "--host=0.0.0.0", "--port=8000"]
