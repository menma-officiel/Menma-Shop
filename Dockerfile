# Dockerfile pour déployer l'application sur Render (PHP 8.2 + Apache + pdo_pgsql)
FROM php:8.2-apache

# Installer dépendances et extension pdo_pgsql
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev git unzip zip \
    && docker-php-ext-install pdo_pgsql pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier l'application
COPY . /var/www/html/

# Donner les permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80

# Commande par défaut
CMD ["apache2-foreground"]
