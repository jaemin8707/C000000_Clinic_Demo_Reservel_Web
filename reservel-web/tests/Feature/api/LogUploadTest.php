<?php

namespace Tests\Feature\api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class LogUploadTest extends PassortGrantTestCase
{
    //ログアップロード成功
    public function testlogUpload_success()
    {
        Log::Debug('ログアップロードAPIテスト Start');

        Log::Debug($this->access_token);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->get('/api/acceptance')
                         ->assertStatus(200);
        Storage::persistentFake(env('DISK_UPLOAD'));
                    $this->post('/api/log/upload',[
                                  "uploads" => UploadedFile::fake()->create('20190327log.zip'), 
                                ])
                          ->assertStatus(200)
                          ->assertJson([
                            'result' => [],
                            'status' => [
                               'code' => '0',
                                'message' => '',
                            ]
                        ]);  // マイページへのリダイレクト
        Log::Debug('ログアップロードAPIテスト End');
    }

    //ログアップロード失敗
    public function testlogUpload_fail()
    {
        Log::Debug('ログアップロードAPI失敗テスト Start');

        Log::Debug($this->access_token);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Authorization'=> 'Bearer '.$this->access_token
                           ])
                         ->get('/api/acceptance')
                         ->assertStatus(200);
        Storage::persistentFake(env('DISK_UPLOAD'));
                    $this->post('/api/log/upload',[
                                  "uploads" => [], 
                                ])
                          ->assertStatus(200)
                          ->assertJson([
                            'result' => [],
                            'status' => [
                               'code' => '-1',
                                'message' => '',
                            ]
                        ]);  // マイページへのリダイレクト
        Log::Debug('ログアップロードAPI失敗テスト End');
    }

}
