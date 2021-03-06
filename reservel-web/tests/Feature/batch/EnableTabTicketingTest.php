<?php

namespace Tests\Feature\batch;

use App\Console\Commands\EnableTabTicketing;

use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EnableTabTicketingTest extends BatchTestCase
{
    protected $name = 'reserve:enableTabTicketing';

    // 起動対象クラスの取得
    protected function getTargetClass() {
        return new EnableTabTicketing;
    }

    public function testCanUpdateEnableTicketing1()
    {
        Log::Debug("Tab発券可能状態への設定変更バッチテスト1 Start");

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"true",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"true",]);

        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"true",]);

        Log::Debug("Tab発券可能状態への設定変更バッチテスト1 End");
    }

    public function testCanUpdateEnableTicketing2()
    {
        Log::Debug("Tab発券可能状態への設定変更バッチテスト2 Start");

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券不可
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);

        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：1') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"true",]);

        Log::Debug("Tab発券可能状態への設定変更バッチテスト2 End");
    }

}
