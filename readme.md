# backend install

composer install

cp .env.example .env

TODO edit .env configuration for database

php artisan migrate
php artisan import:csv

cd ./webapp

npm install

npm run build

redirect all api.<yourdomain> to public/index.php
and all <yourdomain> to public/index.html
