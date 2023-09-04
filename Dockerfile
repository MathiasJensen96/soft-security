FROM php:8.2.9-apache

# Configure Apache
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip

# Set working directory
WORKDIR /var/www/html

# Copy Composer files and install dependencies
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli

# PHP configuration
COPY conf/php-override.ini $PHP_INI_DIR/conf.d/php-override.ini

# Copy source code
COPY src .
