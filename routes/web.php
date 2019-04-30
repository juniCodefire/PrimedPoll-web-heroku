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

//****************Users Routes**************** */

$router->post('/api/register', 'SignupController@register');
$router->post('api/register/verify', 'VerifyUserController@verifyUser');

$router->post('api/user/login', 'SignInController@userLogin');

// This controller completes user registration
$router->put('api/complete', 'CompleteRegistrationController@update');

//Tino
$router->post('api/password/reset', 'PasswordController@resetpassword');

$router->put('api/password/change', 'ChangePasswordController@updatepassword');

//****************End Routes****************** */

//****************Admin Custom Routes**************** */
$router->post('api/admin/access/login', 'SignInController@adminLogin');
$router->get('api/user/show/all/interest', 'ShowIntrestController@index');
//****************End Routes****************** */


$router->group(['middleware' => 'jwt.auth', 'prefix' => 'api'], function() use ($router)
{
    //Put you controller inside this block for authorization or create a new ground with new prefix
    //This is the Users Public route
    //************************************** */
    
    //************************************** */

    //This is the Admin Private route(Work here with caution)
    //************************************* */
    $router->get('admin/profile', 'AdminProfileController@adminData');
    $router->put('admin/change/password', 'AdminProfileController@updatePass');
    $router->post('admin/create/intrest', 'CreateIntrestController@store');
    $router->get('admin/show/all/intrest', 'CreateIntrestController@index');
    $router->put('admin/edit/intrest/{intrest_id}', 'CreateIntrestController@update');
    $router->delete('admin/delete/intrest/{intrest_id}', 'CreateIntrestController@destroy');
    //************************************** */
    
    //Iro
      $router->put('/edit', 'EditProfileController@editprofile');
    $router->post('/upload', 'EditProfileController@uploadImage');
  
  //Tino
   $router->post('/polls/create', 'PollController@createpoll');



    //francise 
    $router->get('/profile', 'ProfileController@profile');

});
