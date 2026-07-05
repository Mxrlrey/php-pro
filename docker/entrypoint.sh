#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -d vendor ]; then
  composer install
fi

cd /var/www/html/public

exec "$@"
