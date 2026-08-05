FROM php:8.3-cli-alpine

# Install system libraries and required PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    nodejs \
    npm \
    git \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql intl zip gd mbstring

# Copy Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application source code
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Install Node dependencies and build Vite frontend assets
RUN npm install && npm run build

# Create storage directories and set permissions
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 8000

# Start command with permissions and cache cleanup
CMD ["sh", "-c", "mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache && php artisan storage:link --force && php artisan config:clear && php artisan view:clear && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
