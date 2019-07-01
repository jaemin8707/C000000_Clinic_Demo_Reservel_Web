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

class ReserveCreateTest extends TestCase
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
    // 初診受付テスト
    public function testCreate_typeFirst()
    {

        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
        Log::Info('管理側初診受付 Start');

        $this->put('/reserve/create/1',[
                        'patient_no' => ''
        ])
        ->assertStatus(302)
        ->assertRedirect('/reserve');
        $this->assertDatabaseHas('reserves', ['id'=>1,'care_type'=>1,'status'=>10,'medical_card_no'=>null]);

        Log::Info('管理側初診受付 End');
    }

    // 再診受付テスト
    public function testCreate_typeRepeat()
    {
  
        // ログインユーザの作成・認証
        $login = factory(User::class)->create(['email'=>'m-fujisawa@inforce.ne.jp',]);
        $this->actingAs($login);
        Log::Info('管理側再診受付 Start');
  
        $this->put('/reserve/create/2',[
                        'patient_no' => '1234'
        ])
        ->assertStatus(302)
        ->assertRedirect('/reserve');
        $this->assertDatabaseHas('reserves', ['id'=>1,'care_type'=>2,'status'=>10,'medical_card_no'=>'1234']);
  
        Log::Info('管理側再診受付 End');
    }
}
