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

    $api->group(['prefix' => 'api/v1'], function ($api) {
        $api->post('login','App\Http\Controllers\AuthController@authenticate');
        $api->post('register', 'App\Http\Controllers\AuthController@register');

        $api->group(['prefix' => 'password'], function($api) {
          $api->post('/email', 'App\Http\Controllers\PasswordController@postEmail');
          $api->post('/reset/{token}',['as' => 'passwords.reset', 'uses' => 'App\Http\Controllers\PasswordController@postReset']);
        });
        
        $api->group(['middleware' => 'auth:api'], function($api) {
            $api->group(['prefix' => 'user'], function($api) {
                $api->get('/refresh', 'App\Http\Controllers\AuthController@refresh');
                $api->get('/station/{id}', 'App\Http\Controllers\AuthController@station');
                $api->get('/company/{id}', 'App\Http\Controllers\AuthController@company');
                $api->get('/profile', 'App\Http\Controllers\AuthController@me');
                $api->post('/logout', 'App\Http\Controllers\AuthController@logout');
            });

            $api->group(['prefix' => 'types'], function($api) {
                $api->post('/','App\Http\Controllers\TypeController@create');
                $api->get('/{id}','App\Http\Controllers\TypeController@show');
                $api->post('/{id}','App\Http\Controllers\TypeController@update');
                $api->delete('/{id}','App\Http\Controllers\TypeController@delete');
                $api->get('/','App\Http\Controllers\TypeController@index');
            });

            $api->group(['prefix' => 'stationSells'], function ($api) {
                $api->post('/','App\Http\Controllers\StationSellController@create');
                $api->get('/','App\Http\Controllers\StationSellController@index');
                $api->get('/{id}','App\Http\Controllers\StationSellController@show');
                $api->post('/{id}','App\Http\Controllers\StationSellController@update');
                $api->delete('/{id}','App\Http\Controllers\StationSellController@delete');
            });
            $api->group(['prefix' => 'companyBuys'], function($api) {
                $api->post('/','App\Http\Controllers\CompanyBuyController@create');
                $api->get('/{id}','App\Http\Controllers\CompanyBuyController@show');
                $api->delete('/{id}','App\Http\Controllers\CompanyBuyController@delete');
                $api->get('/','App\Http\Controllers\CompanyBuyController@index');
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
