<?php

namespace Tests\Feature\api;

use App\Models\OauthClient;
use App\Models\Reserve;
use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ReserveTestApi extends PassortGrantTestCase
{
/*
    public function testCanGetReserveNoData()
    {
        Log::Debug('受付状況データ取得テスト(データなし) Start');

        Log::Debug($this->access_token);

        $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'false',]);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value' => 'false',]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->get('/api/acceptance')
                         ->assertStatus(200);

        $response->assertStatus(200)
                         ->assertJson([
                              'result' => 
                              [
                                  'tabTicketable' => 'false',
                                  'queue'      => [],
                              ],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        Log::Debug('受付状況データ取得テスト(データなし) End');
    }

    public function testCanGetReserveData()
    {
        Log::Debug('受付状況データ取得テスト(正常系) Start');

        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve2 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve2->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve3 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]); // 呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve4 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]); // 呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve4->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]);
        // 以下ゴミデータ
        $reserve5 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]); // 診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve5->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve6 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]); // 診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve6->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve7 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]); // 完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve7->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve8 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]); // 完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve8->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve9 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]); // 患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve9->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve10 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]); // 患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve10->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve11 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]); // 病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve11->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve12 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]); // 病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve12->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        // 過去データ(毎年1月1日にこのテストをすると失敗することがある)
        $reserve101 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-1-1'),]); // 過去データ待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve101->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-1-1'),]);
        $reserve102 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-1-1'),]); // 過去データ待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve102->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-1-1'),]);
        $reserve103 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-1-1'),]); // 過去データ呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve103->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-1-1'),]);
        $reserve104 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-1-1'),]); // 過去データ呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve104->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-1-1'),]);
        $reserve105 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-1-1'),]); // 過去データ診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve105->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-1-1'),]);
        $reserve106 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-1-1'),]); // 過去データ診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve106->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-1-1'),]);
        $reserve107 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-1-1'),]); // 過去データ完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve107->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-1-1'),]);
        $reserve108 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-1-1'),]); // 過去データ完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve108->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-1-1'),]);
        $reserve109 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-1-1'),]); // 過去データ患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve109->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-1-1'),]);
        $reserve110 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-1-1'),]); // 過去データ患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve110->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-1-1'),]);
        $reserve111 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-1-1'),]); // 過去データ病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve111->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-1-1'),]);
        $reserve112 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-1-1'),]); // 過去データ病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve112->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-1-1'),]);

        $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'true',]);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value' => 'true',]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token,
                           ])
                         ->get('/api/acceptance')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => 
                              [
                                  'tabTicketable' => 'true',
                                  'queue'      => [
                                      [
                                          'status' => 30,
                                          'reception_no' => [5, 6],
                                      ],
                                      [
                                          'status' => 20,
                                          'reception_no' => [3, 4],
                                      ],
                                      [
                                          'status' => 10,
                                          'reception_no' => [1, 2],
                                      ]
                                  ],
                              ],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        Log::Debug('受付状況データ取得テスト(正常系) End');
    }

    public function testCanNumbering()
    {
        Log::Debug('受付番号発番処理テスト Start');

        // 発番してみる
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->post('/api/reserve/numbering/1')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => ["number"=> 1,],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        // もっかい発番してみる
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->post('/api/reserve/numbering/1')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => ["number"=> 2,],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        // 再診で発番してみる
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->post('/api/reserve/numbering/2')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => ["number"=> 3,],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        // 再診でもっかい発番してみると見せかけて初診で発番してみる
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->post('/api/reserve/numbering/1')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => ["number"=> 4,],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        // 試しにもっかい再診で発番してみる
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->post('/api/reserve/numbering/2')
                         ->assertStatus(200)
                         ->assertJson([
                              'result' => ["number"=> 5,],
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        Log::Debug('受付番号発番処理テスト End');
    }

    public function testCanUpdateReserveName()
    {
        Log::Debug('名前更新テスト Start');

        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);

        $newNameJson = ['name' => '寿限無長助'];

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),'name'=>'寿限無長助']);

        Log::Debug('名前更新テスト End');
    }

    public function testCanUpdateReserveStatus()
    {
        Log::Debug('ステータス更新テスト Start');

        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);

        // 呼出済
        $newNameJson = ['status' => 20];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);

        // 診察中
        $newNameJson = ['status' => 30];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);

        // 完了
        $newNameJson = ['status' => 40];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);

        // 患者キャンセル
        $newNameJson = ['status' => -1];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);

        // 病院キャンセル
        $newNameJson = ['status' => -2];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);

        // 自動キャンセル
        $newNameJson = ['status' => -3];
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/reserve/1', $newNameJson)
                         ->assertStatus(200)
                         ->assertJson([
                              'status' => ['code' => 0, 'message' => '',  ],
                          ]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>-3,'created_at'=>date('Y-m-d'),]);

        Log::Debug('ステータス更新テスト End');
    }

    public function testCanGetTotalData()
    {
        Log::Debug('総待ち人数取得テスト Start');

        Log::Debug($this->access_token);

        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve2 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]); // 待ち(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve2->id,'reception_no'=>2,'care_type'=>2,'status'=>10,'created_at'=>date('Y-m-d'),]);
        $reserve3 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]); // 呼び出し中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,'reception_no'=>3,'care_type'=>1,'status'=>20,'created_at'=>date('Y-m-d'),]);
        $reserve4 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]); // 呼び出し中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve4->id,'reception_no'=>4,'care_type'=>2,'status'=>20,'created_at'=>date('Y-m-d'),]);
        // 以下ゴミデータ
        $reserve5 = factory(Reserve::class)->create(['reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]); // 診察中(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve5->id,'reception_no'=>5,'care_type'=>1,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve6 = factory(Reserve::class)->create(['reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]); // 診察中(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve6->id,'reception_no'=>6,'care_type'=>2,'status'=>30,'created_at'=>date('Y-m-d'),]);
        $reserve7 = factory(Reserve::class)->create(['reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]); // 完了(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve7->id,'reception_no'=>7,'care_type'=>1,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve8 = factory(Reserve::class)->create(['reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]); // 完了(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve8->id,'reception_no'=>8,'care_type'=>2,'status'=>40,'created_at'=>date('Y-m-d'),]);
        $reserve9 = factory(Reserve::class)->create(['reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]); // 患者キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve9->id,'reception_no'=>9,'care_type'=>1,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve10 = factory(Reserve::class)->create(['reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]); // 患者キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve10->id,'reception_no'=>10,'care_type'=>2,'status'=>-1,'created_at'=>date('Y-m-d'),]);
        $reserve11 = factory(Reserve::class)->create(['reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]); // 病院キャンセル(初診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve11->id,'reception_no'=>11,'care_type'=>1,'status'=>-2,'created_at'=>date('Y-m-d'),]);
        $reserve12 = factory(Reserve::class)->create(['reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]); // 病院キャンセル(再診)
        $this->assertDatabaseHas('reserves', ['id'=>$reserve12->id,'reception_no'=>12,'care_type'=>2,'status'=>-2,'created_at'=>date('Y-m-d'),]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->get('/api/acceptanceCount')
                         ->assertStatus(200);

        $response->assertStatus(200)
                 ->assertJson([
                      'result' => ['total' => 6, 'time' => date('Y/m/d H:i')],
                      'status' => ['code'  => 0, 'message' => '',  ],
                   ]);

        Log::Debug('総待ち人数取得テスト End');
    }
*/
}
