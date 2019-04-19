<?php

namespace App\Console\Commands;

use App\Libs\Command\LogCommand;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetNumbering extends LogCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserve:resetNumbering';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '受付番号初期化バッチ(日次起動)';

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

        $this->formattedLogInfo("受付番号初期化バッチ Start");

        try {

            DB::enableQueryLog();
		        DB::statement('TRUNCATE TABLE reserve_seq');
		        DB::statement('ALTER TABLE reserve_seq AUTO_INCREMENT = 1');
            $queryLog = DB::getQueryLog();

        } catch (Exception $ex) {
            $this->error('SQL実行エラー');
            $this->formattedQueryLog($queryLog);
            var_dump($ex);
        }

        $this->formattedLogInfo("受付番号初期化バッチ End");

    }
}
