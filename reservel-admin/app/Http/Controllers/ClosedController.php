<?php

namespace App\Http\Controllers;

use App\Models\Closed;
use App\Http\Requests\ClosedPostRequest;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ClosedController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * 休診日一覧画面表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
      $month = empty($request->month) ? $month = date('Y-m') : $request->month;
      $thisMonth = new Carbon($month);
      $prevMonth = Carbon::parse($month)->subMonth()->format('Y-m');
      $nextMonth = Carbon::parse($month)->addMonth()->format('Y-m');
      // 休日データ取得
      $closed = Closed::whereYear('closed_day', '=',  $thisMonth->year)
                     ->whereMonth('closed_day', '=', $thisMonth->month)
                     ->orderby('closed_day')
                     ->get();

      return view('closed.index', ['closed' => $closed, 'month' => $month, 'prevMonth' => $prevMonth, 'nextMonth' => $nextMonth]); 
    }

    /**
     * 休診日と休診区分を保存する。
     *
     * @param  App\Http\Requests\ClosedPostRequest  $request 画面からの追加値(バリデーション済み)
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function create(ClosedPostRequest $request) {
      //日付単位登録
      if($request->create_type == config('const.CLOSED_CREATE_TYPE.DAY')) {
          Log::Debug('休診日追加処理(日付) Start');
          $closed = new Closed;
          $closed->closed_day = $request->closed_day;
          $closed->closed_type = $request->closed_type;
          DB::enableQueryLog();
          $closed->save();
          Log::Debug(DB::getQueryLog());
          Log::Debug('休診日追加処理(日付) End');
          return redirect(route('closed.index',['month' => Carbon::parse($request->closed_day)->format('Y-m')]));
      
      //月の該当曜日登録
      } elseif ($request->create_type == config('const.CLOSED_CREATE_TYPE.WEEK')) {
          Log::Debug('休診日追加処理(毎週曜日) Start');
          $month = $this->get_day($request->closed_week, $request->closed_type, $request->month);
          Log::Debug('休診日追加処理(日付) End');
          return redirect(route('closed.index',['month' => $month]));
      }
    }

    /**
     * 休診日と休診区分を更新する。
     *
     * @param  App\Http\Requests\ClosedPostRequest  $request 画面からの追加値(バリデーション済み)
     * @param  Closed $closed 休診日
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function update(ClosedPostRequest $request, Closed $closed) {
      Log::Debug('休診日更新処理 Start');

      $closed->closed_type = $request->closed_type;
      DB::enableQueryLog();
      $closed->save();
      Log::Debug(DB::getQueryLog());

      Log::Debug('更新処理 End');
      return redirect(route('closed.index',['month' => Carbon::parse($request->closed_day)->format('Y-m')]));
    }

    /**
     * 休診日と休診区分を削除する。
     *
     * @param  Closed $closed 削除対象休診日
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function delete(Request $request,Closed $closed) {
      Log::Debug('休診日削除処理 Start');
      DB::enableQueryLog();
      $closed->delete();
      Log::Debug(DB::getQueryLog());

      Log::Debug('更新処理 End');
      return redirect(route('closed.index',['month' => Carbon::parse($request->closed_day)->format('Y-m')]));
    }

    /**
     * 
     * 
     * 
     * 
     */
    public function get_day($day, $closedType, $month) {
      $firstDate = Carbon::parse($month.'-01')->firstOfMonth();
      $endDate   = Carbon::parse($month.'-01')->endOfMonth();
      $monthDate = CarbonPeriod::create($firstDate, $endDate);
      foreach ($monthDate as $date) {
          if ($date->dayOfWeekIso == $day) {
            DB::enableQueryLog();
            Closed::updateOrCreate(['closed_day' => $date->format('Y-m-d')],['closed_type' => $closedType]);
            Log::Debug(DB::getQueryLog());
          }
      }
      return $month;
    }
}