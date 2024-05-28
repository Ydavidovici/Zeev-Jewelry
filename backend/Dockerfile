# Use an official PHP runtime as a parent image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Update CA certificates
RUN update-ca-certificates

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user
RUN useradd -m composer_user

# Create the vendor directory and set permissions as root
RUN mkdir -p /var/www/vendor && chown -R composer_user:composer_user /var/www/vendor

# Switch to the non-root user
USER composer_user

# Set Composer home environment variable for the non-root user
ENV COMPOSER_HOME="/home/composer_user/.composer"
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Set the CAfile environment variable for composer
ENV SSL_CERT_FILE="/etc/ssl/certs/ca-certificates.crt"

# Set the CAfile configuration for git
RUN git config --global http.sslCAInfo /etc/ssl/certs/ca-certificates.crt

# Copy composer files and install dependencies
COPY --chown=composer_user:composer_user composer.json /var/www/
COPY --chown=composer_user:composer_user composer.lock /var/www/

# Install PHP dependencies
RUN composer install --no-interaction --no-plugins --no-scripts

# Switch back to root to copy the rest of the application code
USER root

# Copy application code
COPY . /var/www

# Change ownership of application files
RUN chown -R www-data:www-data /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
