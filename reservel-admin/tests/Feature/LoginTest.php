<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Log;

use Auth;
class LoginTest extends TestCase
{
    use DatabaseMigrations;

    //Topページアクセステスト
    public function testTopPage()
    {
        Log::Info('Topページアクセステスト Start');

        $this->get('/')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト

        Log::Info('Topページアクセステスト End');
    }
    //ログインフォームアクセステスト
    public function testCanViewLoginForm()
    {
        Log::Info('ログインフォームアクセステスト Start');

        $this->get('/login')
             ->assertStatus(200)
             ->assertSee('<title>ログイン - 管理画面 - あかつき動物病院 - リザベル</title>')
             ->assertSee('<h1>あかつき動物病院　管理画面　ログイン</h1>')
             ->assertSee('メールアドレス')
             ->assertSee('<input id="email" type="email" class="form-control" name="email" value="" required autofocus>')
             ->assertSee('パスワード')
             ->assertSee('<input id="password" type="password" class="form-control" name="password" required>')
             ->assertSee('<input class="form-check-input" type="checkbox" name="remember" id="remember" >')
             ->assertSee('次回から入力省略')
//             ->assertSee('<button type="submit" class="btn btn-primary">Login</button>')
             ->assertSee('パスワードを忘れた方');

        Log::Info('ログインフォームアクセステスト End');
    }

    //トップページアクセス(未ログイン)テスト
    public function testCannotViewTopPage()
    {
        Log::Info('トップページアクセス(未ログイン)テスト Start');

        $this->get('/index')
             ->assertStatus(302)
             ->assertRedirect('/login');  // /loginへのリダイレクト

        Log::Info('トップページアクセス(未ログイン)テスト End');
    }
    //  トップページアクセス(ログイン済)テスト
    public function testCanLogin()
    {
        Log::Info('トップページアクセス(ログイン済)テスト Start');

        // ユーザーを作成
        $login = factory(User::class)->create([
                  'password'  => bcrypt('test1111'),
                ]);
        // まだ、認証されていない
        $this->assertFalse(Auth::check());
        // ログインを実行
        $response = $this->post('login', [
            'email'    => $login->email,
            'password' => 'test1111',
        ]);
     
        // 認証されている
        $this->assertTrue(Auth::check());
     
        // ログイン後にトップページにリダイレクトされるのを確認
        $response->assertRedirect('reserve/index');

        Log::Info('トップページアクセス(ログイン済)テスト End');
    }

    //ログインテスト(パスワードエラー)
    public function testInvalidPasswordCannotLogin()
    {
        Log::Info('ログインテスト(パスワードエラー) Start');

        // 管理者ユーザーを作成
        $user = factory(User::class)->create([
            'password'  => bcrypt('test1111')
        ]);
        // まだ、認証されていないことを確認
        $this->assertFalse(Auth::check());
        // 異なるパスワードでログイン
        $response = $this->post('login', [
            'email'    => $user->email,
            'password' => 'test2222'
        ]);
        // 認証失敗で、認証されていない
        $this->assertFalse(Auth::check());
        // セッションにエラーを含むことを確認
//        $response->assertSessionHasErrors(['email']);
        // エラメッセージを確認
        $response->assertDontSee('auth.failed');
        $this->assertEquals('メールアドレスあるいはパスワードが一致しません。', session('errors')->first('email'));

        Log::Info('ログインテスト(パスワードエラー) End');
    }

    //ログアウトテスト
    public function testLogout()
    {
        Log::Info('ログアウトテスト Start');

        // ログインユーザーを作成
        $login = factory(User::class)->create([]);
        // 認証済みにする
        $this->actingAs($login);
        // 認証されていること確認
        $this->assertTrue(Auth::check());
        // ログアウトを実行
        $response = $this->post('logout');
        // 認証されていない
        $this->assertFalse(Auth::check());
        // Global Topページにリダイレクトすることを確認
        $response->assertRedirect('/');

        Log::Info('ログアウトテスト End');
    }

}
