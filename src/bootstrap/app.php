<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../helpers.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/


/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

//$app->middleware([
//    'cors' => \App\Http\Middleware\LumenCors::class
//]);

$app->routeMiddleware([
    'cors'       => \App\Http\Middleware\LumenCors::class,
    'auth'       => App\Http\Middleware\Authenticate::class,
    'throttle'   => \App\Http\Middleware\ThrottleRequests::class,
    'permission' => Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role'       => Spatie\Permission\Middlewares\RoleMiddleware::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);

#Mail
$app->register(Illuminate\Mail\MailServiceProvider::class);
$app->configure('mail');
$app->withFacades();

#Queue
$app->configure('queue');
$app->register(Illuminate\Queue\QueueServiceProvider::class);

#JWT
//Add config file for JWT
$app->configure('jwt');
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

#Dingo
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);

$app->configure('permission');
$app->alias('cache', \Illuminate\Cache\CacheManager::class);
$app->register(Spatie\Permission\PermissionServiceProvider::class);

# redis
$app->configure('database');
$app->register(\Illuminate\Redis\RedisServiceProvider::class);

// Injecting auth
$app->singleton(Illuminate\Auth\AuthManager::class, function ($app) {
    return $app->make('auth');
});


if (class_exists(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class)) {
    $app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
}

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

// $app->router->group([
//     'namespace' => 'App\Http\Controllers',
// ], function ($router) {
//     require __DIR__ . '/../routes/web.php';
// });

$app->router->group([

], function ($api) {
    require __DIR__ . '/../routes/api/v1.php';
    require __DIR__ . '/../routes/web.php';
});

return $app;
