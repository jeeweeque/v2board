git fetch --all && git reset --hard origin/master && git pull origin master
rm -rf composer.lock composer.phar
wget https://github.com/composer/composer/releases/latest/download/composer.phar -O composer.phar
composer require league/omnipay:* leonardjke/omnipay-qiwi
php composer.phar update -vvv
php artisan v2board:update
php artisan config:cache
