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

Route::prefix('master')->group(function() {
    Route::get('/', 'MasterController@index');

    Route::get('/document','DocumentsController@index');
    Route::post('/document/create','DocumentsController@create');
    Route::get('/document/show/{id}','DocumentsController@show');
    Route::post('/document/update','DocumentsController@update');
    Route::post('/document/approval','DocumentsController@approval');

    Route::get('/position','PositionsController@index');
    Route::get('/position/show/{id}','PositionsController@show');
    Route::post('/position/create','PositionsController@create');
    Route::post('/position/update','PositionsController@update');

    Route::get('/modules','ModulesController@index');
    Route::post('/modules/create',"ModulesController@create");
    Route::post('/modules/delete',"ModulesController@delete");

    Route::get('/adjusters','AdjustersController@index');
   	Route::get('/adjusters/add','AdjustersController@add'); 
   	Route::post('/adjusters/create','AdjustersController@create'); 
   	Route::get('/adjusters/show/{id}','AdjustersController@show');
   	Route::post('/adjusters/update','AdjustersController@update');
});
