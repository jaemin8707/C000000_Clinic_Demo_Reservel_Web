<?php

namespace Tests\Feature\api;

use App\Models\OauthClient;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PassortGrantTestCase extends TestCase
{

    protected $access_token = "";

    public function setUp(): void {
        parent::setUp();
        Artisan::call('migrate:refresh');

        $client = factory(OauthClient::class)->create(['id'=>1,'name'=>'reservelInHp','secret'=>'ZaE0eE9wPnygtaNyRHusNZ9yeXRgK0CytPNJvnQs',]);

        $response = $this->withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                           ])
                         ->json('POST', '/oauth/token', ["grant_type"     => "client_credentials",
                                                         "client_id"      => "1",
                                                         "client_secret"  => "ZaE0eE9wPnygtaNyRHusNZ9yeXRgK0CytPNJvnQs",
                                                         "scope"          => "*",
                                                        ]);

        $this->access_token = $response->json('access_token');

        Log::Debug('取得したアクセストークン：'.$this->access_token);

    }


}
