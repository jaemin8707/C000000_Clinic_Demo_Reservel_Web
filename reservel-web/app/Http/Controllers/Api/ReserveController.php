<?php

namespace App\Http\Controllers\Api;

use App\Models\Reserve;
use App\Models\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReserveController extends Controller
{
    /**
      * 状況別待ち番号一覧取得処理
      * 
      * @return Response(JSON)
      * 
      **/
    public function index() {

        Log::Debug('状況別待ち番号一覧取得処理 Start');

        // 状況別待ち番号一覧取得
//DB::enableQueryLog();
        $reserves   = Reserve::where(function($query) {
                                 $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                       ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                       ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'));
                              })
                            ->where(DB::Raw("DATE(created_at)"), "=", DB::Raw("CURDATE()"))
                            ->orderBy('status','desc')
                            ->orderBy('reception_no')
                            ->get();
//Log::Debug(DB::getQueryLog());

        // 状況別待ち番号一覧フェッチ＆構造化
        $status = 0;
        $queue = [];
        $care_data = [];
        Log::Debug("cnt:".count($reserves));
        foreach ($reserves as $reserve){
            Log::Debug($reserve);
            if ($status != $reserve->status) {
                if ($status != 0) {
                    $queue[] = $care_data;
                }
                $care_data['status'] = $reserve->status;
                $care_data['reception_no'] = [];
            }
            $care_data['reception_no'][] = $reserve->reception_no;
            $status = $reserve->status;
        }
        if ($status != 0) {
            $queue[] = $care_data;
        }

        // 発券可否状態取得
        $ticketable = Setting::where('code', "=", 'tabTicketable')
                             ->value('value');

        $return_data = [
            'result' => ['tabTicketable'=> $ticketable, 'queue' => $queue],
            'status' => ['code' => 0, 'message' => ""]
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        Log::Debug('状況別待ち番号一覧取得処理 End');

        return response(json_encode($return_data));

    }

    /**
      * 受付番号発番処理
      * 
      * @param int $careType  受付区分（1：初診、2：再診）
      * 
      * @return Response(JSON)
      * 
      **/
    public function numbering(int $careType, Request $request) {

        Log::Debug('受付番号発番処理 Start');

        //受付番号採番
        $reception_no = DB::table('reserve_seq')->insertGetId([]);

        // 予約データ保存
        $reserve = new Reserve;
        $reserve->reception_no = $reception_no;
        $reserve->care_type    = $careType;
        $reserve->medical_card_no = $request->patient_no;
        $reserve->place = config('const.PLACE.IN_HOSPITAL');
        $reserve->status = config('const.RESERVE_STATUS.WAITING');
        $reserve->save();

        $result = ['number' => $reception_no];
        $status = ['code' => 0, 'message' => ""];

        $return_data = [
            'result' => $result,
            'status' => $status
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        Log::Debug('受付番号発番処理 End');

        return response(json_encode($return_data));

    }

    /**
      * 更新処理
      * 管理ツール受付状況一覧画面からの名前、ステータスの更新
      * 
      * @param Reserve $reserve  予約情報
      * 
      * @return Response(JSON)
      * 
      **/
    public function update(Request $request, Reserve $reserve) {

        Log::Debug('更新処理 Start');
        Log::Debug('対象予約データ');
        Log::Debug($reserve);

        // 予約データ保存
        $name   = $request->name;
        $status = $request->status;

        if (isset($name) || isset($status)) {
            if(isset($name)){
                Log::Debug('名前更新:'.$name);
                $reserve->name = $name;
            }
            if(isset($status)){
                Log::Debug('ステータス更新:'.$status);
                $reserve->status = $status;
            }
            $reserve->save();
        }else{
            Log::Debug('未更新');
        }

        $result = [];
        $status = ['code' => 0, 'message' => ""];

        $return_data = [
            'result' => $result,
            'status' => $status
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        Log::Debug('更新処理 End');

        return response(json_encode($return_data));

    }

    /**
      * 更新処理
      * 管理ツール受付状況一覧画面からの名前、ステータスの更新
      * 
      * @param Reserve $reserve  予約情報
      * 
      * @return Response(JSON)
      * 
      **/
    public function totalCnt(Request $request) {

        Log::Debug('総待ち人数取得処理 Start');

        $cnt = Reserve::where(function($query) {
                            $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                  ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                  ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'));
                        })
                      ->where(DB::Raw("DATE(created_at)"), "=", DB::Raw("CURDATE()"))
                      ->value(DB::Raw('count(*)'));


        $result = [
            'total'=> $cnt,
            'time' => date('Y/m/d H:i'),
        ];
        $status = ['code' => 0, 'message' => ""];

        $return_data = [
            'result' => $result,
            'status' => $status
        ];

        Log::Debug("returnjson:".json_encode($return_data));
        Log::Debug('総待ち人数取得処理 End');

        return response(json_encode($return_data))
                   ->withHeaders([
                        'Access-Control-Allow-Origin' => env('CORS', 'akatsuki-reservel.jp'),
                    ]);

    }

}
