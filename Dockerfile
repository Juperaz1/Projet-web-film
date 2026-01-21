FROM php:8.4-apache

# PHP + extensions
RUN apt-get update \
    && apt-get install -y git unzip \
    && docker-php-ext-install pdo pdo_mysql

# Récupération de Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache for Symfony - CORRECTION
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/app/public\n\
    \n\
    <Directory /var/www/app/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        DirectoryIndex index.php\n\
        FallbackResource /index.php\n\
    </Directory>\n\
    \n\
    ErrorLog /var/log/apache2/error.log\n\
    CustomLog /var/log/apache2/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/app

# Simple startup
CMD ["bash", "-c", "\
    composer install --no-interaction --no-progress || true && \
    chmod -R 777 var 2>/dev/null || true && \
    apache2-foreground \
"]