FROM php:8.3-fpm

WORKDIR /var/www/symfony

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/symfony

RUN chown -R www-data:www-data /var/www/symfony \
    && chmod -R 755 /var/www/symfony

CMD ["php-fpm"]