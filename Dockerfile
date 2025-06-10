# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances système nécessaires (git, unzip, zip, etc.)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install intl pdo_mysql zip opcache \
    && rm -rf /var/lib/apt/lists/*

# Active mod_rewrite d'Apache (indispensable Symfony)
RUN a2enmod rewrite

# Installe Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définit le répertoire de travail dans le conteneur
WORKDIR /var/www/html/Vigisoft

# Copie les fichiers du projet dans le conteneur
COPY . .

RUN git config --global --add safe.directory /var/www/html/Vigisoft

# Installe les dépendances PHP avec Composer en mode production
RUN ls -al && composer install --no-dev --optimize-autoloader --no-interaction

# Donne les droits nécessaires (optionnel, adapte selon ton user)
RUN chown -R www-data:www-data /var/www/html/Vigisoft/var

# Expose le port 80
EXPOSE 80

# Lancement du serveur Apache
CMD ["apache2-foreground"]