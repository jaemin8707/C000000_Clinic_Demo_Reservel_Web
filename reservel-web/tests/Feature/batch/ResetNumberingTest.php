<?php

namespace Tests\Feature\batch;

use App\Console\Commands\ResetNumbering;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ResetNumberingTest extends BatchTestCase
{

    protected $name = 'reserve:resetNumbering';

    // 起動対象クラスの取得
    protected function getTargetClass() {
        return new ResetNumbering;         //  起動対象クラス
    }

    public function testCanResetNumbering()
    {
        Log::Debug("受付番号リセット処理テスト Start");

        //発番処理
        $reception_no = DB::table('reserve_seq')->insertGetId([]);
        $this->assertTrue($reception_no==1);

        //発番処理
        $reception_no = DB::table('reserve_seq')->insertGetId([]);
        $this->assertTrue($reception_no==2);

        //リセット処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'受付番号初期化バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'受付番号初期化バッチ End') !== false);

        //発番処理
        $reception_no = DB::table('reserve_seq')->insertGetId([]);
        $this->assertTrue($reception_no==1);

        //発番処理
        $reception_no = DB::table('reserve_seq')->insertGetId([]);
        $this->assertTrue($reception_no==2);

        Log::Debug("受付番号リセット処理テスト End");
    }
}
