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

$api = app('Dingo\Api\Routing\Router');




$api->version('v1', function ($api) {  
    $api->get('/', function () use ($api) {
        return "oi"; // not sure what you wanted to do here but this isn't valid...
    });
    $api->group(['prefix' => 'api'], function ($api) {
        $api->group(['prefix' => 'v1'], function($api) {
            $api->group(['prefix' => 'types'], function($api) {
                $api->post('/','App\Http\Controllers\TypeController@create');
                $api->get('/{id}','App\Http\Controllers\TypeController@show');
                $api->post('/{id}','App\Http\Controllers\TypeController@update');
                $api->delete('/{id}','App\Http\Controllers\TypeController@delete');
                $api->get('/','App\Http\Controllers\TypeController@index');
            });

            $api->group(['prefix' => 'stationSells'], function ($api) {
                $api->post('/','App\Http\Controllers\StationSellController@create');
                $api->get('/{id}','App\Http\Controllers\StationSellController@show');
                $api->post('/{id}','App\Http\Controllers\StationSellController@update');
                $api->delete('/{id}','App\Http\Controllers\StationSellController@delete');
                $api->get('/','App\Http\Controllers\StationSellController@index');
                $api->get('/type/{name}', 'App\Http\Controllers\StationSellController@searchTypeName');
            });
        }); 
    });    
});


// $router->group(['prefix' => 'api/v1'], function($router)
// {
    
    

// });

// $api->get('/', function () use ($api) {
//     return $api->app->version();
// });

// $api->post('/auth/login', 'AuthController@postLogin');
