# Dockerfile pour MaxIT Application - Railway
FROM php:8.2-cli

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

# Copie du code source
WORKDIR /app
COPY . .

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Permissions pour les uploads
RUN mkdir -p public/uploads/cni && \
    chmod -R 755 public/uploads

# Configuration de production
RUN php setup-production.php || true

# Port exposé (Railway utilise la variable $PORT)
EXPOSE $PORT

# Commande de démarrage pour Railway
CMD php -S 0.0.0.0:$PORT -t public
