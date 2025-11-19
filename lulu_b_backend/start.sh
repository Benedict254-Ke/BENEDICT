#!/bin/bash
set -e

echo "Starting PHP-FPM..."
php-fpm &

echo "Starting Nginx..."
exec nginx -g "daemon off;"
