# Use the official PHP image as the base image
FROM php:7.4-apache

# Set working directory
WORKDIR /var/www

# Replace the default Debian repository mirror and clean apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install dependencies with retry mechanism
RUN set -eux; \
    for i in $(seq 1 5); do \
      apt-get update && \
      apt-get install -y --fix-missing \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        zip \
        unzip \
        git \
        curl \
        libonig-dev \
        libxml2-dev \
        libzip-dev && \
      docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip && \
      break || sleep 5; \
    done && \
    apt-get clean

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www-data
USER www-data

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
