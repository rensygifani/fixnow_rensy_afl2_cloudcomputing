# Gunakan image PHP dengan Apache
FROM php:8.1-apache

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
COPY src/ /var/www/html/../src/

# Install dependensi PHP menggunakan Composer
RUN composer require kreait/firebase-php

# Pastikan index.php ditemukan sebagai halaman utama
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Set ServerName untuk menghindari error
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80 (default untuk Apache)
EXPOSE 80

# Start Apache ketika container berjalan
CMD ["apache2-foreground"]
