# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances système nécessaires
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

# Active mod_rewrite d'Apache
RUN a2enmod rewrite

# Installe Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définit le répertoire de travail dans le conteneur
WORKDIR /var/www/html/Vigisoft

# Copie les fichiers du projet dans le conteneur
COPY . .

# Configure git pour éviter les warnings dans les scripts Symfony
RUN git config --global --add safe.directory /var/www/html/Vigisoft

# Déclare les variables d'environnement nécessaires au build
ARG APP_ENV=prod
ARG APP_SECRET
ARG DATABASE_URL
ARG MESSENGER_TRANSPORT_DSN
ARG MAILER_DSN
ARG MYSQL_USER
ARG MYSQL_PASSWORD
ARG MYSQL_DATABASE
ARG MYSQL_HOST
ARG MYSQL_PORT

ENV APP_ENV=${APP_ENV}
ENV APP_SECRET=${APP_SECRET}
ENV DATABASE_URL=${DATABASE_URL}
ENV MESSENGER_TRANSPORT_DSN=${MESSENGER_TRANSPORT_DSN}
ENV MAILER_DSN=${MAILER_DSN}
ENV MYSQL_USER=${MYSQL_USER}
ENV MYSQL_PASSWORD=${MYSQL_PASSWORD}
ENV MYSQL_DATABASE=${MYSQL_DATABASE}
ENV MYSQL_HOST=${MYSQL_HOST}
ENV MYSQL_PORT=${MYSQL_PORT}

# Installe les dépendances PHP avec Composer en mode dev
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# Génère le cache d'environnement pour la dev (nécessite symfony/flex)
RUN composer dump-env prod

# Donne les droits nécessaires
RUN chown -R www-data:www-data /var/www/html/Vigisoft/var

# Expose le port 80
EXPOSE 80

# Lancement du serveur Apache
CMD ["apache2-foreground"]