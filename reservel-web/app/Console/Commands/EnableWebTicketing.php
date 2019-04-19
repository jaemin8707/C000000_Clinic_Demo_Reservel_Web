<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Libs\Command\LogCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnableWebTicketing extends LogCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve:enableWebTicketing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Webを発券可能状態へ設定変更(午前診察開始と午後診察開始))';

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
        $this->formattedLogInfo("Web発券可能状態への設定変更バッチ Start");

        try {

            DB::enableQueryLog();
            $updatedCnt = Setting::where('code','=', 'webTicketable')
                             ->update(['value' => 'true']);
            $queryLog = DB::getQueryLog();

        } catch (Exception $ex) {
            $this->error('SQL実行エラー');
            $this->formattedQueryLog($queryLog);
            var_dump($ex);
        }

        $this->formattedLogInfo("更新件数：$updatedCnt");
        $this->formattedLogInfo("Web発券可能状態への設定変更バッチ End");

    }
}
