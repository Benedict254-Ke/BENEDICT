#!/bin/bash
set -e

echo "=== Starting Laravel Application ==="

echo "Setting up Laravel cache..."
php artisan config:cache
php artisan cache:clear
php artisan view:clear

echo "Starting PHP-FPM..."
php-fpm -D

sleep 2

echo "Starting Nginx..."
nginx -g "daemon off;"