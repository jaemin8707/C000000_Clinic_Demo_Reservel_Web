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

// 予約一覧画面表示処理
Route::get ('/',                                      'ReserveController@index')     ->name('index');
Route::get ('/index',                                 'ReserveController@index')     ->name('index');

// 受付フォーム画面表示処理
Route::get ('/reserve/create/{diagnosisType}',        'ReserveController@create')    ->name('reserve.create');
// 受付確認画面表示処理
Route::post('/reserve/confirm',                       'ReserveController@conf')      ->name('reserve.confirm');
// 予約発番処理
Route::post('/reserve',                               'ReserveController@store')     ->name('reserve.store');
// 受付完了画面表示処理
Route::get('/reserve/complete',                       'ReserveController@complete')  ->name('reserve.complete');
// 受付キャンセル画面表示
Route::get('/reserve/cancel/{cancelToken}',           'ReserveController@cancel')    ->name('reserve.cancel');
// 受付キャンセル画面表示
Route::post('/reserve/cancel_complete',               'ReserveController@cancel_complete')    ->name('reserve.cancel_complete');
