#!/usr/bin/env bash
# This setup we should use only for DEV/TEST mode
set -e
cd /var/www/application/

#------------------------------------Prepare DEV environment------------------------------------#
echo '> Install dependencies'
php -d memory_limit=-1 /usr/local/bin/composer install --working-dir /var/www/application
