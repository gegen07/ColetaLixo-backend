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
    $router->post('types/{id}','TypeController@update');
	$router->delete('types/{id}','TypeController@delete');
    $router->get('types','TypeController@index');

    /**
     * routes for StationSell
     */
    $router->post('stationSells','StationSellController@create');
    $router->post('stationSells/{id}','StationSellController@update');
	$router->delete('stationSells/{id}','StationSellController@delete');
    $router->get('stationSells','StationSellController@index');
    $router->get('stationSells/type/{type}', 'StationSellController@searchTypeName');
    

});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->post('/auth/login', 'AuthController@postLogin');
