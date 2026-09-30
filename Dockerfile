# Gunakan image PHP dengan Apache
FROM php:8.3-apache

# Install dependensi yang dibutuhkan untuk Composer
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Download dan install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set document root ke public/
WORKDIR /var/www/html
COPY public/ /var/www/html/

# Install dependensi PHP menggunakan Composer
# vendor harus ada di /var/www supaya cocok dengan require '../vendor/autoload.php' di firebase_config.php
COPY composer.json composer.lock /var/www/
RUN cd /var/www && composer install --no-dev --no-interaction --optimize-autoloader

# Pastikan index.php ditemukan sebagai halaman utama
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Set ServerName untuk menghindari error
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80 (default untuk Apache)
EXPOSE 80

# Start Apache ketika container berjalan
CMD ["sh", "-c", "mkdir -p /var/www/src; cp /etc/secrets/firebase_credentials.json /var/www/src/firebase_credentials.json; chown www-data:www-data /var/www/src/firebase_credentials.json; chmod 600 /var/www/src/firebase_credentials.json; apache2-foreground"]
