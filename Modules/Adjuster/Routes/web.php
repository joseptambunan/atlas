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

Route::prefix('adjuster')->group(function() {
    Route::get('/index', 'AdjusterController@index');
    Route::post('/update/data','AdjusterController@update');

    Route::get('/iou/add','IousController@create');
    Route::post('/iou/store','IousController@store');
    Route::get('/iou/show/{id}','IousController@show');
    Route::post('/iou/update','IousController@update');
    Route::post('/iou/savedetail','IousController@savedetail');
    Route::post('/iou/delete','IousController@delete');
    Route::post('/iou/approval','IousController@approval');
});
