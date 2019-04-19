cd ~/laravel_project/Reservel_akatsuki/reservel-web
php artisan down #メンテモード
cd ~/laravel_project/Reservel_akatsuki/reservel-admin
php artisan down #メンテモード

cd  ~/laravel_project/
cp -r  ~/laravel_project/Reservel_akatsuki ./Reservel_akatsuki_`date +\%Y\%m\%d%H%M`

cd  ~/laravel_project/Reservel_akatsuki
git pull 

cd  ~/laravel_project/Reservel_akatsuki/reservel-web
php artisan up #通常モード
cd  ~/laravel_project/Reservel_akatsuki/reservel-admin
php artisan up #通常???ード


