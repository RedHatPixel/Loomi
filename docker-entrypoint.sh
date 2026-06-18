#!/bin/sh
set -e

# Substitute $PORT in nginx config
envsubst '${PORT}' < /etc/nginx/nginx.conf > /etc/nginx/nginx.conf

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g 'daemon off;'
