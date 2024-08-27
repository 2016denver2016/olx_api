# PHP-FPM is a FastCGI implementation for PHP.
# Read more here: https://hub.docker.com/_/php
FROM php:7.4-fpm


RUN apt-get update

# Install useful tools
RUN apt-get -y install nano wget

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Clear cache


# Install Composer
#COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html
RUN apt-get update

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog vim

# Install system dependencies
RUN apt-get -y install --fix-missing \
    apt-utils \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install \
    exif \
    pcntl \
    bcmath \
    ctype \
    curl \
    pcntl \
    zip

# Install Composer
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN echo "0 0 * * * root php /var/www/html && php artisan verify-advert:price >> /var/log/cron.log 2>&1" >> /etc/crontab

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Copy existing application directory contents
COPY ./src /var/www/html

# Copy existing application directory permissions
COPY --chown=www:www ./src /var/www/html

# Change current user to www
USER www

# Set port for application
EXPOSE 8001