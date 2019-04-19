<?php

namespace Tests\Feature\batch;

use App\Models\Reserve;
use App\Console\Commands\TimeupCalled;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TimeupCalledTest extends BatchTestCase
{
    protected $name = 'reserve:timeupCalled';

    // 起動対象クラスの取得
    protected function getTargetClass() {
        return new TimeupCalled;
    }

    public function testCanUpdateCancelStatus()
    {
        Log::Debug(" 呼び出し済み自動キャンセルバッチテスト Start");

        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve2 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve2->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve3 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve4 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve4->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve5 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve5->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve6 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve6->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve7 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve7->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve8 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve8->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve9 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve9->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve10 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve10->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve11 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve11->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve12 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // 病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve12->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve13 = factory(Reserve::class)->create(['reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // バッチキャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve13->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        $reserve14 = factory(Reserve::class)->create(['reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s'),]); // バッチキャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve14->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        // 過去データ
        $reserve101 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('2019-1-1'),]); // 過去データ待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve101->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('2019-1-1'),]);
        $reserve102 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('2019-1-1'),]); // 過去データ待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve102->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('2019-1-1'),]);
        $reserve103 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('2019-1-1'),]); // 過去データ呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve103->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('2019-1-1'),]);
        $reserve104 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('2019-1-1'),]); // 過去データ呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve104->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('2019-1-1'),]);
        $reserve105 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('2019-1-1'),]); // 過去データ診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve105->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('2019-1-1'),]);
        $reserve106 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('2019-1-1'),]); // 過去データ診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve106->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('2019-1-1'),]);
        $reserve107 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('2019-1-1'),]); // 過去データ完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve107->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('2019-1-1'),]);
        $reserve108 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('2019-1-1'),]); // 過去データ完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve108->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('2019-1-1'),]);
        $reserve109 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('2019-1-1'),]); // 過去データ患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve109->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('2019-1-1'),]);
        $reserve110 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('2019-1-1'),]); // 過去データ患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve110->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('2019-1-1'),]);
        $reserve111 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('2019-1-1'),]); // 過去データ病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve111->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('2019-1-1'),]);
        $reserve112 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('2019-1-1'),]); // 過去データ病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve112->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('2019-1-1'),]);
        $reserve113 = factory(Reserve::class)->create(['reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('2019-1-1'),]); // 過去データバッチキャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve113->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('2019-1-1'),]);
        $reserve114 = factory(Reserve::class)->create(['reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('2019-1-1'),]); // 過去データバッチキャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve114->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('2019-1-1'),]);
        // 過去データ(2時間前)
        $reserve201 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve201->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve202 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve202->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve203 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve203->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve204 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve204->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve205 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve205->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve206 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve206->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve207 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve207->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve208 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve208->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve209 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve209->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve210 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve210->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve211 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve211->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve212 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // 病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve212->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve213 = factory(Reserve::class)->create(['reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // バッチキャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve213->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        $reserve214 = factory(Reserve::class)->create(['reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),'call_time'=>date('Y/m/d H:i:s',strtotime("-2 hour")),]); // バッチキャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve214->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),]);

        // 呼び出し済み自動キャンセル処理
        $result = $this->execute();

        // 画面出力のテスト
        $this->assertTrue(strpos($result,' 呼び出し済み自動キャンセルバッチ Start') !== false);
        $this->assertTrue(strpos($result,'キャンセルにした件数：2') !== false);
        $this->assertTrue(strpos($result,'呼び出し済み自動キャンセルバッチ End') !== false);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve2->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve4->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve5->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve6->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve7->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve8->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve9->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve10->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve11->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve12->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve13->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve14->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        // 過去データ
        $this->assertDatabaseHas('reserves', ['id'=>$reserve101->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve102->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve103->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve104->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve105->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve106->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve107->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve108->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve109->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve110->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve111->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve112->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve113->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('2019-1-1'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve114->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('2019-1-1'),]);
        // 過去データ(2時間前)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve201->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve202->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve203->id,'reception_no'=>3,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]); // ステータス更新(20→-3)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve204->id,'reception_no'=>4,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),]); // ステータス更新(20→-3)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve205->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve206->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve207->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve208->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve209->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve210->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve211->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve212->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve213->id,'reception_no'=>13,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve214->id,'reception_no'=>14,'care_type'=>2,'status'=>-3,'created_at'=>date('Y-m-d'),]);


        Log::Debug(" 呼び出し済み自動キャンセルバッチテスト End");
    }
}
