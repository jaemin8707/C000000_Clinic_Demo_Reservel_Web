<?php

namespace Tests\Feature;

use App\Models\Reserve;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;


class ReserveCancelTest extends TestCase
{
    use DatabaseMigrations;

    // 受付キャンセル(トークンがないとき)
    public function testCanView_ReserveEmptyTokenCancel()
    {
        Log::Info('受付キャンセル(トークンがないとき)表示テスト Start');

        $this->get('/reserve/cancel')
             ->assertStatus(404);

        Log::Info('受付キャンセル(トークンがないとき)表示テスト End');
    }


    // 受付キャンセル(当てはまるトークンが存在しない時)
    public function testCanView_ReserveFailureTokenCancel()
    {
        Log::Info('受付キャンセル(当てはまるトークンが存在しない時)表示テスト Start');

        $this->get('/reserve/cancel/1234') //1234
            ->assertStatus(301)
            ->assertRedirect('/index');
        Log::Info('受付キャンセル(当てはまるトークンが存在しない時)表示テスト End');
    }

    // 受付キャンセル(トークンが)
    public function testCanView_ReserveIssetTokenCancel()
    {
        Log::Info('受付キャンセル(トークンがあるとき)表示テスト Start');
       
        //受付キャンセル用のトークン発行
        $token = 'LtnfTa5qxlC5Yi1rKll0TJjO1axUgDHtaYIjZhSLlIU=';
        $reserve1 = factory(Reserve::class)->create(['cancel_token' => $token, 'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'子','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['cancel_token' => $token, 'id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'子','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);

        $this->get('/reserve/cancel/'.$token)
            ->assertStatus(200)
            ->assertSee('<h1>サンプル動物病院　予約キャンセル</h1')
            ->assertSee('<div class="comprete_clinictype">キャンセルする受付番号：1</div>')
            ->assertSee('<div class="comprete_text">キャンセルしない場合はブラウザを閉じて下さい。</div>')
            ->assertSee('body div.popup_modals .modal_buttons .btn.btn_pmry{width:100px;background-color:#e74c3c;}')
            ->assertSee('body div.popup_modals .modal_buttons .btn.btn_sdry{width:100px;background-color:gray;}')
            ->assertSee('body div.popup_modals .modal_buttons.right{text-align:center;}');
;

        Log::Info('受付キャンセル(トークンがあるとき)表示テスト End');
    }
    
    
    // 受付キャンセル処理表示
    public function testCanView_ReserveCancel()
    {
        Log::Info('受付キャンセル処理表示テスト End');
       
        
        //受付キャンセル用のトークン発行
        $token = 'LtnfTa5qxlC5Yi1rKll0TJjO1axUgDHtaYIjZhSLlIU=';
        $reserve1 = factory(Reserve::class)->create(['cancel_token' => $token, 'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'子','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);
        $this->assertDatabaseHas('reserves', ['cancel_token' => $token, 'id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>10,'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'子','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);

        $this->post('/reserve/cancel_complete',[
                        'id' => $reserve1->id,
                        'cancel_token' => $token,
                   ])
            ->assertStatus(200);
            
        $this->assertDatabaseHas('reserves', ['cancel_token' => null, 'id'=>$reserve1->id,'place'=>1,'reception_no'=>1,'care_type'=>1,'status'=>-1,'name'=>'一番太郎','tel'=>'0123456789','pet_type'=>'子','pet_name'=>'ジロー','conditions'=>'腕の骨折','created_at'=>date('Y-m-d'),]);
        
        Log::Info('受付キャンセル処理表示テスト End');
    }
}
