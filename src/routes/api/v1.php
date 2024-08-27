<?php

use App\Models\User;

/*
 * API Router for 1 version
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace'  => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        'api.throttle',
        'cors',
    ],
    'limit'      => env('ROUTE_LIMIT'),
    'expires'    => env('ROUTE_LIMIT_EXPIRES'),
    'prefix'     => 'v1',
], function ($api) {
    # region Auth
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('register', ['uses' => 'AuthController@register']);

        $api->get('verify', ['uses' => 'AuthController@verify']);

        $api->post('login', ['uses' => 'AuthController@login', 'middleware' => 'throttle:100,10']);

        $api->delete('logout', ['uses' => 'AuthController@logout']);

        $api->post('test', ['uses' => 'AuthController@test', 'middleware' => ['api.auth']]);
    });
    # endregion Auth

    # region Tips
    $api->group(['prefix' => 'olx', 'middleware' => ['role:' . User::ROLE_USER]], function ($api) {
        $api->post('create', ['uses' => 'OlxController@createSubscribe']);
    });
});
