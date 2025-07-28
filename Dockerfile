# Dockerfile pour MaxIT Application
FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    && docker-php-ext-enable pdo_pgsql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration Apache
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copie du code source
WORKDIR /var/www/html
COPY . .

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Permissions pour les uploads
RUN mkdir -p public/uploads/cni && \
    chown -R www-data:www-data public/uploads && \
    chmod -R 755 public/uploads

# Configuration de production
RUN php setup-production.php || true

# Port exposé
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
