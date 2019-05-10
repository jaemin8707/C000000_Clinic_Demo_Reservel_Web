cd ~/laravel_project/Reservel_otake/reservel-web
php artisan down #メンテモード
cd ~/laravel_project/Reservel_otake/reservel-admin
php artisan down #メンテモード

cd  ~/laravel_project/
cp -r  ~/laravel_project/Reservel_otake ./Reservel_otake_`date +\%Y\%m\%d%H%M`

cd  ~/laravel_project/Reservel_otake
git pull 

cd  ~/laravel_project/Reservel_otake/reservel-web
php artisan up #通常モード
cd  ~/laravel_project/Reservel_otake/reservel-admin
php artisan up #通常???ード


