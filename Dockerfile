# Use official PHP image with Apache
FROM php:8.2-apache

# Enable PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache headers and rewrite modules
RUN a2enmod headers rewrite

# Copy all project files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Make sure Apache has write permissions if needed
RUN chown -R www-data:www-data /var/www/html/

# Expose the port Render will use
EXPOSE 10000

# Start Apache in the foreground
CMD ["apache2-foreground"]
