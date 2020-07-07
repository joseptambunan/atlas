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

Route::prefix('casenumbers')->group(function() {
    Route::get('/', 'CaseNumbersController@index');
    Route::get('/index', 'CaseNumbersController@index');
    Route::get('/add','CaseNumbersController@add');
    Route::post('/create','CaseNumbersController@create');
    Route::get('/show/{id}','CaseNumbersController@show');
    Route::post('/update/','CaseNumbersController@update');
    Route::post('/delete/','CaseNumbersController@delete');
    Route::post('/saveadjusters','CaseNumbersController@saveadjusters');
});