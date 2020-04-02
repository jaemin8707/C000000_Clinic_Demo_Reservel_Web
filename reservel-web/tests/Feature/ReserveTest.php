<?php

namespace Tests\Feature;

use App\Models\Reserve;
use App\Models\Setting;
use App\Models\PetType;
use App\Models\Purpose;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ReserveTest extends TestCase
{
    use DatabaseMigrations;

    // 受付状況画面表示テスト
    public function testCanView_indexPage()
    {
        Log::Info('受付状況画面表示テスト Start');

        $curdate = date('Y/m/d');
        $this->get('/')
             ->assertStatus(200)
             ->assertSee('<title>受付状況 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付状況</h1>')
             ->assertSee('<div class="time">'.$curdate.' ')
             ->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">0</span><span class="bold">人</span> </div>')
             ->assertSee('<div class="label">初診 <span class="count"> 0 </span><span class="bold">人</span></div>')
             ->assertSee('<div class="label">再診 <span class="count"> 0 </span><span class="bold">人</span></div>')
             ->assertSee('<form method="GET" action="http://localhost/reserve/create/1">')
             ->assertSee('<button class="btn_first"   accesskey="1">初診受付</button>')
             ->assertSee('<form method="GET" action="http://localhost/reserve/create/2">')
             ->assertSee('<button class="btn_regular" accesskey="2">再診受付</button>')
             ->assertSee('<form method="GET" action="http://localhost/reserve/create/9">')
             ->assertSee('<button class="btn_etc" accesskey="9">その他</button>')
             ->assertSee('<div class="notice">※ネットでの受付は午前00:00～00:00　午後00:00～00:00とさせていただきます。<br>※診療終了時刻(午前の部 00:00、午後の部 00:00)までにご来院いただけなかった方はキャンセルとさせていただきます。</div>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('受付状況画面表示テスト End');
    }

    // 受付状況画面表示テスト(初診2人、再診1人、受付可)
    public function testCanView_indexPage2()
    {
        Log::Info('受付状況画面表示テスト(初診2人、再診1人、その他1人) Start');

        $curdate = date('Y/m/d');
        $reserve1 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>20,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'reception_no'=>1,'care_type'=>1,'status'=>20,]);
        $reserve2 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>30,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve2->id,'reception_no'=>1,'care_type'=>1,'status'=>30,]);
        $reserve3 = factory(Reserve::class)->create(['reception_no'=>1,'care_type'=>1,'status'=>40,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,'reception_no'=>1,'care_type'=>1,'status'=>40,]);
        $reserve4 = factory(Reserve::class)->create(['reception_no'=>2,'care_type'=>1,'status'=>10,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve4->id,'reception_no'=>2,'care_type'=>1,'status'=>10,]);
        $reserve5 = factory(Reserve::class)->create(['reception_no'=>3,'care_type'=>2,'status'=>10,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve5->id,'reception_no'=>3,'care_type'=>2,'status'=>10,]);
        $reserve6 = factory(Reserve::class)->create(['reception_no'=>4,'care_type'=>9,'status'=>10,]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve6->id,'reception_no'=>4,'care_type'=>9,'status'=>10,]);

        $setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'true',]);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'true',]);

        $this->get('/')
             ->assertStatus(200)
             ->assertSee('<title>受付状況 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付状況</h1>')
             ->assertSee('<div class="time">'.$curdate.' ')
             ->assertSee('<div class="total"> <span>現在の待ち人数　</span><span id="totalCnt">6</span><span class="bold">人</span> </div>')
             ->assertSee('<div class="label">初診 <span class="count"> 4 </span><span class="bold">人</span></div>')
             ->assertSee('<li class="called" ><span>1</span></li>')
             ->assertSee('<li ><span>4</span></li>')
             ->assertSee('<div class="label">再診 <span class="count"> 1 </span><span class="bold">人</span></div>')
             ->assertSee('<li ><span>3</span></li>')
             ->assertSee('<div class="label">その他<span class="count">1</span><span class="bold">人</span></div>')
             ->assertSee('<li ><span>4</span></li>')
             ->assertSee('<button class="btn_first"   accesskey="1">初診受付</button>')
             ->assertSee('<button class="btn_regular" accesskey="2">再診受付</button>')
             ->assertSee('<button class="btn_etc" accesskey="9">その他</button>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('受付状況画面表示テスト(初診2人、再診1人) End');
    }

    // 受付状況画面表示テスト(受付不可)
    public function testCanView_indexPage3()
    {
        Log::Info('受付状況画面表示テスト(受付不可) Start');
        Carbon::setTestNow(Carbon::parse('05:30'));
        $setting = factory(Setting::class)->create(['code'=>'webTicketable','value'=>'false',]);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id,'code'=>'webTicketable','value'=>'false',]);

        $this->get('/')
             ->assertStatus(200)
             ->assertSee('<title>受付状況 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付状況</h1>')
             ->assertSee('<span class="receptionMsg" style="color:red;font-weight:900;">ただいま、受付を行っておりません。</span>')
             ->assertSee('<button class="btn_first"   accesskey="1" disabled >初診受付</button>')
             ->assertSee('<button class="btn_regular" accesskey="2" disabled >再診受付</button>')
             ->assertSee('<button class="btn_etc" accesskey="9" disabled >その他</button>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('受付状況画面表示テスト(受付不可) End');
    }

    // 初診受付申込画面表示テスト
    public function testCanView_createPage_typeFiest()
    {
        Log::Info('初診受付申込画面表示テスト Start');

        $this->get('/reserve/create/1')
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicFirst">初診受付申し込み</div>')
             ->assertSee('/reserve/confirm" method="POST">')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>初診</span></div>')
             ->assertSee('受診される方のお名前')
             ->assertSee('年齢')
             ->assertSee('性別')
             ->assertSee('メールアドレス')
             ->assertSee('電話番号')
             ->assertSee('症状など')
             ->assertSee('/index" class="btn_cancel" accesskey="c">キャンセル</a>')
             ->assertSee('<p class="privacy_attention_prompt">上記事項をご確認の上、ご同意いただける方は下の「同意して次へ」をクリックしてください。</p>')
             ->assertSee('<button type="submit" id="btn_execution" class="btn_execution" accesskey="e">同意して次へ</button')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('初診受付申込画面表示テスト End');
    }

    // 初診受付申込確認画面表示テスト
    public function testCanView_confirmPage_typeFiest()
    {
        Log::Info('初診受付申込確認画面表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '123456',
                        'name'        => '動物　太郎',
                        'email'       => 'm-fujisawa@it-craft.co.jp',
                        'tel'         => '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicFirst">初診受付申し込み</div>')
             ->assertSee('/reserve" method="POST" onSubmit="return double()">')
             ->assertSee('<input type="hidden" name="careType"        value="1" />')
             ->assertSee('<input type="hidden" name="patient_no" value="123456" />')
             ->assertSee('<input type="hidden" name="name"            value="動物　太郎" />')
             ->assertSee('<input type="hidden" name="age"             value="23" />')
             ->assertSee('<input type="hidden" name="gender"          value="1" />')
             ->assertSee('<input type="hidden" name="email"           value="m-fujisawa@it-craft.co.jp" />')
             ->assertSee('<input type="hidden" name="tel"             value="0331234567" />')
             ->assertSee('<input type="hidden" name="pet_symptom"     value="おもちゃを飲み込んだ" />')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>初診</span></div>')
             ->assertSee('<dt class="required"><span>受診される方のお名前</span></dt>')
             ->assertSee('<dd><span>動物　太郎</span></dd>')
             ->assertSee('<dt class="required"><span>メールアドレス</span></dt>')
             ->assertSee('<dd><span>m-fujisawa@it-craft.co.jp</span></dd>')
             ->assertSee('<dt class="required"><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><span>0331234567</span></dd>')
             ->assertSee('<dt><span>症状など</span></dt>')
             ->assertSee('<dd><span class="symptom">おもちゃを飲み込んだ</span></dd>')
             ->assertSee('<a href="#" class="btn_cancel" onclick="javascript:window.history.back(-1);return false;">戻　る</a>')
             ->assertSee('<button type="submit" class="btn_execution">受　付</button>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('初診受付申込確認画面表示テスト End');
    }

    // 初診受付申込完了画面表示テスト
    public function testCanReserve_typeFiest()
    {

        Log::Info('初診受付申込完了画面表示テスト Start');
        Mail::fake();
        Mail::assertNothingSent();

        $this->post('/reserve',[
                        'id'              => 1,
                        'careType'        => 1,
                        'patient_no' => '123456',
                        'name'            => '動物　太郎',
                        'email'           => 'm-fujisawa@it-craft.co.jp',
                        'tel'             => '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom'     => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/complete?careType=1&receptionNo=1');

        $this->get('/reserve/complete?careType=1&receptionNo=1')
             ->assertStatus(200)
             ->assertSee('<title>受付完了 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付完了</h1>')
             ->assertSee('<div class="comprete_title">受付が完了しました</div>')
             ->assertSee('<div class="comprete_clinictype">受付区分：初診</div>')
             ->assertSee('<div class="comprete_number">受付番号：1</div>')
             ->assertSee('ご記入いただいたメールアドレス宛に<br class="br-u600">受付完了メールを送信しました。<br/>')
             ->assertSee('※メールが届かないお客様へ')
             ->assertSee('受付番号が記載されていますので、<br class="br-u600">ご確認ください。')
             ->assertSee('/index">受付状況トップ画面に戻る</a></div>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        // 登録されたことを確認
        $this->assertDatabaseHas('reserves', ['id'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>'123456','name'=>'動物　太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0331234567', 'conditions'=>'おもちゃを飲み込んだ',]);

        // 1回送信されたことをアサート
        Mail::assertSent(\App\Mail\ReserveMail::class, 1);

        // メールが指定したユーザーに送信されていることをアサート
        Mail::assertSent(
            \App\Mail\ReserveMail::class,
            function ($mail)  {
                return $mail->to[0]['address'] === 'm-fujisawa@it-craft.co.jp';
            }
        );

        Log::Info('初診受付申込完了画面表示テスト End');
    }

    // 再診受付申込画面表示テスト
    public function testCanView_createPage_typeRepeat()
    {

        Log::Info('再診受付申込画面表示テスト Start');

        $this->get('/reserve/create/2')
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicRepeat">再診受付申し込み</div>')
             ->assertSee('/reserve/confirm" method="POST">')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>再診</span></div')
             ->assertSee('受診される方のお名前')
             ->assertSee('年齢')
             ->assertSee('性別')
             ->assertSee('メールアドレス')
             ->assertSee('電話番号')
             ->assertSee('症状など')
             ->assertSee('/index" class="btn_cancel" accesskey="c">キャンセル</a>')
             ->assertSee('<p class="privacy_attention_prompt">上記事項をご確認の上、ご同意いただける方は下の「同意して次へ」をクリックしてください。</p>')
             ->assertSee('<button type="submit" id="btn_execution" class="btn_execution" accesskey="e">同意して次へ</button')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('再診受付申込画面表示テスト End');
    }

    // 再診受付申込確認画面表示テスト
    public function testCanView_confirmPage_typeRepeat()
    {
        Log::Info('再診受付申込確認画面表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        		=> '動物　太郎',
                        'email'       		=> 'm-fujisawa@it-craft.co.jp',
                        'tel'         		=> '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom' 		=> 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicRepeat">再診受付申し込み</div>')
             ->assertSee('/reserve" method="POST" onSubmit="return double()">')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>再診</span></div>')
             ->assertSee('<dt class="required"><span>受診される方のお名前</span></dt>')
             ->assertSee('<dd><span>動物　太郎</span></dd>')
             ->assertSee('<dt class="required"><span>メールアドレス</span></dt>')
             ->assertSee('<dd><span>m-fujisawa@it-craft.co.jp</span></dd>')
             ->assertSee('<dt class="required"><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><span>0331234567</span></dd>')
             ->assertSee('<dt><span>症状など</span></dt>')
             ->assertSee('<dd><span class="symptom">おもちゃを飲み込んだ</span></dd>')
             ->assertSee('<a href="#" class="btn_cancel" onclick="javascript:window.history.back(-1);return false;">戻　る</a>')
             ->assertSee('<button type="submit" class="btn_execution">受　付</button>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('再診受付申込確認画面表示テスト End');
    }

    // 再診受付申込完了画面表示テスト
    public function testCanReserve_typeRepeat()
    {

        Log::Info('再診受付申込完了画面表示テスト Start');

        Mail::fake();
        Mail::assertNothingSent();

        $this->post('/reserve',[
                        'id'              => 1,
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'            => '動物　太郎',
                        'email'           => 'm-fujisawa@it-craft.co.jp',
                        'tel'             => '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom'     => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/complete?careType=2&receptionNo=1');

        $this->get('/reserve/complete?careType=2&receptionNo=1')
             ->assertStatus(200)
             ->assertSee('<title>受付完了 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付完了</h1>')
             ->assertSee('<div class="comprete_title">受付が完了しました</div>')
             ->assertSee('<div class="comprete_clinictype">受付区分：再診</div>')
             ->assertSee('<div class="comprete_number">受付番号：1</div>')
             ->assertSee('ご記入いただいたメールアドレス宛に<br class="br-u600">受付完了メールを送信しました。<br/>')
             ->assertSee('※メールが届かないお客様へ')
             ->assertSee('受付番号が記載されていますので、<br class="br-u600">ご確認ください。')
             ->assertSee('/index">受付状況トップ画面に戻る</a></div>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        // 登録されたことを確認
        $this->assertDatabaseHas('reserves', ['id'=>1,'care_type'=>2,'status'=>10,'medical_card_no'=>'123456','name'=>'動物　太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0331234567', 'conditions'=>'おもちゃを飲み込んだ',]);


        // 1回送信されたことをアサート
        Mail::assertSent(\App\Mail\ReserveMail::class, 1);

        // メールが指定したユーザーに送信されていることをアサート
        Mail::assertSent(
            \App\Mail\ReserveMail::class,
            function ($mail)  {
                return $mail->to[0]['address'] === 'm-fujisawa@it-craft.co.jp';
            }
        );

        Log::Info('再診受付申込完了画面表示テスト End');
    }

    // その他受付申込画面表示テスト
    public function testCanView_createPage_typeEtc()
    {

        Log::Info('その他受付申込画面表示テスト Start');

        $this->get('/reserve/create/9')
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicEtc">その他受付申し込み</div>')
             ->assertSee('/reserve/confirm" method="POST">')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>その他</span></div')
             ->assertSee('受診される方のお名前')
             ->assertSee('年齢')
             ->assertSee('性別')
             ->assertSee('メールアドレス')
             ->assertSee('電話番号')
             ->assertSee('症状など')
             ->assertSee('/index" class="btn_cancel" accesskey="c">キャンセル</a>')
             ->assertSee('<button type="submit" id="btn_execution" class="btn_execution" accesskey="e">同意して次へ</button')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('その他受付申込画面表示テスト End');
    }

    // その他受付申込確認画面表示テスト
    public function testCanView_confirmPage_typeEtc()
    {
        Log::Info('その他受付申込確認画面表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 9,
                        'patient_no' => '123456',
                        'name'        		=> '医療　太郎',
                        'email'       		=> 'm-fujisawa@it-craft.co.jp',
                        'tel'         		=> '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom' 		=> 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(200)
             ->assertSee('<title>Web受付 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　Web受付</h1>')
             ->assertSee('<div class="clinicType clinicEtc">その他受付申し込み</div>')
             ->assertSee('/reserve" method="POST" onSubmit="return double()">')
             ->assertSee('<div class="type"><span>受付区分</span>：<span>その他</span></div>')
             ->assertSee('<dt class="required"><span>受診される方のお名前</span></dt>')
             ->assertSee('<dd><span>医療　太郎</span></dd>')
             ->assertSee('<dt class="required"><span>年齢</span></dt>')
             ->assertSee('<dt class="required"><span>性別</span></dt>')
             ->assertSee('<dt class="required"><span>メールアドレス</span></dt>')
             ->assertSee('<dd><span>m-fujisawa@it-craft.co.jp</span></dd>')
             ->assertSee('<dt class="required"><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><span>0331234567</span></dd>')
             ->assertSee('<dt><span>症状など</span></dt>')
             ->assertSee('<dd><span class="symptom">おもちゃを飲み込んだ</span></dd>')
             ->assertSee('<a href="#" class="btn_cancel" onclick="javascript:window.history.back(-1);return false;">戻　る</a>')
             ->assertSee('<button type="submit" class="btn_execution">受　付</button>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        Log::Info('その他受付申込確認画面表示テスト End');
    }

    // その他受付申込完了画面表示テスト
    public function testCanReserve_typeEtc()
    {

        Log::Info('その他受付申込完了画面表示テスト Start');

        Mail::fake();
        Mail::assertNothingSent();

        $this->post('/reserve',[
                        'id'              => 1,
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'            => '動物　太郎',
                        'email'           => 'm-fujisawa@it-craft.co.jp',
                        'tel'             => '0331234567',
                        'age'    => '23',
                        'gender'    => '1',
                        'pet_symptom'     => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/complete?careType=2&receptionNo=1');

        $this->get('/reserve/complete?careType=9&receptionNo=1')
             ->assertStatus(200)
             ->assertSee('<title>受付完了 - サンプル動物病院 - リザベル</title>')
             ->assertSee('<h1>サンプル動物病院　受付完了</h1>')
             ->assertSee('<div class="comprete_title">受付が完了しました</div>')
             ->assertSee('<div class="comprete_clinictype">受付区分：その他</div>')
             ->assertSee('<div class="comprete_number">受付番号：1</div>')
             ->assertSee('ご記入いただいたメールアドレス宛に<br class="br-u600">受付完了メールを送信しました。<br/>')
             ->assertSee('※メールが届かないお客様へ')
             ->assertSee('受付番号が記載されていますので、<br class="br-u600">ご確認ください。')
             ->assertSee('/index">受付状況トップ画面に戻る</a></div>')
             ->assertSee('<p>Copyright &copy; reservel All Rights Reserved.</p>');

        // 登録されたことを確認
        $this->assertDatabaseHas('reserves', ['id'=>1,'care_type'=>2,'status'=>10,'medical_card_no'=>'123456','name'=>'動物　太郎','age' => '23', 'gender' => '1', 'email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0331234567', 'conditions'=>'おもちゃを飲み込んだ',]);


        // 1回送信されたことをアサート
        Mail::assertSent(\App\Mail\ReserveMail::class, 1);

        // メールが指定したユーザーに送信されていることをアサート
        Mail::assertSent(
            \App\Mail\ReserveMail::class,
            function ($mail)  {
                return $mail->to[0]['address'] === 'm-fujisawa@it-craft.co.jp';
            }
        );

        Log::Info('その他受付申込完了画面表示テスト End');
    }

}
