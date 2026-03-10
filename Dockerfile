FROM php:8.5-fpm

WORKDIR /app

RUN docker-php-ext-install pdo_mysql curl mbstring

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]