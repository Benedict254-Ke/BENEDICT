#!/bin/bash
set -e

# Substitute $PORT into nginx config at runtime
envsubst '${PORT}' < /etc/nginx/sites-enabled/default > /etc/nginx/sites-enabled/default.tmp
mv /etc/nginx/sites-enabled/default.tmp /etc/nginx/sites-enabled/default

# Start PHP-FPM in background
php-fpm &

# Start Nginx in foreground (Render detects this port)
exec nginx -g "daemon off;"
