<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Models\PetType;
use App\Models\Setting;
use App\Mail\RemindSend;
use App\Http\Requests\ReservePostRequest;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ReserveController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/reserve/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function redirect2index(Request $request)
    {
        //
        return  redirect(route('reserve.index', ['scroll' => $request->scroll]), 301);
    }

    /**
     * 予約一覧画面表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::Debug('予約一覧画面表示 Start');

        //当日受付状況
        $reserves = Reserve::where('created_at','>=',DB::RAW('CURDATE()'))
                           ->with('petType:reserve_id,pet_type', 'purpose:reserve_id,purpose')
                           ->orderBy('reception_no')
                           ->get();
        //待ちの数
        $waitCnt = Reserve::where(function($query) {
                                      $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.PAYMENT'));
                                   })
                          ->where('created_at','>=',DB::RAW('CURDATE()'))
                          ->orderBy('reception_no')
                          ->count();
        //受付可否状況取得(tab)
        $tabTicketable = Setting::where('code','=','tabTicketable')
                             ->value('value');
        //受付可否状況取得(web)
        $webTicketable = Setting::where('code','=','webTicketable')
                             ->value('value');

        Log::Debug('予約一覧画面表示 End');


        return view('reserve.index', compact('reserves', 'waitCnt', 'tabTicketable', 'webTicketable'))->with('scroll', $request->scroll);
    }
    /**
     * 予約新規登録
     *
     * @param int $careType  受付区分（1：初診、2：再診）
     * @param  App\Http\Requests\ReservePostRequest  $request 画面からの登録値(バリデーション済み)
     * @
     * @return Response
     */
    public function create(int $careType, ReservePostRequest $request)
    {
        Log::Debug('登録処理 Start');
        DB::beginTransaction();
        try{
            //受付番号採番
            $reception_no = DB::table('reserve_seq')->insertGetId([]);

            $reserve = new Reserve;
            $reserve->medical_card_no = $request->patient_no;
            $reserve->reception_no    = $reception_no;
            $reserve->care_type = $careType;
            $reserve->place           = config('const.PLACE.IN_HOSPITAL');
            $reserve->status          = config('const.RESERVE_STATUS.WAITING');

            $reserve->save();
        }catch(Exception $e){
            DB::rollback();
            Log::Debug('登録失敗 End');
            return redirect(route('reserve.index'));
        }
        DB::commit();
        Log::Debug('登録成功 End');
        return redirect(route('reserve.index'))->with('scroll', $request->scroll);
    }
    /**
     * 予約編集画面表示
     *
     * @param  Reserve $reserve 表示対象の予約情報
     * @
     * @return \Illuminate\Http\Response 予約編集画面
     */
    public function edit(Reserve $reserve, ReservePostRequest $request)
    {
        Log::Debug('予約編集画面表示 Start');
        //ペット種類取得
        $scroll = $request->scroll;
        Log::Debug('予約編集画面表示 End');

        return view('reserve.edit', compact('reserve', 'scroll'));
    }

    /**
     * 予約編集画面からの更新処理
     * 画面入力値ですべて変更する。
     * 呼出時間はupdatingイベントにて処理する
     * リマインドメール送信ははupdatedイベントにて処理する
     *
     * @param  App\Http\Requests\ReservePostRequest  $request 画面からの更新値(バリデーション済み)
     * @param  Reserve $reserve 更新対象の予約情報
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function update(ReservePostRequest $request, Reserve $reserve) {

        Log::Debug('更新処理 Start');

        DB::beginTransaction();
        try{
            $orgStatus = $reserve->status;

            $reserve->status          = $request->status;
            $reserve->medical_card_no = $request->patient_no;
            $reserve->name            = $request->name;
            $reserve->age             = $request->age;
            $reserve->gender          = $request->gender;
            $reserve->email           = $request->email;
            $reserve->tel             = $request->tel;
            $reserve->conditions      = $request->pet_symptom;
            DB::enableQueryLog();
            $reserve->save();
            
            Log::Debug(DB::getQueryLog());
        }catch(Exception $e){
            DB::rollback();
            Log::Debug('更新失敗 End');
            return redirect(route('reserve.index'));
        }
        DB::commit();

        Log::Debug('更新処理 End');

        return redirect(route('reserve.index'))->with('scroll', $request->scroll);
    }

    /**
     * 予約一覧画面からのステータス変更処理
     * 指定ステータスへの変更を行う
     * 呼出時間はupdatingイベントにて処理する
     * リマインドメール送信ははupdatedイベントにて処理する
     * 
     * @param  App\Http\Requests\ReservePostRequest  $request 画面からの更新値(バリデーション済み)
     * @param  Reserve $reserve 更新対象の予約情報
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function updateStatus(ReservePostRequest $request, Reserve $reserve) {

        Log::Debug('ステータス変更処理 Start');

        $reserve->status = $request->status;
        $reserve->save();

        Log::Debug('ステータス変更処理 End');

        return redirect(route('reserve.index'))->with('scroll', $request->scroll);
    }

    /**
     * 予約一覧画面からの名前の変更処理
     *
     * @param  App\Http\Requests\ReservePostRequest  $request 画面からの更新値(バリデーション済み)
     * @param  Reserve $reserve 更新対象の予約情報
     *
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function updateName(ReservePostRequest $request, Reserve $reserve) {

      Log::Debug('名前変更処理 Start');

      $reserve->name = $request->name;
      $reserve->save();

      Log::Debug('名前変更処理 End');

      return redirect(route('reserve.index'))->with('scroll', $request->scroll);
    }

    /**
     * リマインドメール手動送信
     *
     * @param  Reserve $reserve 更新対象の予約情報
     *
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function remindSend(ReservePostRequest $request, Reserve $reserve) {
        Log::Debug('リマインドメール送信処理 Start');

        if($reserve->send_remind == false){
            Mail::to($reserve->email)
                ->send(new RemindSend($reserve));
            if (Mail::failures()) {
                Log::Debug('リマインドメール送信失敗');
            } else {
                $reserve->send_remind = true;
                $reserve->save();
            }
        } else {
            Log::Debug('すでに送信されました。');
        }
        Log::Debug('リマインドメール送信処理 End');
        return redirect(route('reserve.index'))->with('scroll', $request->scroll);
    }
}
