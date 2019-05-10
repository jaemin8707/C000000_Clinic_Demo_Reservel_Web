<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\User;
use App\Models\Reserve;
use App\Models\PetType;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ReserveIndexTest extends TestCase
{

  use DatabaseMigrations;

  public function testIndexStatus_editData()
    {
      Log::Info('index画面からstatus変更テスト Start');

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
        'status'=>20,
        'id'=>$reserve1->id,
        'place'=>1,'reception_no'=>1,'care_type'=>1,'medical_card_no'=> null,
        'name'=>null,'email'=>null,'tel'=>null,'pet_name'=>null,
        'conditions'=>null,
        ])
        ->assertStatus(302)
        ->assertRedirect('/reserve');  // /Indexへのリダイレクト
           
        $this->assertDatabaseHas('reserves', [
          'id'=>$reserve1->id,
          'place'=>1,
          'reception_no'=>1,
          'care_type'=>1,
          'status'=>20,
          'medical_card_no'=> null,
          'email'=> null,
          'tel'=> null,
          'pet_name'=> null,
          'conditions'=> null,
          ]);
      
      Log::Info('index画面からstatus変更テスト End');
  }

  public function testIndexName_editData()
  {
    Log::Info('index画面から名前変更テスト Start');

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
      'id'=>$reserve1->id,
      'place'=>1,'reception_no'=>1,'care_type'=>1,'medical_card_no'=> null,
      'name'=>'test','email'=>null,'tel'=>null,'pet_name'=>null,
      'conditions'=>null,
      ])
      ->assertStatus(302)
      ->assertRedirect('/reserve');  // /Indexへのリダイレクト
         
      $this->assertDatabaseHas('reserves', [
        'id'=>$reserve1->id,
        'name' => 'test',
        'place'=>1,
        'reception_no'=>1,
        'care_type'=>1,
        'status'=>10,
        'medical_card_no'=> null,
        'email'=> null,
        'tel'=> null,
        'pet_name'=> null,
        'conditions'=> null,
        ]);
    
    Log::Info('index画面から名前変更テスト End');
}
}
