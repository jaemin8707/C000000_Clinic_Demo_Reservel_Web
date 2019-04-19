<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
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

    public function redirect2index()
    {
        //
        return  redirect(route('reserve.index'), 301);
    }

    /**
     * 予約一覧画面表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::Debug('予約一覧画面表示 Start');

        //当日受付状況
        $reserves = Reserve::where('created_at','>=',DB::RAW('CURDATE()'))
                           ->orderBy('reception_no')
                           ->get();
        //待ちの数
        $waitCnt = Reserve::where(function($query) {
                                      $query->orWhere('status', '=', config('const.RESERVE_STATUS.WAITING'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.CALLED'))
                                            ->orWhere('status', '=', config('const.RESERVE_STATUS.EXAMINE'));
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

        return view('reserve.index', compact('reserves', 'waitCnt', 'tabTicketable', 'webTicketable'));
    }

    /**
     * 予約編集画面表示
     *
     * @param  Reserve $reserve 表示対象の予約情報
     * @
     * @return \Illuminate\Http\Response 予約編集画面
     */
    public function edit(Reserve $reserve)
    {
        Log::Debug('予約編集画面表示 Start');

        Log::Debug('予約編集画面表示 End');

        return view('reserve.edit', compact('reserve'));
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

        $orgStatus = $reserve->status;

        $reserve->status          = $request->status;
        $reserve->medical_card_no = $request->patient_no;
        $reserve->name            = $request->name;
        $reserve->email           = $request->email;
        $reserve->tel             = $request->tel;
        $reserve->pet_type        = $request->pet_type;
        $reserve->pet_name        = $request->pet_name;
        $reserve->conditions      = $request->pet_symptom;
        DB::enableQueryLog();
        $reserve->save();
        Log::Debug(DB::getQueryLog());

        Log::Debug('更新処理 End');

        return redirect(route('reserve.index'));
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

        return redirect(route('reserve.index'));
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

      return redirect(route('reserve.index'));
  }


}
