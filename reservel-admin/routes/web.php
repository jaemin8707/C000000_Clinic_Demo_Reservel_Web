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

// 認証関連
Auth::routes();

// トップページ::受付一覧画面へのリダイレクト
Route::get ('/',                          'ReserveController@redirect2index')     ->name('index');
Route::get ('/index',                     'ReserveController@redirect2index')     ->name('index');
Route::get ('/index/{scroll}',            'ReserveController@redirect2index')     ->name('index');

// 予約受付情報 (一覧画面(index)、編集(edit)、更新(update))
Route::resource('reserve',                'ReserveController',        ['only' => ['index', 'edit', 'update']]);
// 予約::ステータス更新
Route::put ('reserve/{reserve}/status',   'ReserveController@updateStatus')       ->name('reserve.update.status');
// 予約::名前変更
Route::put ('reserve/{reserve}/name',   'ReserveController@updateName')       ->name('reserve.update.name');
// リマインドメール送信
Route::put ('reserve/{reserve}/remind',   'ReserveController@remindSend')       ->name('reserve.remind.send');

// 受付
Route::put ('reserve/create/{diagnosisType}',   'ReserveController@create')       ->name('reserve.create');

// 設定::発券可否更新(tab)
Route::put ('setting/tabTicketable',         'SettingController@updateTabTicketable')   ->name('setting.update.tabTicketable');
// 設定::発券可否更新(web)
Route::put ('setting/webTicketable',         'SettingController@updateWebTicketable')   ->name('setting.update.webTicketable');

Route::get('closed/{month}',             'ClosedController@index')        ->name('closed.index');

Route::resource('closed',                'ClosedController',        ['only' => ['index']]);

Route::post('/closed/create',            'ClosedController@create')       ->name('closed.create');

// 休診日区分更新
Route::put ('closed/{closed}/update',           'ClosedController@update')       ->name('closed.update');

// 休診日区分更新
Route::put ('closed/{closed}/delete',           'ClosedController@delete')       ->name('closed.delete');

// 休診日区分更新
Route::put ('closed/create_day',           'ClosedController@create_day')       ->name('closed.create_day');

