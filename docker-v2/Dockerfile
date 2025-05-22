FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    curl \
    git \
    libcurl4-openssl-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql curl dom

# Enable Apache modules
RUN a2enmod proxy \
    && a2enmod headers \
    && a2enmod rewrite \
    && a2enmod proxy_http

# Set working directory
WORKDIR /var/www/html

# Copy web application
# COPY hamza-chat/chat-client/ /var/www/html/

# Copy Apache configuration
COPY docker-v2/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose ports
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"] 