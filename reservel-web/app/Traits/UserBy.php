<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

trait UserBy
{
    public static function bootUserBy()
    {

      //ユーザー登録
      Log::Debug('created_id, updated_id(ユーザー保存)イベント Start');
      static::creating(function($model)
      {
          if (Route::current() !== null && config("const.USER_ID")[Route::current()->getName()] !== null) {
              $userId = config("const.USER_ID")[Route::current()->getName()];
          } else {
              $userId = null;
          }
          $model->created_id = $userId;
          $model->updated_id = $userId;
      });
      static::updating(function($model)
      {
          if (Auth::user() !== null) {
              $user = Auth::user();
              $userId = $user->id;
          } elseif (Route::current() !== null && config("const.USER_ID")[Route::current()->getName()] !== null) {
              $userId = config("const.USER_ID")[Route::current()->getName()];
          } else {
              $userId = null;
          }
          $model->updated_id = $userId;
      });
      Log::Debug('created_id, updated_id(ユーザー保存)イベント End');
    }


}