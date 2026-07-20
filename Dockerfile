FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
        git curl unzip \
        libonig-dev libxml2-dev libpq-dev libzip-dev \
        nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first so this layer is cached unless composer.json changes
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Install JS deps and build assets before copying the rest of the app
COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN composer dump-autoload --optimize \
    && npm run build \
    && php artisan storage:link

EXPOSE 8080

CMD php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
