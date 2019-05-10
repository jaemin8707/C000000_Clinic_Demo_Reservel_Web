#!/bin/sh
cd `dirname $0`
echo "reservel-admin"
cd ./reservel-admin
./clearcache.sh
echo "reservel-web"
cd ../reservel-web
./clearcache.sh

