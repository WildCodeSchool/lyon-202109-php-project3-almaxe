#!/bin/sh
set -e

php composer.phar update

## Symfony configuration
php bin/console doctrine:database:create --if-not-exists --quiet --no-interaction
php bin/console doctrine:migrations:migrate --verbose --no-interaction --allow-no-migration
if [ ${APP_ENV} != "prod" ]; then
  php bin/console doctrine:fixtures:load --quiet --no-interaction --no-debug
fi

php bin/console cache:clear
php bin/console cache:warmup

chmod -R 777 /var/www/var
chmod -R 777 /var/www/public

## server config
php-fpm -D &
nginx -g "daemon off;"

php bin/console get:amazon --quiet --no-interaction --no-debug
php bin/console app:scrap-home --quiet --no-interaction --no-debug
php bin/console scrap:mdm --quiet --no-interaction --no-debug
