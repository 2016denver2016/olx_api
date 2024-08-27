<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */

/*
 * Default application Router
 */

$router = app(Router::class);

$router->group(
    [
        'middleware' => [
            'cors'
        ]

    ], function ($router){


    $router->get('/', function () use ($router) {
        return $router->app->version();
    });


});

