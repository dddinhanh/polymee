# Base image: PHP 8.2 kèm Apache web server
FROM php:8.2-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Cài đặt các extension PHP PDO (MySQL & SQLite)
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Kích hoạt Apache mod_rewrite để hỗ trợ Clean URL (.htaccess)
RUN a2enmod rewrite

# Cấu hình VirtualHost trỏ DocumentRoot vào thư mục public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cho phép override cấu hình từ .htaccess
RUN echo "<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Sao chép toàn bộ mã nguồn vào container
WORKDIR /var/www/html
COPY . /var/www/html/

# Phân quyền tệp tin
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
