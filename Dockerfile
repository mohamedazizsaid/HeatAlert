# ==============================================================================
# STAGE 1: Frontend Build (Node.js & Vite)
# ==============================================================================
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copy dependency manifests
COPY package*.json ./

# Install npm dependencies
RUN npm ci || npm install

# Copy frontend source files & configs
COPY resources/ ./resources/
COPY public/ ./public/
COPY vite.config.js* tailwind.config.js* postcss.config.js* ./

# Build assets into public/build
RUN npm run build


# ==============================================================================
# STAGE 2: PHP Composer Dependencies
# ==============================================================================
FROM composer:2 AS composer-builder

WORKDIR /app

# Copy composer manifests
COPY composer.json composer.lock ./

# Install vendor dependencies without dev packages and scripts
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

# Copy full application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev


# ==============================================================================
# STAGE 3: Production Runtime (PHP 8.2 FPM + Nginx + Supervisord)
# ==============================================================================
FROM php:8.2-fpm-alpine AS runner

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    sed \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    icu-libs

# Install PHP extensions required by Laravel & MySQL
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    intl

# Set working directory
WORKDIR /var/www/html

# Copy server & daemon configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Normalize line-endings (CRLF -> LF) and give execute permission
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Copy application files and vendor dependencies from composer builder
COPY --from=composer-builder --chown=www-data:www-data /app /var/www/html

# Copy compiled frontend assets from node builder
COPY --from=node-builder --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Setup storage and cache directories permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose default HTTP port (overridden dynamically by Render via $PORT)
EXPOSE 8080

# Configure container entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
