<?php

namespace Tests\Feature\api;

use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ConfigTest extends PassortGrantTestCase
{

    public function testCanGetConfig()
    {

        Log::Debug('設定データ取得テスト Start');
        Log::Debug($this->access_token);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->get('/api/config')
                         ->assertStatus(200)
                         ->assertJson([

                               'result' => 
                               [
                                   "name"   =>"サンプル動物病院",
                                   "tel"   =>"03-6423-6734",
                                   "url"   =>"https://demovet.com/",
                                   "optimes"=>
                                   [
                                       ["stime"=>"09:00","etime"=>"12:00"],
                                       ["stime"=>"16:00","etime"=>"19:00"]
                                   ],
                               ],
                               'status' => ['code' => 0, 'message' => '',  ],

                           ]);

        Log::Debug('設定データ取得テスト End');


    }

    public function testCanUpdateEnableTicketing1()
    {

        Log::Debug('発券可能への更新テスト1 Start');
        Log::Debug($this->access_token);

        $setting1 = factory(Setting::class)->create(['code'=>'ticketable','value'=>"true",]); // 発券可能
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'ticketable', 'value'=>"true",]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept'       => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/config/update', ['ticketable'=>'true'])
                         ->assertStatus(200)
                         ->assertJson([
                               'result' => [],
                               'status' => ['code' => 0, 'message' => '',  ],

                           ]);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'ticketable', 'value'=>"true",]);

        Log::Debug('発券可能への更新テスト1 End');

    }

    public function testCanUpdateEnableTicketing2()
    {

        Log::Debug('発券可能への更新テスト2 Start');
        Log::Debug($this->access_token);

        $setting1 = factory(Setting::class)->create(['code'=>'ticketable','value'=>"false",]); // 発券不可
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'ticketable', 'value'=>"false",]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept'       => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->json('PUT','/api/config/update', ['ticketable'=>'true'])
                         ->assertStatus(200)
                         ->assertJson([
                               'result' => [],
                               'status' => ['code' => 0, 'message' => '',  ],

                           ]);

        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code'=>'ticketable', 'value'=>"true",]);

        Log::Debug('発券可能への更新テスト2 End');

    }

}
