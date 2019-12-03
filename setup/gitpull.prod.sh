cd  ~/laravel_project/Reservel_shinka/
./clearcache.sh

cd ~/laravel_project/Reservel_shinka/reservel-web
php artisan down #メンテモード
cd ~/laravel_project/Reservel_shinka/reservel-admin
php artisan down #メンテモード

echo "Backup start"
cd  ~/laravel_project/
cp -r  ~/laravel_project/Reservel_shinka ./Reservel_shinka_`date +\%Y\%m\%d%H%M`

cd  ~/laravel_project/Reservel_shinka
echo "pull Start"
git pull 
echo "git pull done."

cd  ~/laravel_project/Reservel_shinka/reservel-web
echo "Migrate start"
php artisan migrate --env=prod

cd  ~/laravel_project/Reservel_shinka/reservel-web
php artisan up #通常モード
cd  ~/laravel_project/Reservel_shinka/reservel-admin
php artisan up #通常???ード


