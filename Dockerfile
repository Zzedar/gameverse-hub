# Utilise l’image officielle PHP avec Apache
FROM php:8.2-apache

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Installe les dépendances MongoDB via Composer
RUN apt-get update && apt-get install -y unzip libssl-dev pkg-config git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer require mongodb/mongodb

# Copie le code dans le dossier Apache
COPY . /var/www/html/

# Active le module Apache rewrite
RUN a2enmod rewrite

# Définir le working directory
WORKDIR /var/www/html

# Expose le port
EXPOSE 80
