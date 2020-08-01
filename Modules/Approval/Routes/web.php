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

Route::prefix('approval')->group(function() {
    Route::get('/index', 'ApprovalController@index');
    Route::get('/show/{type}/{id}/{approval_id}','ApprovalController@show');
    Route::get('/download/{id}','ApprovalController@download');

    Route::post('/submit/','ApprovalController@submit');
    Route::get('/iou/team','ApprovalController@ioupending');
    Route::get('/iou/show/{id}','ApprovalController@ioushow');

    Route::get('/invoice/index','ApprovalController@invoice');
    Route::get('/invoice/show/{id}','ApprovalController@invoice_show');

    Route::post('/request_approval','ApprovalController@request_approval');
    Route::get('/expenses/approval/{id}','ApprovalController@expenses_approval');

});
