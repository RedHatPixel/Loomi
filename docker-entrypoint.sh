#!/bin/sh
set -e

# Start nginx in foreground
nginx -g 'daemon off;' &

# Start PHP-FPM in foreground (keeps container alive)
php-fpm
