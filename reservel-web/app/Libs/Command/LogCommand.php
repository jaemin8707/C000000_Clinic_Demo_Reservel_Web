<?php

namespace App\Libs\Command;

use App\Models\Setting;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogCommand extends Command
{

    /**
     * 標準出力へのフォーマット付きログ出力
     *
     * @return mixed
     */
    public function formattedLogInfo($logString) {
        $aryMicroNow = explode('.',microtime(true));
        $microtime = str_pad($aryMicroNow[1], 4, "0", STR_PAD_RIGHT);
        $now = date('Y/m/d H:i:s',$aryMicroNow[0]);

        $this->info("[$now.$microtime] ".env('APP_ENV').".INFO: ".$logString);
    }

    /**
     * 標準出力へのフォーマット付きSQLログ出力
     *
     * @return mixed
     */
    public function formattedQueryLog($queryLogs) {
        foreach($queryLogs as $queryLog) {
            $this->formattedLogInfo("SQL:".$queryLog['query']);
            $this->formattedLogInfo("BINDINGS".json_encode ($queryLog['bindings']));
        }
    }
}
