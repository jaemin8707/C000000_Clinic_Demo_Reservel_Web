<?php

namespace App\Http\Controllers;

use App\Models\Notice;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NoticeController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/notice/index';

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
     * 予約一覧画面表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        Log::Debug('お知らせ一覧表示 Start');
        $notices = Notice::orderBy('sort', 'ASC')->get();
        Log::Debug('お知らせ一覧表示 End');
        return view('notice.index', compact('notices'));
    }

    /**
     * お知らせ順番変更
     *
     * @param array $sort  更新順番Id
     * @param  App\Http\Requests\Request  $request 
     * @
     * @return Response
     */
    public function store(Request $request) {
        Log::Debug('お知らせ表示順番変更 Start');

        $sort = isset($request->sort) ? explode(',', $request->sort) : [];
        $i = 1;
        foreach($sort as $id) {
            $notice = Notice::find($id);
            $notice->sort = $i;
            $result = $notice->save();
            $i++;
        }
        $postFlg =isset($request->post_flg) ? $request->post_flg : [];
        foreach($request->post_flg as $id => $value) {
            $notice = Notice::find($id);
            $notice->post_flg = $value;
            $result = $notice->save();
        }

        Log::Debug('お知らせ表示順番変更 End');
        return redirect(route('notice.index'))->with('flash_message', 'お知らせの表示順番を変えました。');;
    }

    public function store_new_notice(Request $request) {
        Log::Debug('お知らせ登録 Start');

        $validatedData = $request->validate([
            'notice_text' => 'required|max:40',
        ]);
        $lastSort = Notice::select('sort')->count();
        $number = $lastSort + 1;

        $notice = new Notice;
        $notice->sort = $number;
        $notice->notice_text = $request->notice_text;
        $notice->save();
        return redirect(route('notice.index'));
        Log::Debug('お知らせ登録 Start');
    }
    
    /**
     * お知らせ削除
     *
     * @param  App\Http\Requests\Request  $request お知らせId
     * 
     * @return \Illuminate\Http\Response 一覧画面
     */
    public function delete(Request $request) {
      Log::Debug('お知らせ削除 Start');
      $notice = Notice::find($request->id);
      $notice->delete();
      Log::Debug('お知らせ削除 End');
      return redirect(route('notice.index'));
    }
}
