<?php

use Illuminate\Http\Request;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('me', 'Auth\AuthController@me');
    Route::post('register', 'Auth\RegisterController@create');
});

Route::get('movies/popular', 'Api\MovieController@popular');
Route::apiResource('movies', 'Api\MovieController');
Route::apiResource('movies/{movie}/comments', 'Api\CommentController');
Route::get('genres', 'Api\GenreController@index');
Route::post('movies/{movie}/like', 'Api\MovieController@like');
Route::post('movies/{movie}/dislike', 'Api\MovieController@dislike');
Route::post('movies/{movie}/unlike', 'Api\MovieController@unlike');

Route::get('watchlist', 'Api\WatchlistController@get');
Route::post('watchlist/add/{movie}', 'Api\WatchlistController@add');
Route::post('watchlist/remove/{movie}', 'Api\WatchlistController@remove');
Route::post('watchlist/watched/{movie}/{watched}', 'Api\WatchlistController@setWatched');
