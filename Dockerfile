FROM php:apache

# Install SQLite and required dependencies
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy custom Apache configuration
COPY default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
