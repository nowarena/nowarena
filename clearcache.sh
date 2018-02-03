rm -fr storage/framework/views/*
rm -fr storage/framework/sessions/*
rm -fr bootstrap/cache/config.php
php artisan config:clear 
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan clear-compiled 
composer dump-autoload
php artisan optimize
