#!/bin/bash
set -e

echo "Docker setup is running"

/usr/bin/mysqld_safe --skip-syslog &

until [ -x /var/run/mysqld/mysqld.sock ]
do
    sleep 1
done

cd /var/www/html/

# Clear cache avoiding permission problems in the new container
rm -rf app/cache
rm app/config/parameters.yml

# Classic symfony install
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:create

mysqladmin shutdown

sed -i -e '/Forbidden/,+1d' /var/www/html/web/app_dev.php
chmod -R 777 app/cache
chmod -R 777 app/logs

echo "Docker setup has ended"

echo "--------------"
echo "server address"
http="http://"
echo "--------------"
echo $http$(ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}')