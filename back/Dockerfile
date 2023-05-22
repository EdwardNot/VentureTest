FROM php:7.3-apache
RUN apt-get update && apt upgrade -y
RUN apt-get install -y libzip-dev
COPY . /var/www/html/
WORKDIR /var/www/html/

# Installing mailer
# installing zip required by phpmailer
RUN docker-php-ext-install zip
# firstly download composer in local container
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
# RUN composer require phpmailer/phpmailer
RUN composer install

# installing pdo for mysql to connect database
RUN docker-php-ext-install pdo pdo_mysql

