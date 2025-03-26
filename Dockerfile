FROM php:8.2-apache

# Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y unzip libssl-dev pkg-config git curl \
    && docker-php-ext-install pdo pdo_mysql

# Installe Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Copie les fichiers de ton projet dans le conteneur
COPY . /var/www/html/

# Va dans le dossier du projet et installe les dépendances PHP
WORKDIR /var/www/html/
RUN composer install

# Active le module Apache rewrite
RUN a2enmod rewrite
