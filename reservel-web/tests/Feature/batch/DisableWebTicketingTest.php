<?php

namespace Tests\Feature\batch;

use App\Console\Commands\DisableWebTicketing;

use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DisableWebTicketingTest extends BatchTestCase
{
    protected $name = 'reserve:disableWebTicketing';

    // 起動対象クラスの取得
    protected function getTargetClass() {
        return new DisableWebTicketing;
    }

    public function testCanUpdateDisableTicketing1()
    {
        Log::Debug("Web発券不可状態への設定変更バッチテスト1 Start");

        $setting1 = factory(Setting::class)->create(['code'=>'webTicketable','value'=>"true",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'webTicketable', 'value'=>"true",]);

        // 発券不可状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Web発券不可状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：1') !== false);
        $this->assertTrue(strpos( $result,'Web発券不可状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'webTicketable', 'value'=>"false",]);

        Log::Debug("Web発券不可状態への設定変更バッチテスト1 End");
    }

    public function testCanUpdateDisableTicketing2()
    {
        Log::Debug("Web発券不可状態への設定変更バッチテスト2 Start");

        $setting1 = factory(Setting::class)->create(['code'=>'webTicketable','value'=>"false",]); // 発券不可

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'webTicketable', 'value'=>"false",]);

        // 発券不可状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Web発券不可状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Web発券不可状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'webTicketable', 'value'=>"false",]);

        Log::Debug("Web発券不可状態への設定変更バッチテスト2 End");
    }

}
