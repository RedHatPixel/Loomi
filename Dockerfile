# Stage 1 - Build Frontend (Vite)
FROM node:20 AS frontend

WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build


# Stage 2 - Backend (Laravel + PHP + Composer)
FROM php:8.4-fpm AS backend

RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

COPY --from=frontend /app/public/build ./public/build

# ✅ Install dependencies FIRST
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ✅ THEN run artisan commands (vendor/autoload.php now exists)
RUN mkdir -p /var/www/database \
    && touch /var/www/database/database.sqlite \
    && php artisan storage:link \
    && php artisan optimize

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# Nginx config — listens on Render's $PORT
COPY nginx.conf /etc/nginx/nginx.conf

# Start both FPM and Nginx
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["php-fpm"]
