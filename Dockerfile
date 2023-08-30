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

# Copy source code
COPY . .
