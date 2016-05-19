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

// API ROUTES DEFINITIONS
Route::group(['middleware' => ['api'/*,'cors'*/],'prefix' => 'api'], function () {

  /**
  * PUBLIC ROUTES
  */
  Route::group(['prefix' => 'public'], function() {
      // Route to get the whole PUBLISHED notes list
      Route::get("notes/public", 'NotesController@publicNotes');

      // Route to get all the notes count
      Route::get("notes/count", 'NotesController@notesCount');

      // Route to authenticate a user
      Route::post('authenticate', 'JwtAuthenticateController@authenticate', ['only' => ['index']]);

      // Get the list of the users
      // This must bu under authentication, but for this demo we disabled it
      Route::get("users/demo/list", 'UserController@index');
  });

  /**
  * USER AUTHENTICATED REQUIRED
  */
  Route::group(['prefix' => 'auth', 'middleware' => ['before' => 'jwt.auth']], function() {

      // Route to get the user authenticated information
      Route::get('authenticate/user', 'JwtAuthenticateController@getAuthenticatedUser');

      // Route to get all the notes of the user
      Route::get("user/notes", 'UserController@userNotes');

      // Route to get all the user favorite notes
      Route::get("user/favnotes", 'UserController@userFavNotes');

      // Route to get all the user published note within the favorite property
      Route::get("notes/public", 'NotesController@publicNotesWithUserFav');

      // Route to get all the exentend information about the user
      Route::get('user/spec', 'JwtAuthenticateController@getUserSpec');

      // Route to set a note as favorite or not
      Route::post("user/favorite", ['middleware' => ['ability:admin,set-fav'],
                                                'uses'=> 'UserController@setFavorite']);

      // Route to publis/unpublish a note
      Route::post("user/publish", ['middleware' => ['ability:admin,publish-notes'],
                                                'uses'=> 'UserController@publish']);

      // Route to create a note
      Route::put("note", ['middleware' => ['ability:admin,create-notes'],
                                                'uses'=> 'NotesController@createNote']);

      // Route to delete a note
      Route::delete('note', ['middleware' => ['ability:moderator|admin,'],
                                              'uses'=> 'NotesController@deleteNote']);

  });

  /**
   * ADMIN ROUTES
   * API route group that we need to protect
   * We are just saying that we need the user to be an admin or have the
   * create-users permissions before they can access the routes in this group.
  */
  Route::group(['prefix' => 'admin', 'middleware' => ['ability:admin']], function() {
      // Route to get the whole  Notes list
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

});
