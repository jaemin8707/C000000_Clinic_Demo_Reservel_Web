<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PasswordeReset;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class PasswordResetTest extends TestCase
{
    public function setUp(): void {
        parent::setUp();
        Artisan::call('migrate:refresh');

    }

    public function testCanViewPasswordResetMailRequestPage()
    {

        $this->get('/password/reset')
             ->assertStatus(200)
             ->assertSee('<h1>しんか動物病院　管理画面　パスワードリセット</h1>')
             ->assertSee('<div class="card-header">パスワードのリセット</div>')
             ->assertSee('<form method="POST" action="http://localhost/password/email">')
             ->assertSee('<label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>')
             ->assertSee('<input id="email" type="email" class="form-control" name="email" value="" required>')
             ->assertSee('<button type="submit" class="btn btn-primary">パスワードリセット用のメールを送信する</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');
    }

    public function testCanNotSendEmail()
    {

        $this->withHeaders([
                'Referer' => '/password/reset',
              ])
             ->post('/password/email',['email'=>'a@b'])
             ->assertStatus(302)
             ->assertRedirect("/password/reset");  

        $this->get('/password/reset')
             ->assertStatus(200)
             ->assertSee('<strong>メールアドレスに一致するユーザーが存在しません。</strong>');

    }

    public function testCanSendEmail()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp',]);

        $this->withHeaders([
                'Referer' => '/password/reset',
              ])
             ->post('/password/email',['email'=>'m-fujisawa@it-craft.co.jp'])
             ->assertStatus(302)
             ->assertRedirect("/password/reset");  

        $this->get('/password/reset')
             ->assertStatus(200)
             ->assertSee('パスワード再設定用のURLをメールで送りました。');

    }

    public function testCanViewResetPage()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);

        $this->get('/password/reset/abc')
             ->assertStatus(200)
             ->assertSee('<title>パスワードリセット - 管理画面 - しんか動物病院 - リザベル</title>')
             ->assertSee('<h1>しんか動物病院　管理画面　パスワードリセット</h1>')
             ->assertSee('<div class="card-header">パスワードのリセット</div>')
             ->assertSee('<form method="POST" action="http://localhost/password/reset">')
             ->assertSee('<label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>')
             ->assertSee('<input id="email" type="email" class="form-control" name="email" value="" required autofocus>')
             ->assertSee('<label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>')
             ->assertSee('<input id="password" type="password" class="form-control" name="password" required>')
             ->assertSee('<label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワード(確認用)</label>')
             ->assertSee('<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>')
             ->assertSee('<button type="submit" class="btn btn-primary">パスワードをリセットする</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

    }

    public function testCanViewResetPageByMissingToken()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);

        // トークンが間違っていても正常に表示されることをテスト
        $this->get('/password/reset/ab')
             ->assertStatus(200)
             ->assertSee('<title>パスワードリセット - 管理画面 - しんか動物病院 - リザベル</title>')
             ->assertSee('<h1>しんか動物病院　管理画面　パスワードリセット</h1>')
             ->assertSee('<div class="card-header">パスワードのリセット</div>')
             ->assertSee('<form method="POST" action="http://localhost/password/reset">')
             ->assertSee('<label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>')
             ->assertSee('<input id="email" type="email" class="form-control" name="email" value="" required autofocus>')
             ->assertSee('<label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>')
             ->assertSee('<input id="password" type="password" class="form-control" name="password" required>')
             ->assertSee('<label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワード(確認用)</label>')
             ->assertSee('<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>')
             ->assertSee('<button type="submit" class="btn btn-primary">パスワードをリセットする</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

    }

    public function testCanNotChangePasswordByRequired()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);

        // トークンが間違っていたらバリデーションに制限されることをテスト
        $this->withHeaders([
                'Referer' => '/password/reset/abc',
              ])
             ->post('/password/reset')
             ->assertStatus(302)
             ->assertRedirect("/password/reset/abc");  

        $this->get('/password/reset/ab')
             ->assertStatus(200)
             ->assertSee('<strong>emailは必須です。</strong>')
             ->assertSee('<strong>passwordは必須です。</strong>');

    }

    public function testCanNotChangePasswordByMissingToken()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);

        // トークンが間違っていたらバリデーションに制限されることをテスト
        $this->withHeaders([
                'Referer' => '/password/reset/ab',

                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate',
                'Accept-Language' => 'ja,en;q=0.9',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Content-Length' => '142',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => 'XSRF-TOKEN=eyJpdiI6IjFNRmhcL21SUHhwbXJ1amV3amZaNlwvQT09IiwidmFsdWUiOiIzQU5PRnlUSThxdWQwTWc1WjlWWkFDNFZmZWhiTmNwM25GY3AxcU1VaTlMRXdyejVPNmRcL25GR1QxMm8zdzBhZSIsIm1hYyI6Ijc3NTU3NGVlMDI2NjA0NjQwOTZmYzFmOWE0YmE4NzJlZTJlNjhkNjU3YWIwZjJiNWVjMWMzMzJjZmI4MDQ2NTcifQ%3D%3D; reservelakatsuki_session=eyJpdiI6InE2NmpiXC8rbm5PakFnR0dRejBDTnpnPT0iLCJ2YWx1ZSI6IldVNVgrSkNFd0E1Z1czXC9cL3JvSDlhaXA4TndDRnZwYWRHM3UxNlhpTFJvaEEzYndDWTFZXC8zSzcySk5jNGxWT3UiLCJtYWMiOiI3Yjk3ZWQyZTZmMjFiMjg5YzU5NjMwNzQ0YzViM2MzZWUyZTZlZjI1M2YxZmUzMGYxNGQ3NmJmZDJlY2ZjZGU2In0%3D',
                'Host' => 'lee.seibozaka-reservel.jp',
                'Origin' => 'http://lee.seibozaka-reservel.jp',
                'Pragma' => 'no-cache',
                'Upgrade-Insecure-Requests' => '1',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
              ])
             ->post('/password/reset',['email'=>'m-fujisawa@it-craft.co.jp','password'=>'testtest','password_confirmation'=>'testtest'])
             ->assertStatus(302)
             ->assertRedirect("/password/reset/ab");  

        $this->get('/password/reset/ab')
             ->assertStatus(200)
             ->assertSee('<strong>パスワード再設定用のトークンが不正です。</strong>');

    }

    public function testCanChangePassword()
    {

        $user = factory(User::class)->create([             'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);
        $this->assertDatabaseHas('users', ['id'=>$user->id,'email'=>'m-fujisawa@it-craft.co.jp','remember_token'=>'abc',]);

        $this->get('/password/reset/abc')
             ->assertStatus(200)
             ->assertSee('<title>パスワードリセット - 管理画面 - しんか動物病院 - リザベル</title>')
             ->assertSee('<h1>しんか動物病院　管理画面　パスワードリセット</h1>')
             ->assertSee('<div class="card-header">パスワードのリセット</div>')
             ->assertSee('<form method="POST" action="http://localhost/password/reset">')
             ->assertSee('<label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>')
             ->assertSee('<input id="email" type="email" class="form-control" name="email" value="" required autofocus>')
             ->assertSee('<label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>')
             ->assertSee('<input id="password" type="password" class="form-control" name="password" required>')
             ->assertSee('<label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワード(確認用)</label>')
             ->assertSee('<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>')
             ->assertSee('<button type="submit" class="btn btn-primary">パスワードをリセットする</button>')
             ->assertSee('<p>Copyright &copy; 2019 IT Craft All Rights Reserved.</p>');

    }



}
