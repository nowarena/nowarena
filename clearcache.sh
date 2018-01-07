rm -fr storage/framework/views/
php alrtisan view:clear
php artisan config:cache
php artisan cache:clear
php artisan clear-compiled 
composer dump-autoload
php artisan optimize

