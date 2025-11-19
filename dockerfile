# Use PHP 8.1 with Nginx
FROM php:8.1-fpm

# Install Nginx and dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    unzip \
    curl \
    supervisor \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel backend app from subfolder
COPY lulu_b_backend/ .

# Copy .env file if it exists
RUN if [ -f .env ]; then chmod 644 .env; fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader 2>/dev/null || true

# Create necessary directories with proper permissions
RUN mkdir -p storage bootstrap/cache logs && \
    chmod -R 755 storage bootstrap/cache logs && \
    chown -R www-data:www-data /var/www/html

# Remove default nginx config
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# Copy nginx config
COPY nginx.conf /etc/nginx/sites-enabled/default

# Validate nginx configuration
RUN nginx -t

# Copy PHP-FPM config
COPY php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create supervisor log directory
RUN mkdir -p /var/log/supervisor

# Expose port 8080
EXPOSE 8080

# Override the FPM entrypoint and use supervisor as PID 1
ENTRYPOINT []
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]