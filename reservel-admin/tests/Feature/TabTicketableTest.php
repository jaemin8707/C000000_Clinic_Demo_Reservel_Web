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


class TabTicketableTest extends TestCase
{
  use DatabaseMigrations;

    //受付可否(true->false)
    public function testTicketableTrueToFalse() {

      Log::Info('受付可否(true->false) Start');

      // ログインユーザの作成・認証
      $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp']);
      $this->actingAs($login);

      // Setup
      $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'true']);
      $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'true']);

      // Test
      $this->put("/setting/tabTicketable", [
                          'tabTicketable'=>'false'
                 ])
           ->assertStatus(302)
           ->assertRedirect('/reserve');

      // Check
      $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'false']);

      Log::Info('受付可否(true->false) End');

    }

    //受付可否(false->true)
    public function testTicketAbleFalseToTrue() {

        Log::Info('受付可否(false->true) Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp']);
        $this->actingAs($login);

        // Setup
        $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'false']);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'false']);

        // Test
        $this->put("setting/tabTicketable", [
                           'tabTicketable'=>'true'
                  ])
             ->assertStatus(302)
             ->assertRedirect('/reserve');

        // Check
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'true']);

        Log::Info('受付可否(false->true) End');

    }

    //受付可否(false->false)
    public function testTicketAbleFalseToFalse() {

        Log::Info('受付可否(false->false) Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp']);
        $this->actingAs($login);

        // Setup
        $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'false']);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'false']);

        // Test
        $this->put("setting/tabTicketable", [
                           'tabTicketable'=>'false'
                  ])
             ->assertStatus(302)
             ->assertRedirect('/reserve');

        // Check
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'false']);

        Log::Info('受付可否(false->false) End');

    }

    //受付可否(true->true)
    public function testTicketAbleTrueToTrue()
    {
        Log::Info('受付可否(true->true) Start');

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp']);
        $this->actingAs($login);

        // Setup
        $setting = factory(Setting::class)->create(['code' => 'tabTicketable', 'value' => 'true']);
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'true']);

        // Test
        $this->put("/setting/tabTicketable", [
                            'tabTicketable'=>'true'
                  ])
             ->assertStatus(302)
             ->assertRedirect('/reserve');

        // Check
        $this->assertDatabaseHas('settings', ['id'=>$setting->id, 'code' => 'tabTicketable', 'value'=>'true']);

        Log::Info('受付可否(true->true) End');
    }
}
