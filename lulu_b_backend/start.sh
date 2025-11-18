#!/bin/bash
set -e

echo "[$(date)] === Starting Application ===" > /proc/1/fd/1

echo "[$(date)] Starting PHP-FPM..." > /proc/1/fd/1
/usr/local/sbin/php-fpm -D

echo "[$(date)] Sleeping 3 seconds..." > /proc/1/fd/1
sleep 3

echo "[$(date)] Starting Nginx..." > /proc/1/fd/1
exec /usr/sbin/nginx -g "daemon off;"