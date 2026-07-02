FROM dunglas/frankenphp:latest

# Required PHP extensions
RUN install-php-extensions pdo_pgsql

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy app
COPY . /app

# Install PHP dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Heads cache must be writable at runtime
RUN mkdir -p /app/public/images/heads && chown -R www-data:www-data /app/public/images/heads

# FrankenPHP defaults (overridable via env)
ENV SERVER_NAME=:80
ENV SERVER_ROOT=public/

EXPOSE 80
