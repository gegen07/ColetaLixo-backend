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

$router->group(['prefix' => 'api/v1'], function($router)
{
    $router->post('types','TypeController@create');
    $router->get('types/{id}','TypeController@show');
    $router->post('types/{id}','TypeController@update');
	$router->delete('types/{id}','TypeController@delete');
    $router->get('types','TypeController@index');

    /**
     * routes for StationSell
     */
    $router->post('stationSells','StationSellController@create');
    $router->get('stationSells','StationSellController@show');
    $router->post('stationSells/{id}','StationSellController@update');
	$router->delete('stationSells/{id}','StationSellController@delete');
    $router->get('stationSells','StationSellController@index');
    $router->get('stationSells/type/{name}', 'StationSellController@searchTypeName');
    

});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->post('/auth/login', 'AuthController@postLogin');
