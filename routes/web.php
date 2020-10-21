<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/api/login', 'UserController@login');

$router->group(['prefix' => 'api'], function( $router){
    $router->post('user', 'UserController@store');
    $router->get('user/{id}', 'UserController@view');
    $router->delete('user/{id}', 'UserController@delete');
    $router->put('user/{id}', 'UserController@update');
    $router->post('user/{id}', 'UserController@destroy');
    $router->get('users', 'UserController@list');
});


