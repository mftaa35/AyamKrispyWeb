# Gunakan image PHP resmi dengan Apache
FROM php:8.2-apache

# ✅ Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Aktifkan modul rewrite Apache (opsional tapi umum dipakai)
RUN a2enmod rewrite

# Copy semua file ke folder web server
COPY . /var/www/html/

# Set permission jika perlu (opsional)
RUN chown -R www-data:www-data /var/www/html

# Buka port 80
EXPOSE 80
