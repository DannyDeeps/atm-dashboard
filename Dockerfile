FROM dunglas/frankenphp:latest

# Required PHP extensions
RUN install-php-extensions pdo_pgsql zip

# Production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php --install-dir=/usr/bin --filename=composer --quiet \
  && php -r "unlink('composer-setup.php');"

# Copy app
COPY . /app
WORKDIR /app

# Create config.php from example (env vars override at runtime)
RUN cp config.example.php config.php

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Heads cache must be writable at runtime
RUN mkdir -p /app/public/images/heads && chown -R www-data:www-data /app/public/images/heads

# FrankenPHP defaults (overridable via env)
ENV SERVER_NAME=:80
ENV SERVER_ROOT=public/

EXPOSE 80
