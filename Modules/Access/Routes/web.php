<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('access')->group(function() {
    Route::get('/', 'AccessController@index');
    Route::post('/submit','AccessController@submit');
    Route::get('/fail','AccessController@fail');
	Route::get('/home','AccessController@home')->middleware("auth");
	Route::get('/logout','AccessController@logout');
	Route::get('/master','AccessController@master')->middleware("auth");
	Route::get('/reset','AccessController@reset');
	Route::post('/submit/reset','AccessController@submitreset');
	Route::get('/reset/success','AccessController@successreset');
	Route::get('/reset/fail','AccessController@failreset');
	Route::get('/profile','AccessController@profile')->middleware("auth");
	Route::post('/profile/update','AccessController@update_profile')->middleware("auth");
});
