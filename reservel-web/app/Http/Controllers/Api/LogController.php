<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    //
		public function upload(Request $request){
        Log::Debug('ログファイルアップロード Start');

        if(!$request->file('uploads')) {
            $return_data = [
                'result' => [],
                'status' => [
                  'code' => '-1',
                  'message' => '',
                ]
            ];
        } else {
            $file = $request->file('uploads');
            $name = $file->getClientOriginalName();
            $filepath = $file->storeAs("tabletLogs/", $name, env('DISK_UPLOAD'));
            $return_data = [
                'result' => [],
                'status' => [
                   'code' => '0',
                    'message' => '',
                ]
            ];
        }
        Log::Debug('ログファイルアップロード End');
        return response(json_encode($return_data));
		}
}
