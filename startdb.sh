service mysql start
service apache2 start
cd /var/www/html/get-city-by-ip && composer install
cd /var/www/html/get-city-by-ip && npm install
cd /var/www/html/get-city-by-ip && npm run build
cd /var/www/html/get-city-by-ip && php bin/console lexik:jwt:generate-keypair
#Infinite waiting because tty: true is just not good enough for docker to keep container running
sh -c tail -f /dev/null
