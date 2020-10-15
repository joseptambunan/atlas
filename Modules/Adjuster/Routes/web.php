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
    Route::post('/todolist','AdjusterController@todolist');
    Route::get('/invoice','AdjusterController@invoice');
    Route::post('/invoice/finish',"AdjusterController@finish_invoice");
    Route::post('/loadcases',"AdjusterController@loadcases");
    Route::post('/invoice/create','AdjusterController@create_invoice');

    Route::get('/iou/add','IousController@create');
    Route::post('/iou/store','IousController@store');
    Route::get('/iou/show/{id}','IousController@show');
    Route::post('/iou/update','IousController@update');
    Route::post('/iou/savedetail','IousController@savedetail');
    Route::post('/iou/delete','IousController@delete');
    Route::post('/iou/approval','IousController@approval');
    Route::get('/iou/index','IousController@index');
    Route::post('/iou/request_approval','IousController@request_approval');
    Route::post('/iou/expenses/request_approval','IousController@request_expenses_approval');
    Route::get('/iou/expired','IousController@expired');

    Route::get('/case/show/{id}','CasesController@show');
    Route::post('/case/expenses','CasesController@save_expenses');
    Route::post('/case/approval','CasesController@request_approval');
    Route::post('/case/remove_expenses','CasesController@remove_expenses');
    Route::post('/case/revisi_expenses','CasesController@revisi_expenses');
    Route::post('/case/history_approval','CasesController@history_approval');

});
