#!/bin/bash
set -e

echo "Docker setup is running"

/usr/bin/mysqld_safe &

until [ -x /var/run/mysqld/mysqld.sock ]
do
    sleep 1
done

cd /var/www/html/

# Clear cache avoiding permission problems in the new container
rm -rf app/cache

# Classic symfony install
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:create

mysqladmin shutdown

sed -i -e '/Forbidden/,+1d' /var/www/html/web/app_dev.php
chmod -R 777 app/cache
chmod -R 777 app/logs

echo "Docker setup has ended"
