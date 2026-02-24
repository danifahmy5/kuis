# Build frontend assets
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./vite.config.js
COPY public ./public
RUN npm run build

# PHP app
FROM php:8.4-cli

# System deps
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        zip \
        gd \
        bcmath \
        exif \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} app \
    && useradd -m -u ${UID} -g ${GID} -s /bin/bash app

WORKDIR /var/www

# Copy composer files first for better build cache
COPY composer.json composer.lock ./
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# App source
COPY . .

# Bring in built frontend assets
COPY --from=assets /app/public/build ./public/build

# Install dependencies with scripts after full source is present
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist \
    && php artisan storage:link || true \
    && chown -R app:app /var/www

# PHP config
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

USER app

EXPOSE 8011

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8011"]
