<?php

namespace Tests\Feature;

use App\Models\Reserve;
use App\Models\Setting;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


/**
 * 受付状況画面(診察時間の一時停止)表示テスト(11:30) testReverePossibleTime_StopReserve_1
 * 受付状況画面(診察時間の一時停止)表示テスト(17:30) testReverePossibleTime_StopReserve_2
 * 受付状況画面(診察時間外)表示テスト(07:30)        testRevereImpossibleTime_StopReserve_1
 * 受付状況画面(診察時間外)表示テスト(12:30)        testRevereImpossibleTime_StopReserve_2
 * 
 * 
 * 
 */
class ReserveTimeTest extends TestCase
{
    use DatabaseMigrations;

    // 受付状況画面(診察時間の一時停止)表示テスト(11:30)
    public function testReverePossibleTime_StopReserve_1()
    {
        Log::Info('受付状況画面(診察時間の一時停止)表示テスト(10:30) Start');
				Carbon::setTestNow(Carbon::parse('10:30'));

				$setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
				$this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);
				
				$this->get('/')
							->assertStatus(200)
							->assertSee('<title>受付状況 - おおたけ動物病院 - リザベル</title>')
							->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
							->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
							->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>')
							->assertDontSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
							->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
							->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
							->assertSee('<div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
							->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付状況画面(診察時間の一時停止)表示テスト(10:30) End');
		}
		
    // 受付状況画面(診察時間の一時停止)表示テスト(17:30)
		public function testReverePossibleTime_StopReserve_2()
		{
				Log::Info('受付状況画面(診察時間の一時停止)表示テスト(17:30) Start');
				Carbon::setTestNow(Carbon::parse('17:30'));

				$setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
				$this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);
				
				$this->get('/')
							->assertStatus(200)
							 ->assertSee('<title>受付状況 - おおたけ動物病院 - リザベル</title>')
							 ->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
							 ->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
							 ->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							 ->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							 ->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>')
							 ->assertDontSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>')
							 ->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
							 ->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
							 ->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
							 ->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
							 ->assertSee('<div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
							 ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

				Log::Info('受付状況画面(診察時間の一時停止)表示テスト(17:30) End');
		}

		// 受付状況画面(診察時間外)表示テスト(07:30)
		public function testRevereImpossibleTime_StopReserve_1()
		{
				Log::Info('受付状況画面(診察時間外)表示テスト(07:30) Start');
				Carbon::setTestNow(Carbon::parse('07:30'));

				$setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
				$this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);
				
				$this->get('/')
							->assertStatus(200)
							->assertSee('<title>受付状況 - おおたけ動物病院 - リザベル</title>')
							->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
							->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
							->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertDontSee('<span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>')
							->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
							->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
							->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
							->assertSee('<div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
							->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

				Log::Info('受付状況画面(診察時間の一時停止)表示テスト(07:30) End');
		}

		// 受付状況画面(診察時間外)表示テスト(12:30)
		public function testRevereImpossibleTime_StopReserve_2()
		{
				Log::Info('受付状況画面(診察時間外)表示テスト(12:30) Start');
				Carbon::setTestNow(Carbon::parse('12:30'));

				$setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
				$this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);
				
				$this->get('/')
							->assertStatus(200)
							->assertSee('<title>受付状況 - おおたけ動物病院 - リザベル</title>')
							->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
							->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
							->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertDontSee('<span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>')
							->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
							->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
							->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
							->assertSee('<div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
							->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

				Log::Info('受付状況画面(診察時間の一時停止)表示テスト(11:30) End');
		}

		// 受付状況画面(診察時間外)表示テスト(20:30)
		public function testRevereImpossibleTime_StopReserve_3()
		{
				Log::Info('受付状況画面(診察時間外)表示テスト(20:30) Start');
				Carbon::setTestNow(Carbon::parse('20:30'));

				$setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
				$this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);
				
				$this->get('/')
							->assertStatus(200)
							->assertSee('<title>受付状況 - おおたけ動物病院 - リザベル</title>')
							->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
							->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
							->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
							->assertDontSee('<span class="receptionMsg" style="color:red;font-weight:900;">検査中につき診察を一時中断しております。<br>少々お待ちください。</span>')
							->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいまの時間は受付を行っておりません。</span>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
							->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
							->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
							->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
							->assertSee('<div class="notice">※ネットでの受付は午前9:00～11:30　午後16:00～18:30とさせていただきます。<br>※営業終了時刻(午前の部 12:00、午後の部 19:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
							->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

				Log::Info('受付状況画面(診察時間の一時停止)表示テスト(20:30) End');
		}

}
