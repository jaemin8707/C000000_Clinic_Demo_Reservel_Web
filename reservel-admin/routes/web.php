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

// 予約受付情報 (一覧画面(index)、編集(edit)、更新(update))
Route::resource('reserve',                'ReserveController',        ['only' => ['index', 'edit', 'update']]);
// 予約::ステータス更新
Route::put ('reserve/{reserve}/status',   'ReserveController@updateStatus')       ->name('reserve.update.status');
// 予約::名前変更
Route::put ('reserve/{reserve}/name',   'ReserveController@updateName')       ->name('reserve.update.name');

// 設定::発券可否更新(tab)
Route::put ('setting/tabTicketable',         'SettingController@updateTabTicketable')   ->name('setting.update.tabTicketable');
// 設定::発券可否更新(web)
Route::put ('setting/webTicketable',         'SettingController@updateWebTicketable')   ->name('setting.update.webTicketable');

