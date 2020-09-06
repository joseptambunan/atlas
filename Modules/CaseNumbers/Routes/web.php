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
    Route::get('/adjuster/all/{id}','CaseNumbersController@alladjuster');
    Route::post('/remove/adjuster','CaseNumbersController@removeadjuster');
    Route::post('/invoice/create','CaseNumbersController@createinvoice');

    Route::get('/iou','CaseNumbersController@iou');
    Route::get('/iou/show/{id}','CaseNumbersController@iou_show');
    Route::post('/iou/update_reference',"CaseNumbersController@update_reference");

    Route::post('/search/iou','CaseNumbersController@search');
    Route::post('/search/case','CaseNumbersController@search_case');

    Route::get('/download/{id}','CaseNumbersController@download');
    Route::post('/update_return','CaseNumbersController@update_return');

    Route::get('/expenses/add','CaseNumbersController@add_expenses');
    Route::post('/expenses/store','CaseNumbersController@save_expenses');
    Route::post('/expenses/update','CaseNumbersController@update_expenses');

    Route::get('/testemail','CaseNumbersController@testemail');
});
