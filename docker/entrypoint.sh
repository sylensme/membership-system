#!/bin/sh
set -eu

php artisan migrate --force
php artisan db:seed --class=ProductionAdminSeeder --force
php artisan optimize

exec apache2-foreground
