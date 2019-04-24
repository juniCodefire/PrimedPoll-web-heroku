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


$router->group(['middleware' => 'auth:api', 'prefix' => 'api'], function() use ($router)
{
    //Put you controller inside this block for authrization or create a new ground with new prefix

});

$router->post('/api/register', 'SignupController@register');

$router->get('api/register/verify/{verifyToken}', 'VerifyMailController@verify');


$router->post('api/login', 'SignInController@authenticate');


$router->put('api/update', 'UpdateController@update');

//Tino
$router->post('api/password/reset', 'PasswordController@resetpassword');

$router->put('api/password/change', 'ChangePasswordController@updatepassword');

$router->post('api/polls/create', 'PollController@createpoll');



