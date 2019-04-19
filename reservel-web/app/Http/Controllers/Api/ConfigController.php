<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{
    /**
      * 基本設定値取得処理
      * 
      * @return Response(JSON)
      * 
      **/
    public function show(){

        Log::Debug('基本設定値取得処理 Start');

        $result = [];
        $setting = config('hp_setting');
        $result['name'] = $setting['HOSPITAL_NAME'];
        $result['tel'] = $setting['HOSPITAL_TEL'];
        $result['url'] = $setting['HOSPITAL_URL'];
        $opTimes        = $setting['OP_TIME'];
        $json_opTime    = [];
        foreach($opTimes as $src_opTime) {
            $json_opTime['stime'] = $src_opTime['START'];
            $json_opTime['etime'] = $src_opTime['END'];
            $result['optimes'][] = $json_opTime;
        }

        $status = ['code' => 0, 'message' => ""];

        $return_data = [
            'result' => $result,
            'status' => $status
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        return response(json_encode($return_data));

        Log::Debug('基本設定値取得処理 End');
    }

    /**
      * 設定テーブル更新処理
      * 
      * @return Response(JSON)
      * 
      **/
    public function update(Request $request){

        Log::Debug('設定テーブル更新処理 Start');

        Setting::where('code','=',$request->keys()[0])
               ->update(['value'=>$request[$request->keys()[0]]]);

        $status = ['code' => 0, 'message' => ""];

        $return_data = [
            'result' => [],
            'status' => $status
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        return response(json_encode($return_data));

        Log::Debug('設定テーブル更新処理 End');
    }
}
