# ==========================================
# Stage 1 : Build des assets Vite
# ==========================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


# ==========================================
# Stage 2 : Installation des dépendances PHP
# ==========================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader \
    --no-interaction

COPY . .

RUN composer dump-autoload --optimize

RUN php artisan package:discover --ansi


# ==========================================
# Stage 3 : Production avec FrankenPHP
# ==========================================
FROM dunglas/frankenphp:1-php8.3

WORKDIR /app

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache \
    bcmath \
    exif \
    pcntl \
    gd

COPY --from=vendor /app /app
COPY --from=frontend /app/public/build /app/public/build

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

ENV APP_ENV=production

EXPOSE 8080

# Générique
# CMD ["php", "artisan", "octane:frankenphp", "--host=0.0.0.0", "--port=8080"]

# Spécifique à Railway
CMD ["sh", "-c", "php artisan octane:frankenphp --host=0.0.0.0 --port=${PORT:-8080}"]