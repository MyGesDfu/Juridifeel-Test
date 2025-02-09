# Étape 1: Créer une image avec PHP et Composer
FROM php:8.2-fpm

# Installer les dépendances requises
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev git unzip libxml2-dev libzip-dev

# Installer les extensions PHP nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Créer un répertoire pour le projet
WORKDIR /var/www/html

# Copier les fichiers de Symfony
COPY . .

# Installer les dépendances du projet
RUN composer install --no-dev --optimize-autoloader

# Configurer les permissions pour le répertoire var
RUN chown -R www-data:www-data /var/www/html/var && \
    chmod -R 775 /var/www/html/var

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["sh", "-c", "chown -R www-data:www-data /var/www/html/var && chmod -R 775 /var/www/html/var && php-fpm"]
