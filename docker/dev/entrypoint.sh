#!/bin/bash

# Ganti APP_LOCALE=id di file .env jika belum
if grep -q "^APP_LOCALE=" .env; then
    sed -i 's/^APP_LOCALE=.*/APP_LOCALE=id/' .env
else
    echo "APP_LOCALE=id" >> .env
fi

# Jalankan perintah default untuk PHP-FPM
exec php-fpm

exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
