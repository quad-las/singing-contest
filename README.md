Built with [Lumen](https://lumen.laravel.com/docs) and [Bootstrap](https://getbootstrap.com/)

## Set up
copy .env.example > .env

run command `composer install`

run command `php artisan key:generate`

create database and add corresponding values in .env -> DB_*

run migration: `php artisan migrate`

start the local server: `php artisan serve` and visit `localhost:8000` in your browser

to run tests, run command `./vendor/bin/phpunit`
