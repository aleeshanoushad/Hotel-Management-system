#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --ansi
fi

if [ ! -f database/database.sqlite ]; then
  mkdir -p database
  touch database/database.sqlite
fi

if [ ! -d vendor ] || [ ! -f composer.lock ]; then
  composer install --no-interaction --optimize-autoloader --ignore-platform-reqs
fi

php artisan migrate --force

exec apache2-foreground
