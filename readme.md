# Installation
* create .env file from .env.example and add correct db params
* composer install
* php artisan key:generate
* npm install && npm run dev
* php artisan migrate
* php artisan storage:link
* php.ini config file: change 
    - upload_max_filesize = 100M
    - post_max_size = 200M
* php artisan serve

Open in browser http://127.0.0.1:8000

