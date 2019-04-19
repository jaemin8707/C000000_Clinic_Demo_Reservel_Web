<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Libs\Command\LogCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DisableTabTicketing extends LogCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve:disableTabTicketing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tabを発券不可状態へ設定変更(午前診察終了と午後診察終了)';

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
        $this->formattedLogInfo("Tab発券不可状態への設定変更バッチ Start");

        $queryLog = null;
        try {

            DB::enableQueryLog();
            $updatedCnt = Setting::where('code','=', 'tabTicketable')
                                 ->update(['value' => 'false']);
            $queryLog = DB::getQueryLog();

        } catch (Exception $ex) {
            $this->error('SQL実行エラー');
            $this->formattedQueryLog($queryLog);
            var_dump($ex);
        }

        $this->formattedLogInfo("更新件数：$updatedCnt");
        $this->formattedLogInfo("Tab発券不可状態への設定変更バッチ End");

    }
}
