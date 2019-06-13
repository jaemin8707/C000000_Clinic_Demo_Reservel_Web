cd  ~/laravel_project/Reservel_otake/
./clearcache.sh

cd ~/laravel_project/Reservel_otake/reservel-web
php artisan down #メンテモード
cd ~/laravel_project/Reservel_otake/reservel-admin
php artisan down #メンテモード

echo "Backup start"
cd  ~/laravel_project/
cp -r  ~/laravel_project/Reservel_otake ./Reservel_otake_`date +\%Y\%m\%d%H%M`

cd  ~/laravel_project/Reservel_otake
echo "pull Start"
git pull 
echo "git pull done."

cd  ~/laravel_project/Reservel_otake/reservel-web
echo "Migrate start"
php artisan migrate --env=prod

cd  ~/laravel_project/Reservel_otake/reservel-web
php artisan up #通常モード
cd  ~/laravel_project/Reservel_otake/reservel-admin
php artisan up #通常???ード


