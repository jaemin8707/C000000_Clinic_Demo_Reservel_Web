<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Reserve;
use App\Models\Setting;
use App\Models\Notice;
use App\Http\Requests\ReservePostRequest;

use App\Mail\ReserveMail;
use Illuminate\Support\Facades\Mail;
use Input;

class ReserveController extends Controller {
    //

    /**
      * 予約一覧画面表示処理
      * 
      * @return Response
      * 
      **/
    public function index() {

        DB::enableQueryLog();
        // 初診予約待ち行列
        $reserveFirst   = Reserve::where(function($query) {
                                      $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.PAYMENT'));
                                   })
                                 ->where("care_type", "=",  config('const.CARE_TYPE.FIRST'))
                                 ->whereBetween("created_at", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
                                 ->orderBy('status','desc')
                                 ->orderBy('reception_no')
                                 ->get();
        Log::Debug(DB::getQueryLog());
        // 再診予約待ち行列
        $reserveRegular = Reserve::where(function($query) {
                                      $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.PAYMENT'));
                                   })
                                 ->where("care_type", "=",  config('const.CARE_TYPE.REGULAR'))
                                 ->whereBetween("created_at", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
                                 ->orderBy('status','desc')
                                 ->orderBy('reception_no')
                                 ->get();

        // そのた予約待ち行列
        $reserveEtc = Reserve::where(function($query) {
                                    $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                        ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                        ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'))
                                        ->orWhere('status', '=', config('const.RESERVE_STATUS.PAYMENT'));
                                })
                                ->where("care_type", "=",  config('const.CARE_TYPE.ETC'))
                                ->whereBetween("created_at", [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')])
                                ->orderBy('status','desc')
                                ->orderBy('reception_no')
                                ->get();
        Log::Debug(DB::getQueryLog());

        $webTicketable = Setting::where('code','=','webTicketable')
                             ->value("value");
        $notices = Notice::where('post_flg', 1)->orderBy('sort', 'ASC')->limit(4)->get();
        return view('index', compact('reserveFirst', 'reserveRegular', 'reserveEtc', 'webTicketable', 'notices'));
    }

    /**
      * 受付フォーム画面表示処理
      * 
      * @param int $careType  受付区分（1：初診、2：再診, 9:その他）
      * 
      * @return Response
      * 
      **/
    public function create(int $careType) {
        $webTicketable = Setting::where('code','=','webTicketable')
                        ->value("value");
        if($webTicketable=='false') {
                return redirect(route('index'));
        }
        return view('reserve.create', compact("careType"));

    }

    /**
      * 受付確認画面表示処理
      * 
      * @param ReservePostRequest $request  バリデーション済みリクエスト(App\Http\Requests\ReservePostRequest参照)
      * 
      * @return Response
      * 
      **/
    public function conf(ReservePostRequest $request) {

        return view('reserve.confirm', compact("request"));

    }

    /**
      * 受付情報登録処理
      * 
      * @param ReservePostRequest $request  バリデーション済みリクエスト(App\Http\Requests\ReservePostRequest参照)
      * 
      * @return Response
      * 
      **/
    public function store(ReservePostRequest $request) {

        DB::beginTransaction();
        try{
            //受付番号採番
            $reception_no = DB::table('reserve_seq')->insertGetId([]);

            //受付キャンセル用のトークン発行
            $token = str_random(32);
            // DB保存
            $reserve = new Reserve;
            $reserve->medical_card_no = $request->patient_no;
            $reserve->reception_no    = $reception_no;
            $reserve->care_type       = $request->careType;
            $reserve->place           = config('const.PLACE.OUT_HOSPITAL');
            $reserve->status          = config('const.RESERVE_STATUS.WAITING');
            $reserve->age             = $request->age;
            $reserve->gender          = $request->gender;
            $reserve->name            = $request->name;
            $reserve->email           = $request->email;
            $reserve->tel             = $request->tel;
            $reserve->conditions      = $request->pet_symptom;
            $reserve->cancel_token    = $token;

            $reserve->save();
        }catch(Exception $e){
            DB::rollback();
            return redirect(route('index'));
        }
        DB::commit();
        // メール送信
        Mail::to($reserve->email)
            ->send(new ReserveMail($reserve)); // 引数にリクエストデータを渡す

        // 二重送信対策
        $request->session()->regenerateToken();

        return redirect(route('reserve.complete')."?careType=".$reserve->care_type."&receptionNo=".$reception_no);
    }

    /**
      * 受付完了画面表示処理
      * 
      * @param Request $request  バリデーション済みリクエスト(App\Http\Requests\ReservePostRequest参照)
      * 
      * @return Response
      * 
      **/
    public function complete(Request $request) {

        return view('reserve.complete', ["careType" => $request->careType, "reception_no" => $request->receptionNo]);

    }

    /**
     * 予約キャンセルURLクリック処理
     *
     * @param CancelToken $cancelToken キャンセル用トークン
     *
     * @return Response
     */
    public function cancel(string $cancelToken) {

        $reserve   = Reserve::where("cancel_token", "=",  $cancelToken)
                                   ->first();
        if(!isset($reserve)) {
            return redirect(route('index'), 301);
        }
        return view('cancel.index', compact('reserve'));
    }
    
    /**
     * 予約キャンセル処理
     *
     * @param request $request 受付ID(id), キャンセルトークン(cancel_token)
     *
     * @return view
     */
    public function cancel_complete(Request $request) {

        $reserve = Reserve::where("id", "=", $request->id)
                            ->where("cancel_token", "=", $request->cancel_token)
                            ->first();

        $reserve->status = config('const.RESERVE_STATUS.CANCEL_BY_PATIANT');
        $reserve->cancel_token = null;

        $reserve->save();
        
        return view('cancel.complete');
    }
}
