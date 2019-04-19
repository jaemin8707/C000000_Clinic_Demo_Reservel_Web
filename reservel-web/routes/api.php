<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
// 院内アプリ：表示用データ取得
Route::get ('/acceptance',                    'Api\ReserveController@index')    ->middleware('client')->name('api.reserve.index');
// 院内アプリ：発番
Route::post('/reserve/numbering/{careType}',  'Api\ReserveController@numbering')->middleware('client')->name('api.reserve.numbering');
// 管理ツール一覧画面：予約データ更新
Route::put ('/reserve/{reserve}',             'Api\ReserveController@update')   ->middleware('client')->name('api.reserve.update');
// TopPage：総待ち人数取得
Route::get ('/acceptanceCount',               'Api\ReserveController@TotalCnt') ->name('api.reserve.total'); // 認証しない
// 院内アプリ：設定値取得
Route::get ('/config',                        'Api\ConfigController@show')      ->middleware('client')->name('api.config.show');
// 管理ツール一覧：発番可否更新
Route::put ('/config/update',                 'Api\ConfigController@update')    ->middleware('client')->name('api.config.update');
// 院内アプリログファイルアップロード
Route::post('/log/upload',                    'Api\LogController@upload')       ->middleware('client')->name('api.log.upload');
