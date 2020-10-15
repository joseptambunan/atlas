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

    //Finance
    Route::get('/invoice','FinanceController@index');
    Route::post('/invoice/search','FinanceController@search_case');

    Route::get('/case/all','FinanceController@case_all');
    Route::get('/case/show/{id}','FinanceController@case_show');

    Route::get('/iou','FinanceController@iou');
    Route::get('/iou/show/{id}','FinanceController@iou_show');
    Route::post('/iou/update_reference',"FinanceController@update_reference");
    Route::post('/search/iou','FinanceController@search');
    Route::post('/iou/set_finish','FinanceController@update_return');


    Route::get('/expenses/add','FinanceController@add_expenses');
    Route::post('/expenses/store','FinanceController@save_expenses');
    Route::post('/expenses/update','FinanceController@update_expenses');

    Route::get('/download/{id}','FinanceController@download');
    Route::get('/download_receipt/{id}','FinanceController@download_receipt');
    Route::get('/download_return/{id}','FinanceController@download_return');
    Route::get('/testemail','CaseNumbersController@testemail');

    Route::post('/reiumberse/add','FinanceController@reiumberse');

});
