<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['cors', 'json.response']], function () {
    // ...
	Route::group([
	    'prefix' => 'auth',
	], function () {
	    Route::post('login', 'UserController@login');
	    Route::post('signup', 'UserController@register');
	});

	Route::group([
      'middleware' => 'auth:api',
    ], function() {
        Route::get('logout', 'UserController@logout');
        
        Route::resource('posts', 'PostController');
        Route::post('posts/files', 'MediaController@store');
        Route::delete('files/{id}', 'MediaController@destroy');
    });
});

