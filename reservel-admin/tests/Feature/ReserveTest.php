<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Reserve;
use App\Models\PetType;
use App\Models\Setting;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ReserveTest extends TestCase
{

    use DatabaseMigrations;

    // 受付状況画面表示テスト(未ログイン)
    public function testCannotView_indexPage()
    {
        // 表示できないことをテスト
        $this->get('/')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト

        // 表示できないことをテスト
        $this->get('/reserve')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト
    }

    // 受付状況画面表示テスト(患者ゼロ)
    public function testCanView_indexPage_noData()
    {
        Log::Info('受付状況画面表示テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $setting1 = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'true']);
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code' => 'tabTicketable', 'value'=>'true']);
        $setting2 = factory(Setting::class)->create(['code' => 'webTicketable', 'value' => 'true']);
        $this->assertDatabaseHas('settings', ['id'=>$setting2->id, 'code' => 'webTicketable', 'value'=>'true']);

        $curdate = date('Y/m/d');
        $this->get('/reserve')
             ->assertStatus(200)
             ->assertSee('<title>受付状況一覧 - 管理画面 - おおたけ動物病院 - リザベル</title>')
             ->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
             ->assertSee('<span class="time">'.$curdate.' ')
             ->assertSee('<li><div>本日の待ち患者は、まだいません。</div></li>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付状況画面表示テスト End');
    }
    
    // 受付状況画面表示テスト(患者あり)
    public function testCanView_indexPage()
    {
        Log::Info('受付状況画面表示テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $curdate = date('Y/m/d');
        $reserve1 = factory(Reserve::class)->create(['place'=>1,
        'reception_no'=>1,
        'care_type'=>1,
        'status'=>10,
        'name'=>'一番太郎',
        'tel'=>'0123456789',
        'pet_name'=>'ジロー',
        'conditions'=>'腕の骨折',
        'medical_card_no'=>null,
        'created_at'=>date('Y-m-d'),]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1->id,
          'place'=>1,
          'reception_no'=>1,
          'care_type'=>1,
          'status'=>10,
          'name'=>'一番太郎',
          'tel'=>'0123456789',
          'pet_name'=>'ジロー',
          'conditions'=>'腕の骨折',
          'created_at'=>date('Y-m-d'),]);
        
        $call_date_called = date('Y-m-d H:i:s');
        $reserve2 = factory(Reserve::class)->create([
          'place'=>2,
          'reception_no'=>2,
          'care_type'=>1,
          'status'=>20,
          'name'=>'二番次郎',
          'tel'=>'1123456789',
          'pet_name'=>'サブロー',
          'conditions'=>'足の骨折',
          'call_time'=>$call_date_called,
          'medical_card_no'=>'123',
          'created_at'=>date('Y-m-d'),]);
          factory(PetType::class)->create(['reserve_id'=>$reserve2->id, 'pet_type' => 1]);
          $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve2->id,'pet_type' => 1]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve2->id,
          'place'=>2,
          'reception_no'=>2,
          'care_type'=>1,
          'status'=>20,
          'name'=>'二番次郎',
          'tel'=>'1123456789',
          'pet_name'=>'サブロー',
          'medical_card_no'=>'123',
          'conditions'=>'足の骨折',
          'call_time'=>$call_date_called,
          'created_at'=>date('Y-m-d'),]);

        $call_date_done = date('Y-m-d H:i:s', strtotime('-1hour'));
        $reserve3 = factory(Reserve::class)->create([
          'place'=>1,
          'reception_no'=>3,
          'care_type'=>2,
          'status'=>30,
          'name'=>'三番三郎',
          'tel'=>'2123456789',
          'pet_name'=>'シロー',
          'conditions'=>'腰の骨折',
          'call_time' => $call_date_done,
          'created_at'=>date('Y-m-d'),]);
          factory(PetType::class)->create(['reserve_id'=>$reserve3->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve3->id,'pet_type' => 1]);
        $this->assertDatabaseHas('reserves', ['id'=>$reserve3->id,
          'place'=>1,'reception_no'=>3,
          'care_type'=>2,
          'status'=>30,
          'name'=>'三番三郎',
          'tel'=>'2123456789',
          'pet_name'=>'シロー',
          'conditions'=>'腰の骨折',
          'call_time' => $call_date_done,
          'created_at'=>date('Y-m-d'),]);

        $setting1 = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'true']);
        $this->assertDatabaseHas('settings', ['id'=>$setting1->id, 'code' => 'tabTicketable', 'value'=>'true']);
        $setting2 = factory(Setting::class)->create(['code' => 'webTicketable', 'value' => 'true']);
        $this->assertDatabaseHas('settings', ['id'=>$setting2->id, 'code' => 'webTicketable', 'value'=>'true']);
  

        $this->get('/reserve')
             ->assertStatus(200)
             ->assertSee('<title>受付状況一覧 - 管理画面 - おおたけ動物病院 - リザベル</title>')
             ->assertSee('<h1>おおたけ動物病院　受付状況</h1>')
             ->assertSee('<span class="time">'.$curdate.' ')
             ->assertDontSee('<li><div>本日の待ち患者は、まだいません。</div></li>')

             ->assertSee('<div class="a_number">1</div>')
             ->assertSee('<div class="a_status"><span>待ち</span></div>')
             
             ->assertSee('<div class="a_reserve_time">--:--</div>')
             ->assertSee('<button class="btn_status_call" type="submit" name="status" value="20">呼出済みに変更</button>')
             ->assertSee('<div class="a_c_num">ジロー</div>')
             ->assertSee('<div class="a_patient_no"></div>')
             ->assertSee('<div class="a_name"><span>一番太郎</span>')

             ->assertSee('<div class="a_number">2</div>')
             ->assertSee('<div class="a_status"><span>呼出済み</span></div>')
             //ITC-19ステータス変更追記
             ->assertSee('<div class="a_reserve_time">'.date('H:i', strtotime($call_date_called)).'</div>')
             ->assertSee('<button class="btn_status_examination" type="submit" name="status" value="30">診察中に変更</button>')
             ->assertSee('<div class="a_c_num">サブロー</div>')
             ->assertSee('<div class="a_patient_no">123</div>')
             ->assertSee('<div class="a_name"><span>二番次郎</span>')

             ->assertSee('<div class="a_number">3</div>')
             ->assertSee('<div class="a_status"><span>診察中</span></div>')
             //ITC-19ステータス変更追記
             ->assertSee('<div class="a_reserve_time">--:--</div>')
             ->assertDontSee('<div class="a_reserve_time">'.date('H:i', strtotime($call_date_done)).'</div>')
             ->assertSee('<button class="btn_status" type="submit" name="status" value="40">完了に変更</button>')
             ->assertSee('<div class="a_c_num">シロー</div>')
             ->assertSee('<div class="a_patient_no"></div>')
             ->assertSee('<div class="a_name"><span>三番三郎</span>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付状況画面表示テスト End');
    }

    // 受付状況画面からステータス変更テスト(患者あり)
    public function testCan_indexStatusWAITINGtoCALLUpdate()
    {
      Log::Info('受付状況画面からステータス(待ち→呼出)変更テスト Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_date = date('Y-m-d H:i:s');
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折','medical_card_no'=>null,
        'created_at'=>date('Y-m-d'),]);
      factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
      $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折',
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '20'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト

      $this->assertDatabaseHas('reserves', [
              'id'=>$reserve1->id,
              'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>20,
              'name'=>'一番太郎','tel'=>'0123456789',
              'pet_name'=>'ジロー','conditions'=>'腕の骨折',
              'call_time'=>$call_date,
              'created_at'=>date('Y-m-d'),]);
      Log::Info('受付状況画面からステータス(待ち→呼出)変更テスト End');
    }


    // 受付状況画面からステータス変更テスト(患者あり)
    public function testCan_indexStatusCALLtoEXAMINEUpdate()
    {
      Log::Info('受付状況画面からステータス(呼出→診察中)変更テスト Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_time = date('Y-m-d H:i:s', strtotime('-1hour'));
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>20,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折','medical_card_no'=>null,
        'call_time' => $call_time,
        'created_at'=>date('Y-m-d'),]);
      factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
      $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>20,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折',
        'call_time' => $call_time,
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '30'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト
             
        
      $this->assertDatabaseHas('reserves', [
            'id'=>$reserve1->id,
            'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>30,
            'name'=>'一番太郎','tel'=>'0123456789',
            'pet_name'=>'ジロー','conditions'=>'腕の骨折',
            'call_time' => $call_time,
            'created_at'=>date('Y-m-d'),]);
      Log::Info('受付状況画面からステータス(呼出→診察中)変更テスト End');
    }

    
    // 受付状況画面からステータス変更テスト(患者あり)
    public function testCan_indexStatusEXAMINEtoDONEUpdate()
    {
      Log::Info('受付状況画面からステータス(診察中→完了)変更テスト Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_time = date('Y-m-d H:i:s', strtotime('-1hour'));
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>30,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折','medical_card_no'=>null,
        'call_time' => $call_time,
        'created_at'=>date('Y-m-d'),]);
      factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
      $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>30,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ジロー','conditions'=>'腕の骨折',
        'call_time' => $call_time,
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '40'])
             ->assertStatus(302);
      
      $this->assertDatabaseHas('reserves', [
       'id'=>$reserve1->id,
       'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>40,
       'name'=>'一番太郎','tel'=>'0123456789',
       'pet_name'=>'ジロー','conditions'=>'腕の骨折',
       'call_time' => $call_time,
       'created_at'=>date('Y-m-d'),]);
      Log::Info('受付状況画面からステータス(診察中→完了)変更テスト End');
    }

    // 受付状況画面から名前変更テスト(患者あり)
    public function testCan_indexNameUpdate()
    {
      Log::Info('受付状況画面から名前変更テスト Start');
  
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);
  
      $curdate = date('Y/m/d');
      $reserve1 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
          'name'=>'一番太郎','tel'=>'0123456789',
          'pet_name'=>'ジロー','conditions'=>'腕の骨折','medical_card_no'=>null,
          'created_at'=>date('Y-m-d'),]);
          factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
          $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

      $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1->id,
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
          'name'=>'一番太郎','tel'=>'0123456789',
          'pet_name'=>'ジロー','conditions'=>'腕の骨折',
          'created_at'=>date('Y-m-d'),]);
  
      $this->put("/reserve/$reserve1->id/name",['name' => '(変更)一番太郎'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト
             
        
      $this->assertDatabaseHas('reserves', [
              'id'=>$reserve1->id,
              'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
              'name'=>'(変更)一番太郎','tel'=>'0123456789',
              'pet_name'=>'ジロー','conditions'=>'腕の骨折',
              'created_at'=>date('Y-m-d'),]);
      Log::Info('受付状況画面から名前変更テスト End');
    }

    // 受付情報編集画面表示テスト(未ログイン)
    public function testCannotView_editPage()
    {
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);

        // 表示できないことをテスト
        $this->get('/reserve/1/edit')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト

        // 表示できないことをテスト
        $this->get('/reserve')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト
    }

    // 受付情報編集画面表示(ステータス：待ち)テスト
    public function testCanView_edit_Status_Waiting_Page()
    {
        Log::Info('受付情報編集画面表示(ステータス：待ち)テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);

        $this->get('/reserve/1/edit')
             ->assertStatus(200)
             ->assertSee('<title>受付 - 管理画面 - おおたけ動物病院 - リザベル</title>')
             ->assertSee('<h1>おおたけ動物病院　受付情報編集</h1>')
             ->assertSee('/reserve/1" method="POST">')
             ->assertSee('<dt><span>受付番号</span></dt>')
             ->assertSee('<dd>1</dd>')
             ->assertSee('<dt><label for="status">ステータス</label></dt>')
             ->assertSee('<option value="10" selected >待ち</option>')
             //ITC-20 ステータスによる呼び出し時間表示
             ->assertDontSee('<div>(呼出時刻)<span>--:--</span></div>')
             ->assertSee('<dt><span>受付場所</span></dt>')
             ->assertSee('<dd>院内</dd>')
             ->assertSee('<dt><span>受付区分</span></dt>')
             ->assertSee('<dd>初診</dd>')
             ->assertSee('<dt><label for="patient_no">診察券番号</label></dt>')
             ->assertSee('<dd><input type="text" id="patient_no" name="patient_no" value="ABC0123456" /></dd>')
             ->assertSee('<dt><label for="name">飼い主氏名</label></dt>')
             ->assertSee('<dd><input type="text" id="name" name="name" value="一番太郎" /></dd>')
             ->assertSee('<dt><label for="email">メールアドレス</label></dt>')
             ->assertSee('<dd><input type="email" id="email" name="email" value="m-fujisawa@it-craft.co.jp" /></dd>')
             ->assertSee('<dt><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><input type="tel" id="tel" name="tel" value="0123456789" /></dd>')
             ->assertSee('<dt><label for="pet_type">ペットの種類</label></dt>')
             ->assertSee('<dt class="required"><label for="pet_name">ペットの名前</label></dt>')
             ->assertSee('<dd><input type="text" id="pet_name" name="pet_name" value="ジロー" /></dd>')
             ->assertSee('<dt><label for="pet_symptom">症状など</label></dt>')
             ->assertSee('<dd><textarea id="pet_symptom" name="pet_symptom" rows="5">腕の骨折</textarea></dd>')
             ->assertSee('<a href="http://localhost/index" class="btn_cancel">戻　る</a>')
             ->assertSee('<button class="btn_execution">更　新</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付情報編集画面表示(ステータス：待ち)テスト End');
    }

    // 受付情報編集画面表示(ステータス：呼出中)テスト
    public function testCanView_edit_Status_Called_Page()
    {
        Log::Info('受付情報編集画面表示(ステータス：呼出中)テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $call_time = date('Y-m-d H:i:s');
        $reserve1 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>20,
          'medical_card_no'=>'ABC0123456','name'=>'一番太郎',
          'email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789',
          'pet_name'=>'ジロー','conditions'=>'腕の骨折',
          'call_time' => $call_time,
          'created_at'=>date('Y-m-d'),]);
          factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
          $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>20,
          'medical_card_no'=>'ABC0123456','name'=>'一番太郎',
          'email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789',
          'pet_name'=>'ジロー','conditions'=>'腕の骨折',
          'call_time' => $call_time,
          'created_at'=>date('Y-m-d'),]);

        $this->get('/reserve/1/edit')
             ->assertStatus(200)
             ->assertSee('<title>受付 - 管理画面 - おおたけ動物病院 - リザベル</title>')
             ->assertSee('<h1>おおたけ動物病院　受付情報編集</h1>')
             ->assertSee('/reserve/1" method="POST">')
             ->assertSee('<dt><span>受付番号</span></dt>')
             ->assertSee('<dd>1</dd>')
             ->assertSee('<dt><label for="status">ステータス</label></dt>')
             ->assertSee('<option value="20"  selected >呼び出し済み</option>')
             //ITC-20 ステータスによる呼び出し時間表示
             ->assertSee('<div>（呼出時刻<span>'.date('H:i',strtotime($call_time)).'</span>）</div> ')
             ->assertSee('<dt><span>受付場所</span></dt>')
             ->assertSee('<dd>院内</dd>')
             ->assertSee('<dt><span>受付区分</span></dt>')
             ->assertSee('<dd>初診</dd>')
             ->assertSee('<dt><label for="patient_no">診察券番号</label></dt>')
             ->assertSee('<dd><input type="text" id="patient_no" name="patient_no" value="ABC0123456" /></dd>')
             ->assertSee('<dt><label for="name">飼い主氏名</label></dt>')
             ->assertSee('<dd><input type="text" id="name" name="name" value="一番太郎" /></dd>')
             ->assertSee('<dt><label for="email">メールアドレス</label></dt>')
             ->assertSee('<dd><input type="email" id="email" name="email" value="m-fujisawa@it-craft.co.jp" /></dd>')
             ->assertSee('<dt><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><input type="tel" id="tel" name="tel" value="0123456789" /></dd>')
             ->assertSee('<dt><label for="pet_type">ペットの種類</label></dt>')
             ->assertSee('<dt class="required"><label for="pet_name">ペットの名前</label></dt>')
             ->assertSee('<dd><input type="text" id="pet_name" name="pet_name" value="ジロー" /></dd>')
             ->assertSee('<dt><label for="pet_symptom">症状など</label></dt>')
             ->assertSee('<dd><textarea id="pet_symptom" name="pet_symptom" rows="5">腕の骨折</textarea></dd>')
             ->assertSee('<a href="http://localhost/index" class="btn_cancel">戻　る</a>')
             ->assertSee('<button class="btn_execution">更　新</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付情報編集画面表示(ステータス：呼出中)テスト End');
    }

    // 受付情報編集画面表示(ステータス：診察中)テスト
    public function testCanView_edit_Status_Examine_Page()
    {
        Log::Info('受付情報編集画面表示(ステータス：診察中)テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
        $call_time = date('Y-m-d H:i:s');
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>30,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','call_time'=>$call_time, 'created_at'=>date('Y-m-d'),]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>30,'medical_card_no'=>'ABC0123456','name'=>'一番太郎','email'=>'m-fujisawa@it-craft.co.jp','tel'=>'0123456789','pet_name'=>'ジロー','conditions'=>'腕の骨折','call_time'=>$call_time, 'created_at'=>date('Y-m-d'),]);

        $this->get('/reserve/1/edit')
             ->assertStatus(200)
             ->assertSee('<title>受付 - 管理画面 - おおたけ動物病院 - リザベル</title>')
             ->assertSee('<h1>おおたけ動物病院　受付情報編集</h1>')
             ->assertSee('/reserve/1" method="POST">')
             ->assertSee('<dt><span>受付番号</span></dt>')
             ->assertSee('<dd>1</dd>')
             ->assertSee('<dt><label for="status">ステータス</label></dt>')
             ->assertSee('<option value="30" selected >診察中</option>')
             //ITC-20 ステータスによる呼び出し時間表示
             ->assertDontSee('<div>(呼出時刻)<span>--:--</span></div>')
             ->assertSee('<dt><span>受付場所</span></dt>')
             ->assertSee('<dd>院内</dd>')
             ->assertSee('<dt><span>受付区分</span></dt>')
             ->assertSee('<dd>初診</dd>')
             ->assertSee('<dt><label for="patient_no">診察券番号</label></dt>')
             ->assertSee('<dd><input type="text" id="patient_no" name="patient_no" value="ABC0123456" /></dd>')
             ->assertSee('<dt><label for="name">飼い主氏名</label></dt>')
             ->assertSee('<dd><input type="text" id="name" name="name" value="一番太郎" /></dd>')
             ->assertSee('<dt><label for="email">メールアドレス</label></dt>')
             ->assertSee('<dd><input type="email" id="email" name="email" value="m-fujisawa@it-craft.co.jp" /></dd>')
             ->assertSee('<dt><label for="tel">電話番号</label></dt>')
             ->assertSee('<dd><input type="tel" id="tel" name="tel" value="0123456789" /></dd>')
             ->assertSee('<dt><label for="pet_type">ペットの種類</label></dt>')
             ->assertSee('<dt class="required"><label for="pet_name">ペットの名前</label></dt>')
             ->assertSee('<dd><input type="text" id="pet_name" name="pet_name" value="ジロー" /></dd>')
             ->assertSee('<dt><label for="pet_symptom">症状など</label></dt>')
             ->assertSee('<dd><textarea id="pet_symptom" name="pet_symptom" rows="5">腕の骨折</textarea></dd>')
             ->assertSee('<a href="http://localhost/index" class="btn_cancel">戻　る</a>')
             ->assertSee('<button class="btn_execution">更　新</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

        Log::Info('受付情報編集画面表示(ステータス：診察中)テスト End');
    }
    
    //受付情報編集保存テスト
    public function testCanSave_editData()
    {
        Log::Info('受付情報編集保存テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);
        
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        
        
        $this->put("/reserve/$reserve1->id", [
          'status'=>10,
          'name' => 'lee',
          'patient_no'=> '123',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678',
          
          'pet_name'=>'ポチ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect('/reserve');  // /Indexへのリダイレクト
             
        
        $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'name'=>'lee',
        'place'=>1,
        'reception_no'=>1,
        'care_type'=>1,
        'status'=>10,
        'medical_card_no'=> '123',
        'email'=>'j-lee@it-craft.co.jp',
        'tel'=>'0312345678',
        
        'pet_name'=>'ポチ',
        'conditions'=>'風邪',
        ]);
        Log::Info('受付情報編集保存テスト End');
    }


     //受付情報編集エラー(名前255文字以上)テスト
    public function testNameValidationError_editData()
    {
        Log::Info('受付情報編集エラー(名前255文字以上)テスト Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);


        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇ',
          'medical_card_no'=> '123',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678',
          
          'pet_name'=>'ポチ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト

          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>名前を255文字以下入力してください。</li>')
          ->assertDontSee('<li>name.max</li>');

        Log::Info('受付情報編集エラー(名前255文字以上)テスト End');
    }

    //受付情報編集エラー(診察券255文字以上)テスト
    public function testMedicalValidationError_editData()
    {
       Log::Info('受付情報編集エラー(診察券番号255文字以上)テスト Start');
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);

        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'test',
          'patient_no'=> 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇ',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678',
          'pet_name'=>'ポチ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト

          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>診察券番号を255文字以下入力してください。</li>')
          ->assertDontSee('<li>patient_no.max</li>');

        Log::Info('受付情報編集エラー(診察券番号255文字以上)テスト End');
    }

    //受付情報編集エラー(メールアドレス)テスト
    public function testMailValidationError_editData()
    {
       Log::Info('受付情報編集エラー(メールアドレス)テスト Start');
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
  
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);

        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

  
        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
  
  
        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'test',
          'medical_card_no'=> '123',
          'email'=>'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇ',
          'tel'=>'0312345678',
          'pet_name'=>'ポチ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト
  
          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>メールアドレスを正しい形式に入力してください。</li>')
          ->assertDontSee('<li>email.email</li>');
  
        Log::Info('受付情報編集エラー(メールアドレス)テスト End');
    }

    //受付情報編集エラー(電話番号)テスト
    public function testTelValidationError_editData()
    {
       Log::Info('受付情報編集エラー(電話番号)テスト Start');
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
  
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,
        'pet_type' => 1
        ]);
  
  
        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'test',
          'medical_card_no'=> '123',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678123124124124',
          'pet_name'=>'ポチ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト
  
          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>電話番号を半角数字を8桁以上、11桁以下を入力してください。</li>')
          ->assertDontSee('<li>tel.digits_between</li>');
  
        Log::Info('受付情報編集エラー(電話番号)テスト End');
    }

    //受付情報編集エラー(ペット名前255文字以上)テスト
    public function testPetNameValidationError_editData()
    {
       Log::Info('受付情報編集エラー(ペット名255文字以上)テスト Start');
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
    
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,
        'pet_type' => 1
        ]);
    
    
        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'test',
          'medical_card_no'=> '123',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678',
          'pet_type'=> [0 => ["pet_type" => "1"], 1 => ["pet_type" => "2"]],
          'pet_name'=>'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇ',
          'pet_symptom'=>'風邪',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト
    
          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>ペットの名前を255文字以下入力してください。</li>')
          ->assertDontSee('<li>pet_name.max</li>');
    
        Log::Info('受付情報編集エラー(ペット名255文字以上)テスト End');
    }

    //受付情報編集エラー(症状255文字以上)テスト
    public function testConditionsValidationError_editData()
    {
       Log::Info('受付情報編集エラー(症状255文字以上)テスト Start');
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
    
        $reserve1 = factory(Reserve::class)->create(['place'=>1,'reception_no'=>1,'care_type'=>1,
        'status'=>10,'medical_card_no'=> null,'name'=>null,'email'=>null,'tel'=>null,
        'pet_name'=>null,'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);

        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id,'pet_type' => 1]);

        $this->assertDatabaseHas('reserves', ['id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,'created_at'=>date('Y-m-d'),
        ]);
        
    
    
        $this->put("/reserve/$reserve1->id", [
          'id'=>$reserve1->id,
          'status'=>10,
          'name' => 'test',
          'medical_card_no'=> '123',
          'email'=>'j-lee@it-craft.co.jp',
          'tel'=>'0312345678',
          'pet_type'=> [0 => '1', 1 => '2'],
          'pet_name'=>'ポチ',
          'pet_symptom'=>'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんぁあぃいぅうぇ',
          ])
          ->assertStatus(302)
          ->assertRedirect("/reserve/$reserve1->id/edit");  // /Indexへのリダイレクト
    
          $this->get("/reserve/$reserve1->id/edit")
          ->assertSee('<li>症状などを255文字以下入力してください。</li>')
          ->assertDontSee('<li>pet_name.max</li>');
    
        Log::Info('受付情報編集エラー(症状255文字以上)テスト End');
    }


}
