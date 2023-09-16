## Setup `SFVEL-API` lần đầu tiên Clone

- Run `composer install & npm install` để cài đặt các package từ bên thứ 3.
- Copy `.env` từ `.env.example` và sửa để kết nối CSDL, URL...
- Run : ```php artisan app:base-artisan``` để artisan base.

## Kiến trúc HMVC

>[Giới thiệu kiến trúc HMVC và cách triển khai](hmvc.md)

## List thư viện

Trước khi sử dụng bộ core này bạn cần biết project sử dụng những gì.

- [Laravel 10](https://laravel.com/docs/10.x)
- [Laravel-permission](https://spatie.be/docs/laravel-permission)
- [Jwt-Auth](https://jwt-auth.readthedocs.io/en/develop/)
- [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)
- [Telescope](https://laravel.com/docs/10.x/telescope)
- [Horizon](https://laravel.com/docs/10.x/horizon)
- [Predis](https://laravel.com/docs/10.x/redis)
- [Supervisor](https://laravel.com/docs/10.x/queues#supervisor-configuration)
- [PHP-CS-Fixer](https://laravel.com/docs/10.x/pint)
   + >command fix code style. run: `./vendor/bin/pint`

------------------------
## Command

- Clear log & telescope...:
  + ```truncate -s 0 storage/logs/laravel.log;php artisan telescope:clear;php artisan cache:clear;php artisan queue:flush;php artisan queue:clear redis```

------------------------
