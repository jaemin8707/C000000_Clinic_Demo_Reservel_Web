<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Closed;
use App\Models\Setting;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ClosedTest extends TestCase
{
  use DatabaseMigrations;

  // 登録されている休診日の表示()
  public function testSavedView_closed()
  {
      Log::Info('登録されている休診日の表示 Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $curdate = date('Y/m/d');
          $closed = factory(Closed::class)->create([
          'closed_day'=>'2019-05-01',
          'closed_type'=>1,]);
      $this->assertDatabaseHas('closed', [
          'id'=>$closed->id,
          'closed_day'=>'2019-05-01',
          'closed_type'=>1,]);

      $this->get('/closed')
        ->assertStatus(200)
        ->assertSee('<div class="a_closed_day">2019年05月01日(水)</div>')
        ->assertSee('<div class="a_closed_type">現在：午前');
      Log::Info('登録されている休診日の表示 End');
  }

  public function testCreateDayMorning_Closed()
  {
    Log::Info('休診日・午前登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'create_type' => '2',
        'closed_day'  => '2019-05-23',
        'closed_type' => '1',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月23日(木)</div>')
      ->assertSee('<div class="a_closed_type">現在：午前');
    Log::Info('休診日・午前登録 Start');
  }

  public function testCreateDayAfternoon_Closed()
  {
    Log::Info('休診日・午後登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'create_type' => '2',
        'closed_day'  => '2019-05-23',
        'closed_type' => '2',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月23日(木)</div>')
      ->assertSee('<div class="a_closed_type">現在：午後');
    Log::Info('休診日・午前登録 Start');
  }

  public function testCreateDayAllDay_Closed()
  {
    Log::Info('休診日・全日登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'create_type' => '2',
        'closed_day'  => '2019-05-23',
        'closed_type' => '3',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月23日(木)</div>')
      ->assertSee('<div class="a_closed_type">現在：全日');
    Log::Info('休診日・全日登録 Start');
  }

  public function testCreateWeekMorning_Closed()
  {
    Log::Info('休診毎週・午前登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'month' => '2019-05',
        'create_type' => '1',
        'closed_week'  => '1',
        'closed_type' => '1',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月06日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月13日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月20日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月27日(月)</div>')
      ->assertSee('<div class="a_closed_type">現在：午前');
    Log::Info('休診毎週・午前登録 Start');
  }

  public function testCreateWeekAfternoon_Closed()
  {
    Log::Info('休診毎週・午後登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'month' => '2019-05',
        'create_type' => '1',
        'closed_week'  => '1',
        'closed_type' => '2',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月06日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月13日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月20日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月27日(月)</div>')
      ->assertSee('<div class="a_closed_type">現在：午後');
    Log::Info('休診毎週・午後登録 Start');
  }

  public function testCreateWeekAllday_Closed()
  {
    Log::Info('休診毎週・全日登録 Start');
      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
      $this->actingAs($login);

      $this->POST("/closed/create", [
        'month' => '2019-05',
        'create_type' => '1',
        'closed_week'  => '1',
        'closed_type' => '3',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月06日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月13日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月20日(月)</div>')
      ->assertSee('<div class="a_closed_day">2019年05月27日(月)</div>')
      ->assertSee('<div class="a_closed_type">現在：全日');
    Log::Info('休診毎週・午後登録 Start');
  }

  public function testUpdateDayMorningToAfternoon_Closed() {

    Log::Info('休診日・午前→ Start');
    // ログインユーザの作成・認証
    $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
    $this->actingAs($login);
    
    
    $closed = factory(Closed::class)->create([
          'closed_day'=>'2019-05-01',
          'closed_type'=>1,]);
    $this->assertDatabaseHas('closed', [
          'id'=>$closed->id,
          'closed_day'=>'2019-05-01',
          'closed_type'=>1,]);

      $this->PUT("/closed/$closed->id/update", [
            'closed_type' => '2',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月01日(水)</div>')
      ->assertSee('<div class="a_closed_type">現在：午後');
      Log::Info('休診日・午前→午後 Start');
  }

  public function testUpdateDayAfternoonToMorning_Closed() {

    Log::Info('休診日・午後→午前 Start');
    // ログインユーザの作成・認証
    $login = factory(User::class)->create(['email'=>'j-lee@it-craft.co.jp',]);
    $this->actingAs($login);
    
    
    $closed = factory(Closed::class)->create([
          'closed_day'=>'2019-05-01',
          'closed_type'=>'2',]);
    $this->assertDatabaseHas('closed', [
          'id'=>$closed->id,
          'closed_day'=>'2019-05-01',
          'closed_type'=>'2',]);

      $this->PUT("/closed/$closed->id/update", [
            'closed_type' => '1',
      ])
      ->assertStatus(302)
      ->assertRedirect("/closed?month=2019-05");  // /Indexへのリダイレクト

      $this->get('/closed?month=2019-05')
      ->assertStatus(200)
      ->assertSee('<div class="a_closed_day">2019年05月01日(水)</div>')
      ->assertSee('<div class="a_closed_type">現在：午前');

      Log::Info('休診日・午後→午前 End');
  }
}