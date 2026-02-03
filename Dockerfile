FROM php:8.1-apache

# Copy application files
COPY . /var/www/html/

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]