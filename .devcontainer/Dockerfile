FROM php:8.4-apache

# Install dependencies, enable mod_rewrite, configure Apache, and set permissions
RUN apt-get update && \
    apt-get install -y git unzip && \
    apt-get clean && rm -rf /var/lib/apt/lists/* && \
    a2enmod rewrite && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    chown -R www-data:www-data /var/www

# Install Composer
COPY --from=composer:2.8.10 /usr/bin/composer /usr/bin/composer

# Set document root and working directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

WORKDIR /var/www/html
