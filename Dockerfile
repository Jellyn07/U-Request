# Use official PHP image with Apache
FROM php:8.2-apache

# Enable PHP extensions if needed
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all project files to Apache root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose the port Render will use
EXPOSE 10000

# Make sure Apache has write permissions if needed
RUN chown -R www-data:www-data /var/www/html/

# Enable Apache headers module
RUN a2enmod headers

# Start Apache in the foreground
CMD ["apache2-foreground"]
