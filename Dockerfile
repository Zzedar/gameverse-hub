FROM php:8.2-apache

# Installer dépendances système
RUN apt-get update && apt-get install -y unzip libssl-dev pkg-config git curl

# Installer les extensions PHP
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier le projet dans l'image Docker
COPY . .

# Installer les dépendances Composer
RUN composer install

# Activer mod_rewrite Apache
RUN a2enmod rewrite
