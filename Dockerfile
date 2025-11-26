# Gunakan Image PHP 8.2
FROM php:8.2-cli

# 1. Install Library System
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    default-mysql-client \
    nodejs \
    npm

# 2. Bersihkan Cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install Ekstensi PHP (GANTI KE MYSQL DI SINI)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set Folder Kerja
WORKDIR /var/www

# 6. Copy file project
COPY . .

COPY database/certs/isrgrootx1.pem /etc/ssl/certs/isrgrootx1.pem

# 7. Install Dependensi PHP
RUN composer install --no-dev --optimize-autoloader

# 8. Install Dependensi JS & Compile CSS
RUN npm install && npm run build

# 9. Atur Hak Akses Storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 10. Jalankan Perintah Utama
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
