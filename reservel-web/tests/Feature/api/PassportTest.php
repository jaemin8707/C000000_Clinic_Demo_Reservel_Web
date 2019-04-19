<?php

namespace Tests\Feature\api;

use App\Models\OauthClient;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PassportTest extends TestCase
{

    public function setUp(): void {
        parent::setUp();
        Artisan::call('migrate:refresh');

        // Client生成
        $client = factory(OauthClient::class)->create(['id'=>1,'name'=>'reservelInHp','secret'=>'ZaE0eE9wPnygtaNyRHusNZ9yeXRgK0CytPNJvnQs',]);

    }

    public function testCanGetAccessToken()
    {
        Log::Debug('アクセストークン取得テスト Start');

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                           ])
                         ->json('POST', '/oauth/token', ["grant_type"     => "client_credentials",
                                                         "client_id"      => "1",
                                                         "client_secret"  => "ZaE0eE9wPnygtaNyRHusNZ9yeXRgK0CytPNJvnQs",
                                                         "scope"          => "*",
                                                        ]);
        $response->assertStatus(200)
                 ->assertJson([
                      "token_type"=> "Bearer",
//                      "expires_in"=> 31622400, // 処理に時間がかかり31622399で戻ってくることがあるのでテストしない
//                      "access_token"=>''       //毎回変わるのでテストしない
                   ]);

        Log::Debug('アクセストークン取得テスト End');
    }

    public function testCanNotGetAccessToken()
    {
        Log::Debug('アクセストークン取得失敗テスト Start');

        Log::Debug('↓↓このログはassertStatus(401)で必ず出力されるものなので無視して構わない↓↓');
        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                           ])
                         ->json('POST', '/oauth/token', ["grant_type"     => "client_credentials",
                                                         "client_id"      => "1",
                                                         "client_secret"  => "",  // s-keyなし
                                                         "scope"          => "*",
                                                        ]);
        Log::Debug('↑↑このログはassertStatus(401)で必ず出力されるものなので無視して構わない↑↑');

        $response->assertStatus(401)
                 ->assertJson([
                      "error"=> "invalid_client",
                      "message"=> "Client authentication failed",
                   ]);

        Log::Debug('アクセストークン失敗取得テスト End');
    }

}
