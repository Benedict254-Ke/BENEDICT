#!/bin/bash
set -e

echo "=== Starting Application ==="
echo "Starting PHP-FPM in background..."
/usr/local/sbin/php-fpm -D

echo "Waiting for PHP-FPM to be ready..."
sleep 2

echo "Starting Nginx..."
/usr/sbin/nginx -g "daemon off;"