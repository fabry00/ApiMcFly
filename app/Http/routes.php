<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', function () {
    //return view('welcome');
    return view('index');
});



Route::group(['prefix' => 'api'], function() {
    // Authentication route
    Route::post('authenticate', 'JwtAuthenticateController@authenticate', ['only' => ['index']]);
    Route::get('authenticate/user', 'JwtAuthenticateController@getAuthenticatedUser');
});



// API route group that we need to protect
// We are just saying that we need the user to be an admin or have the
// create-users permissions before they can access the routes in this group.
Route::group(['prefix' => 'admin', 'middleware' => ['ability:admin,create-users']], function() {
    // Protected route
    // Entrust already has a EntrutAbility that can be seen here but the
    // problem is that it works with sessions and not tokens.
    // What we can do is extend the JWT's middleware to include Entrust's and
    // work with a token, not session.
    // php artisan make:middleware TokenEntrustAbility
    Route::get('users', 'JwtAuthenticateController@index');

    // Route to create a new role
    Route::post('role', 'JwtAuthenticateController@createRole');
    // Route to create a new permission
    Route::post('permission', 'JwtAuthenticateController@createPermission');
    // Route to assign role to user
    Route::post('assign-role', 'JwtAuthenticateController@assignRole');
    // Route to attach permission to a role
    Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');
});
