<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Reserve;
use App\Models\Setting;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RemindMailTest extends TestCase
{
  use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanRemind_Mail_Send()
    {
      Log::Info('リマインドメール送信(ステータス10->20, 対象院外受付) Start');

      Mail::fake();
      Mail::assertNothingSent();

      Log::Info('受付状況画面からステータス(待ち→呼出)変更テスト Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_date = date('Y-m-d H:i:s');
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $reserve2 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける','medical_card_no'=>null,
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve2->id,
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける',
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      
      $reserve3 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve3->id,
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $reserve4 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+4@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve4->id,
        'place'=>2,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+4@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '20'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト

      
      
      // 1回送信されたことをアサート
      Mail::assertSent(\App\Mail\RemindSend::class, 1);

      // メールが指定したユーザーに送信されていることをアサート
      Mail::assertSent(
          \App\Mail\RemindSend::class,
          function ($mail)  {
              return $mail->to[0]['address'] === 'j-lee+4@it-craft.co.jp';
          }
      );

      Log::Info('リマインドメール送信(ステータス10->20, 対象院外受付) End');
    }


    public function testCanRemind_Mail_NotSend()
    {
      Log::Info('リマインドメール送信(ステータス10->20, メールアドレス登録されてない) Start');

      Mail::fake();
      Mail::assertNothingSent();

      Log::Info('受付状況画面からステータス(待ち→呼出)変更テスト Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_date = date('Y-m-d H:i:s');
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $reserve2 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける','medical_card_no'=>null,
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve2->id,
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける',
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      
      $reserve3 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve3->id,
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $reserve4 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email' => null,
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve4->id,
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email' => null,
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '20'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト

      
      
      // 1回送信されたことをアサート
      Mail::assertNotSent(\App\Mail\RemindSend::class, 0);

      // メールが指定したユーザーに送信されていることをアサート
      Mail::assertNothingSent(
          \App\Mail\RemindSend::class,
          function ($mail)  {
              return $mail->to[0]['address'] === null;
          }
      );

      Log::Info('リマインドメール送信(ステータス10->20, メールアドレス登録されてない) End');
    }

    //前日の受付情報が残っている時の送信
    public function testCanRemind_Mail_Today_Send()
    {
      Log::Info('前日の受付情報が残っている時の送信 Start');

      Mail::fake();
      Mail::assertNothingSent();

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_date = date('Y-m-d H:i:s');

      //2日前の受付 リマインドメール未送信
      $reserve_2_1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'2日前1','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+2daysbefore1@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-2 days'))]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve_2_1->id,
        'place'=>1,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'2日前1','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+2daysbefore1@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-2 days'))]);

      $reserve_2_2 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'2日前2','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+2daysbefore2@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-2 days'))]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve_2_2->id,
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'2日前2','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+2daysbefore2@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-2 days'))]);

      //1日前の受付 リマインドメール未送信
      $reserve1_1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'1日前1','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+1daysbefore1@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-1 day'))]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1_1->id,
        'place'=>1,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'1日前1','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+1daysbefore1@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-1 day'))]);

      $reserve1_2 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'1日前2','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+1daysbefore2@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-1 day'))]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1_2->id,
        'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'1日前2','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+1daysbefore2@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d',strtotime('-1 day'))]);



      //当日受付
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      $reserve2 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける','medical_card_no'=>null,
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve2->id,
        'place'=>2,'reception_no'=>2,'care_type'=>1,'status'=>10,
        'name'=>'二番次郎','tel'=>'0123456789','pet_type'=>'猫',
        'pet_name'=>'キャット','conditions'=>'ひげ抜ける',
        'email'=>'j-lee+2@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);

      
      $reserve3 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve3->id,
        'place'=>2,'reception_no'=>3,'care_type'=>1,'status'=>10,
        'name'=>'三番三郎','tel'=>'0123456789','pet_type'=>'ウサギ',
        'pet_name'=>'ラビット','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+3@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d'),]);

      $reserve4 = factory(Reserve::class)->create([
        'place'=>2,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+4@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d'),]);
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve4->id,
        'place'=>2,'reception_no'=>4,'care_type'=>1,'status'=>10,
        'name'=>'四番四郎','tel'=>'0123456789','pet_type'=>'ゴジラ',
        'pet_name'=>'ゴジ','conditions'=>'目の出血','medical_card_no'=>null,
        'email'=>'j-lee+4@it-craft.co.jp',
        'send_remind' => false,
        'created_at'=>date('Y-m-d'),]);

      $this->put("/reserve/$reserve1->id/status",['status' => '20'])
           ->assertStatus(302)
           ->assertRedirect('/reserve');  // /Indexへのリダイレクト

      
      
      // 1回送信されたことをアサート
      Mail::assertSent(\App\Mail\RemindSend::class, 1);

      // メールが指定したユーザーに送信されていることをアサート
      Mail::assertSent(
          \App\Mail\RemindSend::class,
          function ($mail)  {
              return $mail->to[0]['address'] === 'j-lee+4@it-craft.co.jp';
          }
      );

      Log::Info('前日の受付情報が残っている時の送信 End');
    }


    //前日の受付情報が残っている時なお対象がない
    public function testCanRemind_Mail_Today_Not_Send()
    {
        Log::Info('前日の受付情報が残っている時なお対象がない Start');

        Mail::fake();
        Mail::assertNothingSent();

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);

        $curdate = date('Y/m/d');
        $call_date = date('Y-m-d H:i:s');

        //2日前の受付 リマインドメール未送信
        $reserve_2_1 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>3,'care_type'=>1,'status'=>10,
          'name'=>'2日前1','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
          'email'=>'j-lee+2daysbefore1@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-2 days'))]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve_2_1->id,
          'place'=>1,'reception_no'=>3,'care_type'=>1,'status'=>10,
          'name'=>'2日前1','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'email'=>'j-lee+2daysbefore1@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-2 days'))]);

        $reserve_2_2 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
          'name'=>'2日前2','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
          'email'=>'j-lee+2daysbefore2@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-2 days'))]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve_2_2->id,
          'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
          'name'=>'2日前2','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'email'=>'j-lee+2daysbefore2@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-2 days'))]);

        //1日前の受付 リマインドメール未送信
        $reserve1_1 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>2,'care_type'=>1,'status'=>10,
          'name'=>'1日前1','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
          'email'=>'j-lee+1daysbefore1@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-1 day'))]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1_1->id,
          'place'=>1,'reception_no'=>2,'care_type'=>1,'status'=>10,
          'name'=>'1日前1','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'email'=>'j-lee+1daysbefore1@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-1 day'))]);

        $reserve1_2 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
          'name'=>'1日前2','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
          'email'=>'j-lee+1daysbefore2@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-1 day'))]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1_2->id,
          'place'=>1,'reception_no'=>4,'care_type'=>1,'status'=>10,
          'name'=>'1日前2','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'email'=>'j-lee+1daysbefore2@it-craft.co.jp',
          'send_remind' => false,
          'created_at'=>date('Y-m-d',strtotime('-1 day'))]);



        //当日受付
        $reserve1 = factory(Reserve::class)->create([
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
          'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
          'email'=>'j-lee+1@it-craft.co.jp',
          'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1->id,
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
          'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'犬',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'email'=>'j-lee+1@it-craft.co.jp',
          'created_at'=>date('Y-m-d'),]);

        $this->put("/reserve/$reserve1->id/status",['status' => '20'])
            ->assertStatus(302)
            ->assertRedirect('/reserve');  // /Indexへのリダイレクト
        
        // 1回送信されたことをアサート
        Mail::assertSent(\App\Mail\RemindSend::class, 0);

        // メールが指定したユーザーに送信されていることをアサート
        Mail::assertNothingSent(
          \App\Mail\RemindSend::class,
          function ($mail)  {
              return $mail->to[0]['address'] === null;
          }
        );

        Log::Info('前日の受付情報が残っている時なお対象がない End');
    }
}
