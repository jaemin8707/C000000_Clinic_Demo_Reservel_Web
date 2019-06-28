<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Reserve;
use App\Models\PetType;
use App\Models\Purpose;
use App\Models\Setting;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RemindMailManualTest extends TestCase
{
  use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRemindMail_Send()
    {
      Log::Info('リマインドメール手動送信 Start');

      Mail::fake();
      Mail::assertNothingSent();

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
      $call_date = date('Y-m-d H:i:s');
      $reserve1 = factory(Reserve::class)->create([
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折','medical_card_no'=>null,
        'send_remind' => 0,
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
        factory(PetType::class)->create(['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        factory(Purpose::class)->create(['reserve_id'=>$reserve1->id, 'purpose' => 1]);

      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
        'name'=>'一番太郎','tel'=>'0123456789',
        'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
        'send_remind' => 0,
        'email'=>'j-lee+1@it-craft.co.jp',
        'created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
        $this->assertDatabaseHas('purpose', ['reserve_id'=>$reserve1->id, 'purpose' => 1]);

        $this->put("/reserve/$reserve1->id/remind", [
        ])
        ->assertStatus(302)
        ->assertRedirect('/reserve');  // /Indexへのリダイレクト
        
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1->id,
          'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,
          'name'=>'一番太郎','tel'=>'0123456789',
          'pet_name'=>'ドッグ','conditions'=>'腕の骨折',
          'send_remind' => 1,
          'email'=>'j-lee+1@it-craft.co.jp',
          'created_at'=>date('Y-m-d'),]);
          $this->assertDatabaseHas('pet_type', ['reserve_id'=>$reserve1->id, 'pet_type' => 1]);
          $this->assertDatabaseHas('purpose', ['reserve_id'=>$reserve1->id, 'purpose' => 1]);

          
        // 1回送信されたことをアサート
        Mail::assertSent(\App\Mail\RemindSend::class, 1);

        // メールが指定したユーザーに送信されていることをアサート
        Mail::assertSent(
            \App\Mail\RemindSend::class,
            function ($mail)  {
                return $mail->to[0]['address'] === 'j-lee+1@it-craft.co.jp';
            }
        );
        Log::Info('リマインドメール手動送信 End');
      }
}