<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Libs\Command\LogCommand;
use App\Libs\Command\ClosedCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnableTabTicketing extends LogCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve:enableTabTicketing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tabを発券可能状態へ設定変更(午前診察開始と午後診察開始))';

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
        $this->formattedLogInfo("Tab発券可能状態への設定変更バッチ Start");

        try {
            $updatedCnt = '0';
            $terminalTime = config('const.RESERVE_START_TIME_TAB');
            if($closed = ClosedCommand::checkClosedDate($terminalTime)) {
                    $this->formattedLogInfo("休診日：".$closed->closed_day.", 休診区分：".config('const.CLOSED_TYPE_NAME')[$closed->closed_type]);
            } else {
                    $this->formattedLogInfo("営業日");
                    $this->formattedLogInfo("受付可能に変更 Start");
                    DB::enableQueryLog();
                    $updatedCnt = Setting::where('code','=', 'tabTicketable')
                                                    ->update(['value' => 'true']);
                    $queryLog = DB::getQueryLog();
                    $this->formattedLogInfo("受付可能に変更 End");
            }
        } catch (Exception $ex) {
            $this->error('SQL実行エラー');
            $this->formattedQueryLog($queryLog);
            var_dump($ex);
        }

        $this->formattedLogInfo("更新件数：$updatedCnt");
        $this->formattedLogInfo("Tab発券可能状態への設定変更バッチ End");

    }
}
