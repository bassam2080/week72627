FROM php:8.3-apache

COPY ./site/ /var/www/html/

WORKDIR /var/www/html/

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 80