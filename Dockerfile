# Stage 1 - Build Frontend (Vite)
FROM node:20 AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.4-fpm AS backend

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy app files first
COPY . .

# Create storage link & SQLite database
RUN mkdir -p /var/www/database \
    && touch /var/www/database/database.sqlite \
    && php artisan storage:link

# Copy built frontend from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies (with lock file verification)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Production optimization (replaces individual clear commands)
RUN php artisan optimize

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

USER www-data

CMD ["php-fpm"]
