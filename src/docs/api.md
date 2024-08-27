# Правила написание АПИ на основе фреймворка Lumen

Данный файл хранит в себе основные требования
дял построение полноценного проекта на основе Lumen.

Основная сборка проекта находится по ссылке [lumen-engin](https://bitbucket.org/vjet_developers/lumen-engine)


Models
=============================
* [Console](#console)
* [Exception](#exception)
* [Middleware](#middleware)
* [Providers](#providers)
* [Repositories](#repositories)
* [Services](#services)
* [Controllers](#controllers)
* [Routers](#routers)
* [Models](#models)
* [Database](#database)
    * [Factories](#factories)
    * [Migrations](#migrations)
    * [Seeders](#seeders)

# Console
Lumen предоставляет возможность создавать отдельные консольные команды.  
создавать их можно с помощью
```
php artisan make:console Example Console
```

В созданном классе необходимо задать сигнатуру метода,
указать обязательные и не обязательные параметры.

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSomething extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'example:command
        {firstName : The first name of the user.}
        {lastName=Doe : The last name of the user}
        {--a|age=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments = $this->arguments();
        $options = $this->options();
        
        var_dump($arguments, $options);die();
    }
}
```

Чтобы определить аргументы метода необходимо обернуть их в `{}`
```
{firstName : The first name of the user.}
```

Для опций необходимо в начале поставить `--`, так же если поставить в конце опции `=*`,
то опцию в запросе можно будет дублировать и она будет принимать параментры как массив.
```
{--a|age=*}
```

Чтобы обратиться к обязательным аргументам необходимо вызвать
```
$this->arguments();
```

Чтобы обратиться к опицям необходимо вызвать
```
$this->options();
```

Example

```
php artisan example:command Jon Doe --age=123
```


# Exception
Единственный способ, чтобы вернуть ошибку выполнение скрипта в
вызываемый метод - это использовать созданную ранее и создать свою
сущность типа Exception.

```php
<?php

namespace App\Exceptions;

class InvalidPasswordException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Password incorrect Exception';

    /**
     * @var int
     */
    protected $code = 401;

}
```
Модели типа Exception должны находить в `app/Exceptions`
-  Желательным условием для создания модели Exception
    1. Определять текстовое сообщение для ошибки
    2. Определять код ошибки


Благодаря пакету  [flipboxstudio/lumen-generator](https://github.com/flipboxstudio/lumen-generator)
есть возможность создавать Exception командой

```
php artisan make:exception ExampleException
```



# Middleware
Промежуточная предоставляет удобный механизм
для проверки и фильтрации HTTP-запросов, поступающих в ваше приложение.

Например,  аутентифицирован ли пользователь вашего приложения.
Если пользователь не аутентифицирован, промежуточное ПО перенаправит
пользователя на экран входа в систему вашего приложения. Однако, если пользователь
аутентифицирован, промежуточное программное обеспечение позволит запросу
продвинуться дальше в приложение.

Располагать Middleware необходимо в ```app/Http/Middleware```

Благодаря пакету  [flipboxstudio/lumen-generator](https://github.com/flipboxstudio/lumen-generator)
есть возможность создавать Middleware командой

```
php artisan make:middleware ExampleMiddleware
```

# Providers

Это начальное место загрузки и работы приложения. Все дополнительные зависимости
всегда загружаются через ServiceProviders.

Для включение какого-либо провайдера в приложение нужно определить его в файле `bootstrap/app.php`
```php
$app->register(App\Providers\AppServiceProvider::class);
```
Database, Redis, JWT, Auth, Events, Listeners and etc. для всего нужно определить свой
ServiceProvider


Распологать провайдеры нужно в директории ```app/Providers```
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```
ServiceProvider Необходимо использовать для того, чтобы не пропускать пользователя дальше в приложение,
если есть такая необходимость.

Благодаря пакету  [flipboxstudio/lumen-generator](https://github.com/flipboxstudio/lumen-generator)
есть возможность создавать Providers командой

```
php artisan make:provider ExampleServiceProvider
```

# Repositories
Это компонент приложения который создан для инкапсуляции логики, необходимой для доступа к источникам данных.
Модель создана специально чтобы органичить логику запросов к базе данных, от самой логики работы с этими данными.

Располагать Repositories необходимо в ```app/Repositories```

```php
<?php

namespace App\Repositories;

use App\Models\User;

final class UserRepository
{
    /**
     * Finds an user by the given email
     *
     * @param string $email
     *
     * @return null|User
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', '=', $email)->first();
    }
}
```
Таким образом обновлениние или исправление кода приложение
будет происходить безболезненно для логических моделей.

# Services
Это слой, который хранит в себе всю логику работы модели. В данном паттерне необходимо
все методы, валидации и обработки полей модели выносить в сервис. Таким образом идет полная инкапсуляция логики
приложения от ее данных([Repositories](#repositories)) и самой модели([Models](#models))

В каждом сервисе по умолчанию должна быть определена Repository для доступа к данным Model

Располагать Services необходимо в ```app/Services```

```php
<?php

namespace App\Services;

use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserNotFoundException;
use App\Models\Authorization;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class UserServices
{
    public const EMAIL_VALIDATION_RULE    = 'required|email|max:128';
    public const PASSWORD_VALIDATION_RULE = 'required|max:255';

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Constructor of the class
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Gets the manager repository
     *
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }

    /**
     * Tries to authenticate an user from the given data
     *
     * @param Request $request
     *
     * @return Authorization
     *
     * @throws ValidationException
     *         If the given data isn't valid.
     *
     * @throws InvalidPasswordException
     *         If the given password isn't correct.
     */
    public function authenticateByArray(Request $request): Authorization
    {
        $validator = Validator::make($request->all(), [
            'email'    => self::EMAIL_VALIDATION_RULE,
            'password' => self::PASSWORD_VALIDATION_RULE,
        ]);

        $validator->validate();

        $credentials = $request->only('email', 'password');

        // 401 if verification fails
        if (! ($token = auth()->attempt($credentials))) {
            throw new UserNotFoundException();
        }

        $authorization = new Authorization($token);

        return $authorization;
    }

}
```
Все данные для работы с моделью Service должен получать только через связанную с этой моделью Repository.

Service является ServiceProvider для модели и выполняет всю логику внутри.

# Controllers
Необходим только для передачи данных между клиентом и самим приложением. Контроллер не должен нести логику
самого приложение - его основная задача отдать данные в Service для дальнейшей обработки.

Располагать Controllers необходимо в ```app/Http/Controllers```

```php
<?php
  
namespace App\Http\Controllers\Api\V1;

use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserNotFoundException;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
   private UserServices $userService;

   /**
    * Constructor of the class
    *
    * @param UserServices $userService
    */
   public function __construct(UserServices $userService)
   {
       $this->userService = $userService;
   }

   /**
    * @api {post} /authorizations Create JWT token
    * @apiDescription Create JWT token
    * @apiGroup Auth
    * @apiPermission none
    * @apiParam {Email} email     mailbox
    * @apiParam {String} password  password
    * @apiVersion 0.1.0
    * @apiSuccessExample {json} Success-Response:
    *     HTTP/1.1 201 Created
    *     {
    *          "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbHVtZW4tYXBpLWRlbW8uZGV1L2FwaS9hdXRob3JpemF0aW9ucyIsImlhdCI6MTQ4Mzk3NTY5MywiZXhwIjoxNDg5MTU5NjkzLCJuYmYiOjE0ODM5NzU2OTMsImp0aSI6ImViNzAwZDM1MGIxNzM5Y2E5ZjhhNDk4NGMzODcxMWZjIiwic3ViIjo1M30.hdny6T031vVmyWlmnd2aUr4IVM9rm2Wchxg5RX_SDpM",
    *          "expired_at": "2017-03-10 15:28:13",
    *          "refresh_expired_at": "2017-01-23 15:28:13"
    *     }
    * @apiErrorExample {json} Error-Response:
    *     HTTP/1.1 401
    *     {
    *       "error": "User interface password error"
    *     }
    */
   public function store(Request $request)
   {
       try {
           $token = $this->userService->authenticateByArray($request);
       }
       catch (UserNotFoundException|InvalidPasswordException|ValidationException $e) {
           return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
       }

       return new JsonResponse($token->toArray(), 201);
   }
}
```

В каждом контроллере по умолчанию должен быть определен
сервис для работы для доступа к функционалу модели.

```
   private UserServices $userService;

   /**
    * Constructor of the class
    *
    * @param UserServices $userService
    */
   public function __construct(UserServices $userService)
   {
       $this->userService = $userService;
   }
```

Благодаря использования отдельной библиотеки [Apidoc Generator](https://www.npmjs.com/package/apidoc)
Каждый метод котроллера должен быть задокументирован. Далее это описание будет использоваться для генерации
Документации каждого отдельного метода.

```php
   /**
    * @api {post} /authorizations Create JWT token
    * @apiDescription Create JWT token
    * @apiGroup Auth
    * @apiPermission none
    * @apiParam {Email} email     mailbox
    * @apiParam {String} password  password
    * @apiVersion 0.1.0
    * @apiSuccessExample {json} Success-Response:
    *     HTTP/1.1 201 Created
    *     {
    *          "token": "eyJ0eXAiOiJKV1QiLC",
    *          "expired_at": "2017-03-10 15:28:13",
    *          "refresh_expired_at": "2017-01-23 15:28:13"
    *     }
    * @apiErrorExample {json} Error-Response:
    *     HTTP/1.1 401
    *     {
    *       "error": "User interface password error"
    *     }
    */
```

# Routers
Вся маршрутизация по приложение осуществляется с помощью файлов маршрутов которые располагаются в
в директории ```routers``` в корне приложения.

Для того, чтобы определить свой отдельный роутер необходимо зарегистрировать его в ```bootstrap/app.php```
в конце файла. Нам же определяется `namespace` для всех контроллеров и подключается сам файл для определения роутинга.

```php
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/web.php';
});
```

Все запросы должны быть совмещенны в группы. Что позволит мининизировать код
и удобнее работать с роутингом.

```php
    /** @var \Laravel\Lumen\Routing\Router $router */
    // Example
    $router->group(
        [
            'prefix' => 'example',
            'middleware' => [
                'cors'
            ]
        ], function ($router) {
        /** @var \Laravel\Lumen\Routing\Router $router */
        $router->post('', [
            'as'   => 'example.store',
            'uses' => 'ExampleController@store',
        ]);
    });
```
* Параметр `prefix` отвечает за группу. Он определяет путь по умолчанию для группы запросов.
* Параметр `middleware` отвечает за подключенные к группе запросов [Middleware](#middleware)

Каждый запрос может быть определен одним типом (GET, POST, PUT, DEL)
и хранит путь к выбранному методу в контроллере `ExampleController@store`

Определить отдельный роут можно и по его уникальному имени `'as'   => 'example.store'`, что позволит
использовать его имя для редиректов или валидации на доступы.

# Models
Созданна ТОЛЬКО для определения данных.  
МОДЕЛЬ НЕ ДОЛЖНА ХРАНИТЬ НИКАКИХ ЛОГИЧЕСКИХ МЕТОДОВ.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'example';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'delayed' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'text',
        'status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];
}
```

Laravel имеет возможность создавать модели одновременно со всеми необходимыми связанными сущностями.
```
# Generate a model and a FlightFactory class...
php artisan make:model Flight --factory
php artisan make:model Flight -f

# Generate a model and a FlightSeeder class...
php artisan make:model Flight --seed
php artisan make:model Flight -s

# Generate a model and a FlightController class...
php artisan make:model Flight --controller
php artisan make:model Flight -c

# Generate a model and a migration, factory, seeder, and controller...
php artisan make:model Flight -mfsc

# Generate a pivot model...
php artisan make:model Member --pivot
```

# Database

### Migrations
Все новые проекты созданные на основании Lumen должны иметь полный стек миграций для  полного поднятия проекта.

**!!!! ВАЖНО !!!!!**
Пока проект находится на стадии Dev or Stage, не обязательно создавать дополнительные миграции для добавления, изменения,
удаления полей из DB. С помощью рефреша возможно удалить всю базу данных и создать ее заново.
Это позволит совершать обновление базы данных без потери времени на добавление отдельных полей.

На текущий момент мы отказывается от `UnixTime` полей в базе данных и по умолчанию начинаем использовать
`timestamp` в Postgresql. Все миграции должны создаваться исходя из этого требования.

Все таблицы должны быть созданы исходя из правил нормализации.
Таблицы должны быть свзяна между собой с помощью `foreign key`.

```
php artisan migrate:refresh #regenerate all tables
```

Чтобы создать миграцию необходимо выполнить команду.
```
php artisan make:migration migration_name 
```

Чтобы выполнить миграцию
```
php artisan migrate
```

Чтобы выполнить миграцию дновременно с заполнением тестовых данных
```
php artisan migrate --seed
```

Чтобы выполнить миграцию с обновлением базы и дновременно с заполнением тестовых данных
```
php artisan migrate:refresh --seed
```

### Factories
Factories необходимы для заполнения таблиц тестовыми данными, на стадии разработки - ЭТО ОБЯЗАТЕЛЬНАЯ ПРОЦЕДУРА.
Это часть тестирования систеым, которое позволит проверять сразу модели и связи между ними.

Для каждой модели фабрика должна быть обязательно заполнена. Чтобы иметь возможнолсть создавать тестовые данные.
```php
<?php
   
namespace Database\Factories;

use App\Models\Example;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExampleFactory extends Factory
{
   /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
   protected $model = Example::class;

   /**
    * Define the model's default state.
    *
    * @return array
    */
   public function definition()
   {
       static $password;

       return [
           'title' => $this->faker->title,
           'description'  => $this->faker->text(400),
           'email'      => $this->faker->email,
           'status'     => rand(0, 1),
           'password'   => $password ?: $password = app('hash')->make(123456),
           'created_at' => Carbon::now(),
           'updated_at' => Carbon::now(),
       ];
   }
}
```

Чтобы связать модель и ее фабрику в модели необходимо использовать `trait HasFactory`, что возволит обращаться
к фабрике модели напрямую из самой модели.

```php
$example = Example::factory();
```

### Seeders
Seeders основная часть минимального тестирования проекта на стадии разработки.
Seeders может создержать как и статические данные, которые задал разработчик, так и рандомые данные созданные с помощью
Factory

```php
$user = User::factory()->create([
    'first_name'      => 'ADMIN',
    'email'           => 'admin@gmail.com',
    'password'        => app('hash')->make("admin12345"),
    'status'          => 1,
]);

$users = User::factory()->count(250)->create();
```
Seeder не должен хранить рандомную логику моделей. Все связи и зависимости между группами моделей долнжы быть выполнены
с учетом требований проекта. 
