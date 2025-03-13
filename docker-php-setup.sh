apt update
apt install -y cron
apt install -y unzip
apt install -y curl
apt install -y git
apt install -y libpq-dev
apt install -y libonig-dev
apt install -y mariadb-client
docker-php-ext-install pdo pdo_mysql mbstring
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
cp crontab /etc/cron.d/laravel-cron
chmod +x /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron
service cron start
service php-fpm restart