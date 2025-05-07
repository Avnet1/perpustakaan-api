FROM php:8.3-fpm

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

RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=12M" >> /usr/local/etc/php/conf.d/uploads.ini

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . /var/www

RUN chown -R www-data:www-data /var/www

RUN composer install

RUN npm install && npm run build

# Copy entrypoint.sh
COPY ./docker/dev/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Gunakan entrypoint custom
ENTRYPOINT ["entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
