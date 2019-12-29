<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
,|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth',], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
    Route::post('register', 'AuthController@register');
});

Route::group(['prefix' => 'usuario'], function(){
	Route::post('register', 'UsuarioController@register');
    Route::post('quienes', 'UsuarioController@quienes');
    Route::post('siguiendo', 'UsuarioController@siguiendo');
    Route::post('seguidores', 'UsuarioController@seguidores');
});

Route::group(['prefix' => 'tweets'], function(){
	Route::post('show', 'TweetsController@show');
	Route::post('guardar', 'TweetsController@guardar');
    Route::post('reaccion', 'TweetsController@reaccion');
});

Route::group(['prefix' => 'conectar'], function(){
	Route::post('seguir', 'ConectarController@seguir');
	Route::post('dejarseguir', 'ConectarController@dejarseguir');
}); 