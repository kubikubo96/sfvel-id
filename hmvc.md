Tài liệu tổng hợp về cách triển khai và giao tiếp giữa các module trong mô hình HMVC trong Laravel

### I. Giới thiệu chung

Mô hình HMVC (Hierarchical Model-View-Controller) là một phiên bản mở rộng của mô hình MVC truyền thống. Nó cung cấp cấu trúc mã nguồn rõ ràng hơn và làm cho việc mở rộng ứng dụng trở nên dễ dàng hơn.

1. Sử dụng thư viện [`nwidart/laravel-modules`](https://docs.laravelmodules.com/v10/introduction) để quản lý và tổ chức module trong Laravel.
2. Để giao tiếp giữa các module, áp dụng 'Dependency Injection' dựa trên khái niệm ['Contract'](https://laravel.com/docs/10.x/contracts#main-content) và ['Facades'](https://laravel.com/docs/10.x/facades) của Laravel.

Các triển khai này có các ưu điểm như sau:
- Tính modularity: Cấu trúc này khuyến khích các module hoạt động độc lập, giúp dễ dàng thêm, sửa, xóa module mà không ảnh hưởng đến module khác. Nó giúp tăng tính tái sử dụng và giảm sự phụ thuộc giữa các module.
- Đóng gói tốt: Mỗi module chỉ quan tâm về nghiệp vụ trong phạm vi của nó, giúp cho việc quản lý và phát triển mã nguồn trở nên dễ dàng hơn.
- Dependency Injection: Cách triển khai này khuyến khích sử dụng Dependency Injection, giúp kiểm soát phụ thuộc tốt hơn và tăng khả năng kiểm thử ứng dụng.

### II. Các bước thực hiện:

1. **Tạo Module**: Sử dụng lệnh 'php artisan module:make' sau khi cài đặt thư viện nwidart/laravel-modules.

2. **Tạo Service trong Module**: Tạo ra một service mới trong module tương ứng với chức năng cần thực hiện.

3. **Tạo Contracts và Facades cho Service trong Module**:
    - Tạo ra Contract cho Service, chính là một interface định nghĩa các phương thức cần thiết của Service.
    - Tạo ra Facade cho Service.
    - Khai báo 'binding' giữa Contract và Service trong `AppServiceProvider` hoặc `ServiceProvider` khác ở mức độ ứng dụng (global app).

4. **Sử dụng Service trong Module khác**:
    - Để sử dụng Service từ một module khác, resolve instance của Service thông qua Service Contract hoặc sử dụng Service Facade.

> **Tìm hiểu thêm trong tài liệu của nwidart/laravel-modules:** 
> https://docs.laravelmodules.com/v10/introduction




#### Ví dụ với UserService trong User module:
1. Tạo module mới
`php artisan module:make User`

- Một module sẽ có cấu trúc cơ bản như sau:
```
...
Modules/User
├── Config
├── Console
├── Contracts
├── Database
│   ├── factories
│   ├── Migrations
│   └── Seeders
├── Http
│   ├── Controllers
│   ├── Middleware
│   └── Requests
├── Models
├── Providers
│   ...
├── Repositories
├── Resources
│   ├── assets
│   │   ...
│   ├── lang
│   └── views
│       ...
├── Routes
│   ...
├── Services
├── Support
│   ├── Facades
│   ├── Helper.php
│   └── Traits
├── Tests
│   ...
```

2. Tạo service trong module

- Tạo một class UserService trong thư mục Services có hàm getUserInfo(int $id).
```bash
php artisan module:make-service UserService User 
```
- Trong UserService.php:
```php
namespace Modules\User\Services;

class UserService
{
    public function getUserInfo(int $id)
    {
        // Lấy thông tin người dùng theo id
    }
}
```

3. Khai báo Contract và Facade cho service
- Tạo một interface IUserService trong thư mục Contracts:
```php
namespace Modules\User\Contracts;

interface IUserService
{
    public function getUserInfo(int $id);
}
```

- Tạo một class UserServiceFacade trong thư mục Facades:
```php
namespace Modules\User\Facades;

use Illuminate\Support\Facades\Facade;

class UserServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'userService';
    }
}
```

4. Khai báo [binding](https://laravel.com/docs/10.x/container#binding-basics) ở global app

- Trong file AppServiceProvider.php hoặc một ServiceProvider khác:
```php
use Modules\User\Contracts\IUserService;
use Modules\User\Services\UserService;

public function register()
{
    $this->app->singleton(IUserService::class, UserService::class); // Binding IUserService đến UserService
}
```

5. Cách dùng service ở module khác
- Sử dụng Dependency Injection:
```php
// Modules/ModuleB/Controllers/ModuleBController.php
namespace Modules\ModuleB\Controllers;

use Modules\User\Contracts\IUserService;

class ModuleBController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function someMethod(int $userId)
    {
        $userInfo = $this->userService->getUserInfo($userId);
    }
}
```

- Sử dụng UserServiceFacade:
```php
// Modules/ModuleB/Controllers/ModuleBController.php
namespace Modules\ModuleB\Controllers;

use Modules\User\Facades\UserServiceFacade;

class ModuleBController extends Controller
{
    public function someMethod(int $userId)
    {
        $userInfo = UserServiceFacade::getUserInfo($userId);
    }
}
```

Cấu trúc đầy đủ của module User sẽ như sau:
```
Modules/User
├── composer.json
├── Config
│   └── config.php
├── Console
├── Contracts
│   └── UserService.php
├── Database
│   ├── factories
│   ├── Migrations
│   └── Seeders
│       └── UserDatabaseSeeder.php
├── Http
│   ├── Controllers
│   │    └── UserController.php
│   ├── Middleware
│   └── Requests
├── Models
├── module.json
├── package.json
├── Providers
│   ├── RouteServiceProvider.php
│   └── UserServiceProvider.php
├── Repositories
├── Resources
│   ├── assets
│   │   ├── js
│   │   │   └── app.js
│   │   └── sass
│   │       └── app.scss
│   ├── lang
│   └── views
│       ├── index.blade.php
│       └── layouts
│           └── master.blade.php
├── Routes
│   ├── api.php
│   └── web.php
├── Services
│   └── UserService.php
├── Support
│   ├── Facades
│   │     └── UserService.php
│   ├── Helper.php
│   └── Traits
├── Tests
│   ├── Feature
│   └── Unit
└── vite.config.js
```
