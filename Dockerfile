FROM php:8.2

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libsqlite3-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_sqlite

WORKDIR /var/www/html

COPY composer.json /var/www/html/

RUN composer install

COPY . ../

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80", "-t", "."]
