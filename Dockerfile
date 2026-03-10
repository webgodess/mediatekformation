FROM php:8.5-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    curl \
    libcurl4-openssl-dev \
    git \
    unzip \
    zip \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]