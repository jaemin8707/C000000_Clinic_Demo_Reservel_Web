<?php

namespace App\Console\Commands;

use App\Models\Reserve;
use App\Libs\Command\LogCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TimeupCalled extends LogCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve:timeupCalled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '呼び出し済み自動キャンセルバッチ(分次起動)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

/*      //運用しないためにコメントアウト
        $this->formattedLogInfo("呼び出し済み自動キャンセルバッチ Start");

        $updateCnt = 0;
        try {

            DB::enableQueryLog();
            $updatedCnt = Reserve::where(DB::Raw('DATE(created_at)'),'=',DB::Raw('CURDATE()'))
                                 ->where('status','=', config('const.RESERVE_STATUS.CALLED'))
                                 ->where(DB::Raw('(call_time + INTERVAL 60 MINUTE)'),'<', DB::Raw('NOW()'))
                                 ->update([
                                   'status' => config('const.RESERVE_STATUS.CALLED_TIMEUP_CANCEL'),
                                   'updated_id' => config('const.USER_ID.batch.timeup_called'),
                                   ]);
            $queryLog = DB::getQueryLog();
            $this->formattedQueryLog($queryLog);

        } catch (Exception $ex) {
            $this->error('SQL実行エラー');
            $this->formattedQueryLog($queryLog);
            var_dump($ex);
        }

        $this->formattedLogInfo("キャンセルにした件数：$updatedCnt");
        $this->formattedLogInfo("呼び出し済み自動キャンセルバッチ End");
*/
    }
}
