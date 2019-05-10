<?php

namespace Tests\Feature;

use App\Models\Reserve;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use App\Mail\ReserveMail;
use Illuminate\Support\Facades\Mail;


class ReserveValidationTest extends TestCase
{
    use DatabaseMigrations;

    // 初診予約申込バリデーションエラーチェック（名前必須）
    public function testCanView_NameRequiredValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(名前必須)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => '',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
             ->assertSee('<li>名前を必ず入力してください。</li>')
             ->assertDontSee('<li>名前を255文字以下入力してください。</li>')
             ->assertDontSee('<li>validation.required</li>');

        Log::Info('初診予約申込バリデーションエラー(名前必須)表示テスト End');
    }

    // 初診予約申込バリデーションエラーチェック（名前文字数）
    public function testCanView_NameMaxValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(名前255文字以上)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
             ->assertSee('<li>名前を255文字以下入力してください。</li>')
             ->assertDontSee('<li>名前を必ず入力してください。</li>')
             ->assertDontSee('<li>validation.numeric</li>');

        Log::Info('初診予約申込バリデーションエラー(名前255文字以上)表示テスト End');
    }

    // 初診予約申込バリデーションエラーチェック（メールアドレス形式）
    public function testCanView_EmailValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(メールアドレス形式)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testname',
                        'email'       => 'erroremail', //メール形式ではない。
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>メールアドレスを正しい形式に入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(メールアドレス形式)表示テスト End');
    }

     // 初診予約申込バリデーションエラーチェック（メールアドレス未入力）
    public function testCanView_EmailRequireValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(メールアドレス)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testname',
                        'email'       => '', //メール未入力
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>メールアドレスを必ず入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(メールアドレス未入力)表示テスト End');
    }

    // 初診予約申込バリデーションエラーチェック（電話番号形式）
    public function testCanView_TelValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(電話番号形式)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => 'telNum',//電話番号に文字
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>電話番号を半角数字を8桁以上、11桁以下を入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(電話番号形式)表示テスト End');
    }

     // 初診予約申込バリデーションエラーチェック（動物種類必須）
     public function testCanView_PetTypeValidationError_typeFirst()
     {
         Log::Info('初診予約申込バリデーションエラー(動物種類必須)表示テスト Start');
 
         $this->post('/reserve/confirm',[
                         'careType'        => 1,
                         'patient_no' => '',
                         'name'        => 'testName',
                         'email'       => 'testEmail@test.com',
                         'tel'         => 'telNum',//電話番号に文字
                         'pet_type'    => [],
                         'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                         'pet_symptom' => 'おもちゃを飲み込んだ',
                     ])
              ->assertStatus(302)
              ->assertRedirect('/reserve/create/1');
 
          $this->get('/reserve/create/1')
               ->assertSee('<li>ペットの種類を必ず選択してください。</li>');
         Log::Info('初診予約申込バリデーションエラー(動物種類必須)表示テスト End');
     }
    
    // 初診予約申込バリデーションエラーチェック（ペット名）
    public function testCanView_PetNameRequireValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(ペット名前必須)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',//電話番号に文字
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => '',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>ペットの名前を必ず入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(ペット名前必須)表示テスト End');
    }

    // 初診予約申込バリデーションエラーチェック（ペット名前255文字）
    public function testCanView_PetNameValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(ペット名前255文字)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',//電話番号に文字
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>ペットの名前を255文字以下入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(ペット名前255文字)表示テスト End');
    }


    // 初診予約申込バリデーションエラーチェック（症状255文字）
    public function testCanView_PetSymptomValidationError_typeFirst()
    {
        Log::Info('初診予約申込バリデーションエラー(症状255文字)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 1,
                        'patient_no' => '',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',//電話番号に文字
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/1');

         $this->get('/reserve/create/1')
              ->assertSee('<li>症状などを255文字以下入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(症状255文字)表示テスト End');
    }
    
    
    // 再診予約申込バリデーションエラーチェック（名前必須）
    public function testCanView_NameRequiredValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(名前必須)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => '',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
             ->assertSee('<li>名前を必ず入力してください。</li>')
             ->assertDontSee('<li>名前を255文字以下入力してください。</li>');

        Log::Info('初診予約申込バリデーションエラー(名前必須)表示テスト End');
    }

    // 再診予約申込バリデーションエラーチェック（名前文字数）
    public function testCanView_NameMaxValidationError_typeRefeatt()
    {
        Log::Info('初診予約申込バリデーションエラー(名前255文字以上)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
             ->assertSee('<li>名前を255文字以下入力してください。</li>')
             ->assertDontSee('<li>名前を必ず入力してください。</li>');

        Log::Info('初診予約申込バリデーションエラー(名前255文字以上)表示テスト End');
    }

    // 再診予約申込バリデーションエラーチェック（メールアドレス形式）
    public function testCanView_EmailValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(メールアドレス形式)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testname',
                        'email'       => 'erroremail', //メール形式ではない。
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>メールアドレスを正しい形式に入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(メールアドレス形式)表示テスト End');
    }

     // 再診予約申込バリデーションエラーチェック（メールアドレス未入力）
    public function testCanView_EmailRequireValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(メールアドレス)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testname',
                        'email'       => '', //メール未入力
                        'tel'         => '0331234567',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>メールアドレスを必ず入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(メールアドレス未入力)表示テスト End');
    }

    // 再診予約申込バリデーションエラーチェック（電話番号形式）
    public function testCanView_TelValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(電話番号形式)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => 'telNum',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>電話番号を半角数字を8桁以上、11桁以下を入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(電話番号形式)表示テスト End');
    }
    
    
    // 再診予約申込バリデーションエラーチェック（ペット名）
    public function testCanView_PetNameRequireValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(ペット名前必須)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => '',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>ペットの名前を必ず入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(ペット名前必須)表示テスト End');
    }

    // 再診予約申込バリデーションエラーチェック（ペット名前255文字）
    public function testCanView_PetNameValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(ペット名前255文字)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>ペットの名前を255文字以下入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(ペット名前255文字)表示テスト End');
    }

    // 再診予約申込バリデーションエラーチェック（動物種類必須）
    public function testCanView_PetTypeValidationError_typeRefeat()
    {
        Log::Info('再診予約申込バリデーションエラーチェック(動物種類必須)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',
                        'pet_type'    => [],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'おもちゃを飲み込んだ',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
            ->assertStatus(302)
            ->assertRedirect('/reserve/create/2');

        $this->get('/reserve/create/1')
            ->assertSee('<li>ペットの種類を必ず選択してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(動物種類必須)表示テスト End');
    }

     // 再診予約申込バリデーションエラーチェック（来院目的必須）
     public function testCanView_PurposeValidationError_typeRefeat()
     {
         Log::Info('再診予約申込バリデーションエラーチェック(来院目的必須)表示テスト Start');
 
         $this->post('/reserve/confirm',[
                         'careType'        => 2,
                         'patient_no' => '',
                         'name'        => 'testName',
                         'email'       => 'testEmail@test.com',
                         'tel'         => '0312345678',
                         'pet_type'    => [0 => '1', 1 => '2'],
                         'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                         'pet_symptom' => 'おもちゃを飲み込んだ',
                         'purpose'     => [],
                     ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');
 
         $this->get('/reserve/create/1')
             ->assertSee('<li>来院目的を必ず選択してください。</li>');
         Log::Info('初診予約申込バリデーションエラー(来院目的必須)表示テスト End');
     }
    // 再診予約申込バリデーションエラーチェック（症状255文字）
    public function testCanView_PetSymptomValidationError_typeRefeat()
    {
        Log::Info('初診予約申込バリデーションエラー(症状255文字)表示テスト Start');

        $this->post('/reserve/confirm',[
                        'careType'        => 2,
                        'patient_no' => '123456',
                        'name'        => 'testName',
                        'email'       => 'testEmail@test.com',
                        'tel'         => '0312345678',
                        'pet_type'    => [0 => '1', 1 => '2'],
                        'pet_name'    => 'ポチ、ミケ、ぴょん、ピー、ごまぞー',
                        'pet_symptom' => 'x6QtlIaP4E7dJEdH2d8o6tqj3kpRbtypR1MgdifCpQpvDSnUDVmsdfXcqDhEilglXnb1CCAkg0kOfVvina3e6gRSp0FcAShxSLLyV0UFfEjNHXyxTrIv6M3nh0isALy6F54KqX2pOYtbDSlE7vPfFEMPmijEC0L58yON4UoD6JjlQVSLmMHwQX15QlADLdcXqObqEYmOvhClotIH1f7nhyHl6Upbyfdnl5KyAVqCWpbkaAbDyA8WR6lSHBjyA7yFso',
                        'purpose'     => [0 => '1', 1 => '2'],
                    ])
             ->assertStatus(302)
             ->assertRedirect('/reserve/create/2');

         $this->get('/reserve/create/2')
              ->assertSee('<li>症状などを255文字以下入力してください。</li>');
        Log::Info('初診予約申込バリデーションエラー(症状255文字)表示テスト End');
    }
}
