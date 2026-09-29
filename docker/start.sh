#!/bin/sh
# Container start-up on Render: cache config from the live environment
# variables, bring the database schema up to date, then serve.
set -e

cd /var/www/html

php artisan config:cache
php artisan view:cache
php artisan migrate --force

exec apache2-foreground
