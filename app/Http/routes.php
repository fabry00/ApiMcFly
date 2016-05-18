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


/**
* PUBLIC ROUTES
*/
Route::group(['prefix' => 'api/public'], function() {
    Route::get('authenticate/user', 'JwtAuthenticateController@getAuthenticatedUser');
    Route::get("notes/public", 'NotesController@publicNotes');
    Route::get("notes/count", 'NotesController@notesCount');
    Route::post('authenticate', 'JwtAuthenticateController@authenticate', ['only' => ['index']]);
});

/**
* USER AUTHENTICATED REQUIRED
*/
Route::group(['prefix' => 'api/auth', 'middleware' => ['before' => 'jwt.auth']], function() {
    Route::get("user/notes", 'UserController@userNotes');
    Route::get("user/favnotes", 'UserController@userFavNotes');
    Route::get("notes/public", 'NotesController@publicNotesWithUserFav');

});



/**
 * ADMIN ROUTES
 * API route group that we need to protect
 * We are just saying that we need the user to be an admin or have the
 * create-users permissions before they can access the routes in this group.
*/
Route::group(['prefix' => 'admin', 'middleware' => ['ability:admin']], function() {
    // Protected route
    // Entrust already has a EntrutAbility that can be seen here but the
    // problem is that it works with sessions and not tokens.
    // What we can do is extend the JWT's middleware to include Entrust's and
    // work with a token, not session.
    // php artisan make:middleware TokenEntrustAbility
    Route::get('users', 'UserController@index');

    Route::get('notes', 'NotesController@index');

    // Route to create a new role
    Route::post('role', 'UserController@createRole');
    // Route to create a new permission
    Route::post('permission', 'UserController@createPermission');
    // Route to assign role to user
    Route::post('assign-role', 'UserController@assignRole');
    // Route to attach permission to a role
    Route::post('attach-permission', 'UserController@attachPermission');
});
