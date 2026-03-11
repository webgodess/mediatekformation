FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-dev --optimize-autoloader --no-interaction

EXPOSE 8080

CMD ["php", "-d", "display_errors=0", "-d", "error_reporting=24575", "-S", "0.0.0.0:8080", "-t", "public"]