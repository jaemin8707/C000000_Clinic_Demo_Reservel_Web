#!/bin/sh
cd `dirname $0`

echo "reservel-adminテスト"
cd ./reservel-admin
./clearcache.sh 
vendor/bin/phpunit

echo "reservel-webテスト"
cd ../reservel-web
./clearcache.sh 
vendor/bin/phpunit

cd `dirname $0`
