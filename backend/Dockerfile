# Use an official PHP runtime as a parent image
FROM php:8.0-fpm

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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create a non-root user and switch to it
RUN useradd -m composer_user
USER composer_user

# Set Composer home environment variable for the non-root user
ENV COMPOSER_HOME="/home/composer_user/.composer"
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

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
