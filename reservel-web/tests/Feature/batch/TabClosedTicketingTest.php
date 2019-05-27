<?php

namespace Tests\Feature\batch;

use App\Console\Commands\EnableTabTicketing;

use App\Models\Setting;
use App\Models\Closed;

use Carbon\Carbon;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
/**
 * Tab
 * 休診区分：午前、バッチ実行：午前　testTabClosedMonring_true
 * 休診区分：午前、バッチ実装：午後　testTabClosedMonring_false
 * 休診区分：午後、バッチ実装：午後　testTabClosedafternoon_true
 * 休診区分：午後、バッチ実装：午前　testTabClosedafternoon_false
 * 休診区分：全日、バッチ実装：午前　testTabClosedallday_morning
 * 休診区分：全日、バッチ実装：午後　testTabClosedallday_afternoon
 * 
 * 
 * 
 */
class TabClosedTicketingTest extends BatchTestCase
{
    protected $name = 'reserve:enableTabTicketing';

    // 起動対象クラスの取得
    protected function getTargetClass() {
        return new EnableTabTicketing;
    }
    
    //休診区分：午前、バッチ実行：午前
    public function testTabClosedMonring_true()
    {
        Log::Debug("Tab発券可能休診日不可のまま(午前) Start");
        Carbon::setTestNow(Carbon::parse('08:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>1,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>1,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,"休診日：$curdate, 休診区分：午前") !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);

        Log::Debug("Tab発券可能休診日不可のまま(午前) End");
    }

    //休診区分：午前、バッチ実装：午後
    public function testTabClosedMonring_false()
    {
        Log::Debug("Tab発券可能休診日更新(午前) Start");
        Carbon::setTestNow(Carbon::parse('11:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>1,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>1,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：1') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"true",]);

        Log::Debug("Tab発券可能休診日更新(午前) End");
    }

    //休診区分：午後、バッチ実装：午後
    public function testTabClosedAfternoon_true()
    {
        Log::Debug("Tab発券可能休診日(午後) Start");
        Carbon::setTestNow(Carbon::parse('15:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>2,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>2,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,"休診日：$curdate, 休診区分：午後") !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);

        Log::Debug("Tab発券可能休診日(午後) End");
    }

    //休診区分：午後、バッチ実装：午前
    public function testTabClosedAfternoon_false()
    {
        Log::Debug("Tab発券可能休診日(午後) Start");
        Carbon::setTestNow(Carbon::parse('08:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>2,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>2,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,'更新件数：1') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"true",]);

        Log::Debug("Tab発券可能休診日(午後) End");
    }
    
    //休診区分：全日、バッチ実装：午前　testTabClosedallday_morning
    public function testTabClosedAllday_morning()
    {
        Log::Debug("Tab発券可能休診日(全日) Start");
        Carbon::setTestNow(Carbon::parse('08:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>3,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>3,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,"休診日：$curdate, 休診区分：全日") !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);

        Log::Debug("Tab発券可能休診日(全日) End");
    }
    
    //休診区分：全日、バッチ実装：午後　testTabClosedallday_afternoon
    public function testTabClosedAllday_afternoon()
    {
        Log::Debug("Tab発券可能休診日(全日) Start");
        Carbon::setTestNow(Carbon::parse('11:45'));

        $setting1 = factory(Setting::class)->create(['code'=>'tabTicketable','value'=>"false",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);
        
        $curdate = Carbon::today()->format('Y-m-d');
        $closed = factory(Closed::class)->create(['closed_day'=>$curdate,'closed_type'=>3,]);
        $this->assertDatabaseHas('closed', ['id'=>$closed->id,'closed_day'=>$curdate,'closed_type'=>3,]);


        // 発券可能状態への設定変更処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ Start') !== false);
        $this->assertTrue(strpos( $result,"休診日：$curdate, 休診区分：全日") !== false);
        $this->assertTrue(strpos( $result,'更新件数：0') !== false);
        $this->assertTrue(strpos( $result,'Tab発券可能状態への設定変更バッチ End') !== false);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'tabTicketable', 'value'=>"false",]);

        Log::Debug("Tab発券可能休診日(全日) End");
    }

}
